<?php

namespace service\DataProvider\RedBean;

/**
 * Data Provider implementation for MySQL
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_App
 */

use Fiji\Factory;
use Fiji\Service\DataProvider;
use Fiji\Service\DomainObject, Fiji\Service\DomainCollection;
use Fiji\App\Config;
use RedBeanPHP\OODBBean;
use Exception;

use R;

/**
 * Handles storage/retrieval from MySQL database
 */
class RedBean implements DataProvider
{

    protected $tablePrefix = '';

    /**
     * Construct and connect to DB
     */
    public function __construct(Config $Config = null)
    {

        if (!$Config) {
            $Config = Factory::getSingleton('config\\Service');
        }

        $dbtype = $Config->get('dbtype', 'mysql');
        $host = $Config->get('host');
        $user = $Config->get('user');
        $password = $Config->get('password');
        $database = $Config->get('database');

        $this->tablePrefix = $Config->get('tablePrefix');

        $conn_str = $dbtype . ':host=' . $host . ';dbname=' . $database;

        require_once(__DIR__ . '/rb.php');

        if ($dbtype == 'sqlite') {
            $path = $Config->get('path');
            $conn_str = $dbtype . ':' . $path;
            if (!is_dir(dirname($path))) {
                if (!mkdir(dirname($path))) {
                    throw new Exception('Failed to create path to sqlite database. The "path" in config/Service.php needs to be writable by php.');
                }
            }
        }

        R::setup($conn_str, $user, $password);

        // new version of RB does not allow setStrictTyping
        R::ext('xdispense', function($type) {
            return R::getRedBean()->dispense($type);
        });
        //R::setStrictTyping(false);
    }

    /**
     * Find object given an ID
     * @var $id Unique domain object ID
     */
    public function findById(DomainObject $DomainObject, $id) {
        $tableName = $this->getTableName($DomainObject);
        $idName = $DomainObject->getIdKey();

        $bean = R::load($tableName, $id);

        return $this->getBeanProperties($bean, $DomainObject);
    }

    /**
     * Find Domain Objects matching query
     * @var $query Mixed (Array|\Fiji\Service\Query|String)
     * @return Array
     */
    public function findOne(DomainObject $DomainObject, $query = array(), $sort = array()) {
        return $this->find($DomainObject, $query, $sort, 0, 1);
    }

    /**
     * Find Domain Objects matching query
     * @todo impose limit and lazy load
     */
    public function find(DomainObject $DomainObject, $query = array(), $sort = array(), $start = 0, $limit = 10) {
        $tableName = $this->getTableName($DomainObject);
        $idName = $DomainObject->getIdKey();

        // @todo support all mysql queries
        $where = array();
        $query = is_array($query) ? $query : array();
        $_query = array();
        foreach($query as $name => $value) {
            $_query[':' . $name] = $value;
            $where[] = "`$name` = :$name";
        }
        $where = implode(' AND ', $where);

        // set sorting ie. query order by
        if ($sort = $sort ? $sort : $DomainObject->getSort()) {
            $DomainObject->setSort($sort); // remember sort
            $_sort = array();
            foreach($sort as $col => $order) {
                $_sort[] = "`$col` $order";
            }
            $where .= (empty($query) ? ' 1 = 1' : '') . ' ORDER BY ' . implode(',', $_sort);
        }

        if ($limit == 1 && $start == 0) {
            $bean = R::findOne($tableName, $where, $_query);
            return $bean ? $this->getBeanProperties($bean, $DomainObject) : array();
        }

        $beans = R::find($tableName, $where, $_query);

        $list = array();
        if ($beans) {
            foreach($beans as $bean) {
                $list[] = $this->getBeanProperties($bean, $DomainObject);
            }
        }

        return $list;
    }

    /**
     * Find referenced DomainObject or DomainCollection
     * @todo implement fully
     */
    public function findReference(DomainObject $DomainObject, $RefObject, $name, $limit = 100)
    {
        // we need to know the object id to retrieve references
        if (!$DomainObject->getId()) {
            return array();
        }

        // get reference name
        $tableName = $this->getTableName($DomainObject);

        // @todo create bean from data already loaded
        $bean = R::load($tableName, $DomainObject->id);
        $refLinkName = $this->getRefName($RefObject);
        
        $list = array();
        foreach($bean->$refLinkName as $refBean) {
            $list[] = $refBean->getProperties();
        }

        return $RefObject instanceof DomainObject ? @$list[0] : $list;
    }

    /**
     * Save a Domain Object to storage
     * @todo save the references
     */
    public function saveOne(DomainObject $DomainObject, $saveRefs = true) {

        // create our bean with our specific structure
        $bean = $this->createBean($DomainObject);

        // save references
        if ($saveRefs) {
            $this->saveRefs($DomainObject, $bean);
        }

        $id = R::store($bean);

        if ($id) {
            $DomainObject->id = $id;

            if ($saveRefs) {
                $this->augmentRefIds($DomainObject, $bean);
            }

        }

        return $id;

    }

