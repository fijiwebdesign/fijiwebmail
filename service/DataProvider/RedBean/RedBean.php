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
        
        require_once(__DIR__ . '/rb.php');
        R::setup($dbtype . ':host=' . $host . ';dbname=' . $database, 
            $user, $password);
            
        R::setStrictTyping(false);
    }
    
    /**
     * Find object given an ID
     * @var $id Unique domain object ID
     */
    public function findById(DomainObject $DomainObject, $id) {
        $tableName = $this->getName($DomainObject);
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
        $tableName = $this->getName($DomainObject);
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
        $tableName = $this->getName($DomainObject);
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
     * Save a Domain Object to storage
     */
    public function saveOne(DomainObject $DomainObject) {
        
        $tableName = $this->getName($DomainObject);
        $bean = R::dispense($tableName); // limited to alpha
        
        foreach($DomainObject as $name => $value) {
            if (!is_null($value)) {
                $bean->$name = $value;
            }
        }
        
        $id = R::store($bean);
        if ($id) {
            $DomainObject->id = $id;
        }       
        return $id;
        
    }
    
    /**
     * Save all Domain Objects in Collection to storage
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
        $tableName = $this->getName($DomainObject);
        $id = $DomainObject->getId();
        if (!$id) {
            return false;
        } 
        
        $bean = R::dispense($tableName);
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
        $tableName = $this->getName($DomainObject);
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
    protected function getName(DomainObject $DomainObject)
    {
        return $this->tablePrefix . $DomainObject->getName();
    }
    
}