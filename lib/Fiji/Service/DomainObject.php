<?php

namespace Fiji\Service;

/**
 * Fiji Communication Server
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_App
 */

use Fiji\Factory;
use ReflectionClass, ReflectionProperty;

/**
 * Base Domain Object
 */
abstract class DomainObject implements \ArrayAccess, \Countable, \IteratorAggregate
{
    /**
     * Model Id key/attribute name
     */
    protected $idKey = 'id';
    
    /**
     * Name of object
     */
    protected $objectName = null;
    
    /**
     * Attributes of Domain Object
     */
    protected $keys = array();
    
    /**
     * Service interface
     */
    protected $Service;
    
    /**
     * @var Unique ID of domain object instance
     */
    public $id;
    
    /**
     * Construct and set the service used to retrieve/store data
     * @param $Service {Fiji\App\Service} Service Instance
     */
    public function __construct(Array $data = null)
    {
        if (isset($data)) {
            $this->setData($data);
        }
    }
    
    /**
     * Set data from Array
     * @param Array $options
     */
    public function setData(Array $options = array(), $strictOnlyKeys = false)
    {
        foreach($options as $name => $value) {
            if (!$strictOnlyKeys || in_array($name, $this->getKeys())) {
            	$this->$name = $value;
			}
        }
    }
    
    /**
     * Load data to model given the ID
     * @var $id Unique domain object ID
     */
    public function findById($id)
    {
        $this->setData($this->getService()->findById($this, $id));
    }
    
    /**
     * Load data to model given the query
     * @var $id Unique domain object ID
     */
    public function find($query)
    {
        $this->setData($this->getService()->findOne($this, $query));
    }
    
    /**
     * Save the domain object to the service
     */
    public function save()
    {
        return $this->getService()->saveOne($this);
    }
    
    /**
     * Delete the domain object from the service
     */
    public function delete()
    {
        return $this->getService()->deleteOne($this);
    }
    
    /**
     * Return the name of the ID attribute/key
     */
    public function getIdKey()
    {
        return $this->idKey;
    }
    
    /**
     * Return the name of the ID attribute/key
     */
    public function getId()
    {
        return $this->{$this->getIdKey()};
    }
    
    /**
     * Return name of Domain Object mapped to storage
     */
    public function getName()
    {
        if (!$this->objectName) {
            $className = get_class($this);
            $className = substr($className, strrpos($className, '\\')+1);
            $this->objectName = strtolower($className);
        }
        
        return $this->objectName;
    }
    
    /**
     * Get an Array of Domain Object Properties
     * @return Array Associative key/value pairs
     */
    public function toArray()
    {
        $keys = $this->getKeys();
        
        $array = array();
        foreach($keys as $name) {
            $array[$name] = $this->$name;
        }
        
        return $array;
    }
    
    /**
     * Return Domain Object Keys
     * @return Array Keys
     */
    public function getKeys()
    {
        if (empty($this->keys)) {
            $ref = new ReflectionClass($this);
            $refProps = $ref->getProperties(ReflectionProperty::IS_PUBLIC);
            
            $this->keys = array();
            foreach($refProps as $refProp) {
                $this->keys[] = $refProp->name;
            }
        }
        return $this->keys;
    }
    
    /**
     * Return Domain Object values as
     * @return Array
     */
    public function getValues()
    {
        return array_values($this->toArray());
    }
    
    /**
     * ArrayAccess interface
     */
    public function offsetExists($i)
    {
        $vars = $this->toArray();
        return isset($vars[$i]);
    }
    
    /**
     * ArrayAccess interface
     */
    public function offsetGet($i)
    {
        $vars = $this->toArray();
        return isset($vars[$i]) ? $vars[$i] : null;
    }
    
    /**
     * ArrayAccess interface
     */
    public function offsetSet($i, $value)
    {
        $this->$i = $value;
    }
    
    /**
     * ArrayAccess interface
     */
    public function offsetUnset($i)
    {
        $this->$i = null;
    }
    
    /**
     * Countable interface
     */
    public function count()
    {
        return count($this->toArray());
    }
    
    /**
     * Retrieve the Service
     */
    public function getService()
    {
        return isset($this->Service) ? $this->Service : Factory::getService();
    }
    
    /**
     * Set the Service providing data
     */
    public function setService(\Fiji\Service\Service $Service)
    {
        $this->Service = $Service;
    }

    /**
     * IteratorAggregate Interface
     */
    public function getIterator() {
        return new \ArrayIterator($this->toArray());
    }
    
}
