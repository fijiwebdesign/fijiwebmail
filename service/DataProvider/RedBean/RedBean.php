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

        return $bean->getProperties();
    }

    /**
     * Find Domain Objects matching query
     * @var $query Mixed (Array|\Fiji\Service\Query|String)
     * @return Array
     */
    public function findOne(DomainObject $DomainObject, $query = array()) {
        $tableName = $this->getTableName($DomainObject);
        $idName = $DomainObject->getIdKey();

        // @todo support all mysql queries
        $where = array();
        $query = is_array($query) ? $query : array();
        foreach($query as $name => $value) {
            $where[] = "`$name` = :$name";
        }
        $where = implode(' AND ', $where);

        $query = array_flip($query);
        $query = array_map(function($value) {
            return ":" . $value;
        }, $query);
        $query = array_flip($query);

        $bean = R::findOne($tableName, $where, $query);
        return $bean ? $bean->getProperties() : array();
    }

    /**
     * Find Domain Objects matching query
     */
    public function find(DomainObject $DomainObject, $query = array(), $start = 0, $limit = 10) {
        $tableName = $this->getTableName($DomainObject);
        $idName = $DomainObject->getIdKey();

        // @todo support all mysql queries
        $where = array();
        $query = is_array($query) ? $query : array();
        foreach($query as $name => $value) {
            $where[] = "`$name` = :$name";
        }
        $where = implode(' AND ', $where);

        $query = array_flip($query);
        $query = array_map(function($value) {
            return ":" . $value;
        }, $query);
        $query = array_flip($query);

        $beans = R::find($tableName, $where, $query);

        $list = array();
        if ($beans) {
            foreach($beans as $bean) {
                $list[] = $bean->getProperties();
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

        $tableName = $this->getTableName($DomainObject);
        $bean = R::xdispense($tableName); // limited to alpha

        // copy DomainObject properties to bean
        foreach($DomainObject as $name => $value) {
            if (!is_null($value)) {
                $bean->$name = $value;
            }
        }

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
     * Save the references
     */
    protected function saveRefs(DomainObject $DomainObject, $bean)
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
                $refBean = R::xdispense($this->getTableName($Ref));
                // copy Ref DomainObject properties to Ref Bean
                foreach($Ref as $name => $value) {
                    if (!is_null($value)) {
                        $refBean->$name = $value;
                    }
                }
                $bean->{$refLinkName}[] = $refBean;
                $Ref->_refBean = $refBean; // so we can reference later
            }
        }
    }

    /**
     * Copy the saved bean ids to the referenced DomainObject
     */
    protected function augmentRefIds($DomainObject, $bean)
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
