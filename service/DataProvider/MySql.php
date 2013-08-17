<?php

namespace service\DataProvider;

/**
 * Data Provider implementation for MySQL
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_App
 */

use Fiji\Factory;
use Fiji\Service\DomainObject, Fiji\Service\DomainCollection;

/**
 * Handles storage/retrieval from MySQL database
 */
class MySql implements \Fiji\Service\DataProvider
{
    
    protected $tablePrefix = '';
    
    /**
     * Construct and connect to DB
     */
    public function __construct(\Fiji\App\Config $Config = null)
    {
        
        if (!$Config) {
            $Config = Factory::getSingleton('config\\App');
        }
        
        $host = $Config->get('host');
        $user = $Config->get('user');
        $password = $Config->get('password');
        $database = $Config->get('database');
        
        $this->tablePrefix = $Config->get('tablePrefix');
        
        if (!$this->link = mysql_connect($host, $user, $password)) {
            throw new Exception(mysql_error($this->link));
        }
        if (!mysql_select_db($database, $this->link)) {
            throw new Exception(mysql_error($this->link));
        }
    }
    
    /**
     * Find object given an ID
     * @var $id Unique domain object ID
     */
    public function findById(DomainObject $DomainObject, $id) {
        $tableName = $this->getName($DomainObject);
        $idName = $DomainObject->getIdKey();
        $query = "SELECT * FROM `$tableName` 
            WHERE `$idName` = " . intval($id) . " LIMIT 1";
            
        $data = $this->fetchData($query);
        return isset($data[0]) ? $data[0] : array();
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
        foreach($query as $name => $value) {
            $where[] = "`$name` = '" . mysql_real_escape_string($value, $this->link) . "'";
        }
        $where = implode(' AND ', $where);
        
        $query = "SELECT * FROM `$tableName` 
            WHERE $where LIMIT 1";
            
        $data = $this->fetchData($query);
        return isset($data[0]) ? $data[0] : array();
    }
    
    /**
     * Find Domain Objects matching query
     */
    public function find(DomainObject $DomainObject, $query = array(), $start = 0, $limit = 10) {
        $tableName = $this->getName($DomainObject);
        $idName = $DomainObject->getIdKey();
        
        // @todo support all mysql queries
        $where = array();
        if ($query) {
            foreach($query as $name => $value) {
                $where[] = "`$name` = '" . mysql_real_escape_string($value, $this->link) . "'";
            }
        }
        $where = implode(' AND ', $where);
        
        $query = "SELECT * FROM `$tableName` 
            WHERE " . ($where ? $where : 1) . " LIMIT $start, $limit";
            
        $data = $this->fetchData($query);
        return $data;
    }
    
    /**
     * Save a Domain Object to storage
     */
    public function saveOne(DomainObject $DomainObject) {
        
        $tableName = $this->getName($DomainObject);
        
        $names = $DomainObject->getKeys();
        $values = $DomainObject->getValues();
        
        foreach($names as $key => $val) {
            if (!$values[$key]) {
                unset($names[$key]);
                unset($values[$key]);
            } 
        }
        
        $names = '`' . implode('`, `', $names) . '`';
        $values = "'" . implode("', '", array_map('mysql_real_escape_string', $values)) . "'";
        
        $query = "REPLACE INTO `" . $tableName . "` (" . $names . ") VALUES 
            ($values)
        ";
        $result = mysql_query($query, $this->link);
        $DomainObject->id = mysql_insert_id();
        
        if (!$result) {
            var_dump($query);
            throw new Exception(mysql_error($this->link));
        }
        return (bool) $result;
    }
    
    /**
     * Save all Domain Objects in Collection to storage
     * @todo Insert IDs into the DomainObjects in DomainCollection
     */
    public function save(DomainCollection $DomainCollection) {
        
        $DomainObject = $DomainCollection->getDomainObject();
        $tableName = $this->getName($DomainObject);
        $columns = '`' . implode('`, `', $DomainObject->getKeys()) . '`';
        
        $data = array();
        foreach($DomainCollection as $DomainObject) {
            $values = $DomainObject->getValues();
            $data[] = "('" . implode("', '", array_map('mysql_real_escape_string', $values)) . "')";
        }
        
        $query = "REPLACE INTO `" . $tableName . "` (" . $columns . ") VALUES 
            " . implode(',', $data) . "
        ";
        $result = mysql_query($query, $this->link);
        if (!$result) {
            var_dump($query);
            throw new Exception(mysql_error($this->link));
        }
        return (bool) $result;
    }
    
    /**
     * Delete a Domain Object from storage
     */
    public function deleteOne(DomainObject $DomainObject) {
        $id = $DomainObject->getId();
        if (!$id) {
            return false;
        }
        $tableName = $this->getName($DomainObject);
        $query = "DELETE from " . $tableName . 
            " WHERE `" . $DomainObject->getIdKey() . "` = " . $id
            . " LIMIT 1";
        $result = mysql_query($query, $this->link);
        if (!$result) {
            var_dump($query);
            throw new Exception(mysql_error($this->link));
        }
        return (bool) $result;
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
        $result = mysql_query($query, $this->link);
        if (!$result) {
            var_dump($query);
            throw new Exception(mysql_error($this->link));
        }
        return (bool) $result;
    }
    
    /**
     * Domain Object names mapped to MySQL table names
     */
    protected function getName(DomainObject $DomainObject)
    {
        return $this->tablePrefix . $DomainObject->getName();
    }
    
    /**
     * Fetch and object from database
     */
    protected function fetchData($query)
    {
        $result = mysql_query($query, $this->link);
        if (!$result) {
            var_dump($query);
            throw new Exception(mysql_error($this->link));
        }
        $results = array();
        while($data = mysql_fetch_assoc($result)) {
            if (!$data && mysql_error($this->link)) {
                throw new Exception(mysql_error($this->link));
            }
            $results[] = $data;
        }
        
        return $results;
    }
    
}