    /**
     * Retrieve the data in the bean
     * @todo Implement same logic in $this->find() so we can search array properties
     */
    protected function getBeanProperties(OODBBean $bean, DomainObject $DomainObject)
    {
        $data = $bean->getProperties();
        foreach($data as $name => $value) {
            // this is an array
            if (strpos($name, '_php_') === 0) {
                // do not overwrite real (exposed) property.
                // @important this allows arbitrary data types per property. 
                // saving should handle property type strictness.
                if ($value && !isset($data[substr($name, 5)])) {
                    $data[substr($name, 5)] = unserialize($value);
                }
                unset($data[$name]); // remove this as it's soley storage

            // this may be named differently in DomainObject and auto converted by redBean on save
            // @todo this is dangerous. Fix redBean or impose restriction programatically on DomainObject property names
            } elseif (strpos($name, '_') !== false && !isset($DomainObject->$name)) {
                // copy data to other possible names.
                $_name = str_replace(' ', '', ucwords(str_replace('_', ' ', $name)));
                if (!isset($data[$_name]) && $DomainObject->property_exists($_name)) {
                    $data[$_name] = $value;
                    unset($data[$name]);
                } else {
                    $_name[0] = strtolower($_name[0]);
                    if (!isset($data[$_name])  && $DomainObject->property_exists($_name)) {
                        $data[$_name] = $value;
                        unset($data[$name]);
                    }
                }
            }
        }
        return $data;
    }

    /**
     * Create a bean from the DomainObject
     * @param DomainObject
     * @return OODBBean
     */
    protected function createBean(DomainObject $DomainObject)
    {
        $tableName = $this->getTableName($DomainObject);
        $bean = R::xdispense($tableName); // limited to alpha

        // copy DomainObject properties to bean
        foreach($DomainObject as $name => $value) {
            if (!is_null($value)) {
                if (is_array($value) || is_object($value)) {
                    // arrays are saved as one to one relations
                    $this->saveComplexDataType($DomainObject, $bean, $name, $value);
                } else {
                    // save scalar values directly
                    $bean->$name = $value;
                }
            }
        }

        return $bean;
    }

    /**
     * Save an array
     */
    protected function saveComplexDataType(DomainObject $DomainObject, OODBBean $bean, $name, $value)
    {
        $arrName = '_php_' . $name;
        $bean->$arrName = serialize($value);
    }

    /**
     * Save the references
     */
    protected function saveRefs(DomainObject $DomainObject, OODBBean $bean)
    {
        foreach($DomainObject->References as $ref => $class) {
            $Refs = $DomainObject->$ref;
            $refLinkName = $this->getRefName($Refs);
            // if only one DomainObject, wrap in []
            if ($Refs instanceof DomainObject) {
                $Refs = array($Refs);
            }
            // Set bean relation for all DomainObjects in DomainCollection
            foreach($Refs as $Ref) {
                // get a bean for each DomainObject
                $refBean = $this->createBean($Ref);
                $bean->{$refLinkName}[] = $refBean;
                $Ref->_refBean = $refBean; // so we can reference later
            }
        }
    }

    /**
     * Copy the saved bean ids to the referenced DomainObject
     */
    protected function augmentRefIds(DomainObject $DomainObject, OODBBean $bean)
    {
        // update DomainObject reference ids with saved ref Bean ids
        foreach($DomainObject->References as $ref => $class) {
            $Refs = $DomainObject->$ref;
            $refLinkName = $this->getRefName($Refs);
            // if only one DomainObject, wrap in []
            if ($Refs instanceof DomainObject) {
                $Refs = array($Refs);
            }
            // set id for each Refs given linked beans
            foreach ($Refs as $Ref) {
                $Ref->id = $Ref->_refBean->id;
                unset($Ref->_refBean);
            }
        }
    }

    /**
     * Save all Domain Objects in Collection to storage
     * @todo save the references
     */
    public function save(DomainCollection $DomainCollection) {

        // @todo batch save
        foreach($DomainCollection as $DomainObject) {
            $this->saveOne($DomainObject);
        }

        return true;
    }

    /**
     * Delete a Domain Object from storage
     */
    public function deleteOne(DomainObject $DomainObject) {
        $tableName = $this->getTableName($DomainObject);
        $id = $DomainObject->getId();
        if (!$id) {
            return false;
        }

        $bean = R::xdispense($tableName);
        foreach($DomainObject as $name => $value) {
            $bean->$name = $value;
        }
        return R::trash($bean);
    }

    /**
     * Delete all Domain Objects in Collection from storage
     */
    public function delete(DomainCollection $DomainCollection) {
        if (count($DomainCollection) == 0) {
            return false;
        }
        $DomainObject = $DomainCollection->getDomainObject();
        $tableName = $this->getTableName($DomainObject);
        $ids = $DomainCollection->getIds();
        $query = "DELETE from " . $tableName .
            " WHERE `" . $DomainObject->getIdKey() . "` IN (" . implode(',', $ids)
            . ")";
        $result = R::exec($query);
        return (bool) $result;
    }

    /**
     * Domain Object names mapped to MySQL table names
     */
    protected function getTableName(DomainObject $DomainObject)
    {
        return strtolower($this->tablePrefix . $DomainObject->getObjectName());
    }

    /**
     * Reference Object names mapped to RedBean relation name
     * @param DomainCollection | DomainObject
     */
    protected function getRefName($RefObject)
    {
        if ($RefObject instanceof DomainObject) {
            $name = 'own' . ucfirst($this->getTableName($RefObject)) . 'List';
        } else {
            $name = 'shared' . ucfirst($this->getTableName($RefObject->getDomainObject())) . 'List';
        }
        return $name;
    }

}
