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
use Fiji\Service\Service;

/**
 * Base Domain Object
 */
abstract class DomainObject implements \ArrayAccess, \Countable, \IteratorAggregate
{
    /**
     * @var String Model Id key/attribute name
     */
    protected $idKey = 'id';

    /**
     * @var String Namespace of object
     */
    protected $objectName = null;

    /**
     * @var Array Attributes of Domain Object
     */
    protected $keys = array();

    /**
     * @var Fiji\Service\Service Service interface
     */
    protected $Service;

    /**
     * @var Int Unique ID of domain object instance
     */
    protected $id;

    /**
     * @var Bool Only the keys given by $this->getKeys() can be set
     */
    protected $strictOnlyKeys = true;

    /**
     * Construct and set data
     * @param $data {Array} Data Array
     */
    public function __construct(Array $data = null)
    {
        if (isset($data)) {
            $this->setData($data);
        }
    }

    /**
     * Set data from Array
     * @param Array $data Normalized data array to set as properties of this object
     * @see self::setDynamic()
     */
    public function setData(Array $data = array())
    {
        // set new data
        $keys = $this->getKeys();
        foreach($data as $name => $value) {
            if (!$this->strictOnlyKeys || in_array($name, $keys)) {
                // set from external so we trigger $this->__set()
            	Service::setDomainObjectProperty($this, $name, $value);
			}
        }
        return $this;
    }

    /**
     * Clear the data from the model
     */
    public function clearData()
    {
        // clear storables
        $keys = $this->getKeys();
        foreach($keys as $key) {
            if(isset($this->$key) && !is_null($this->$key)) {
                $this->$key = null;
            }
        }
        // clear references
        foreach($this->References as $name => $class) {
            // __isset() and __set() invoked if property doesn't exist. Even within the same class!
            if (property_exists($this, $name) && isset($this->$name)) {
                $this->$name = null;
            }
        }

        return $this;
    }

    /**
     * Set flag for strict setting of data (only keys)
     * @param Bool $flag True for strictly properties in getKeys() or false for any properties
     */
    public function setStrictOnlyKeys($flag = true)
    {
        $this->strictOnlyKeys = $flag;
        return $this;
    }

    /**
     * Allow setting arbitrary values and optionally set the given values from an Array
     * @param Array $data Arbitrary data array to set as properties of this object
     */
    public function setDynamic(Array $data = array())
    {
        $this->setStrictOnlyKeys(false);
        if ($data) {
            $this->setData($data);
        }
        return $this;
    }

    /**
     * Load data to model given the ID
     * @var $id Unique domain object ID
     */
    public function findById($id)
    {
        $this->clearData();
        $this->setData($this->getService()->findById($this, $id));
        return $this;
    }

    /**
     * Load data to model given the query
     * @var $id Unique domain object ID
     */
    public function find($query = null)
    {
        $this->clearData();
        $this->setData($this->getService()->findOne($this, $query));
        return $this;
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
     * Retrieve a reference to another Model
     * @note By convention References named '{name}Collection' are Collections
     */
    public function findReference($name)
    {
        $className = $this->References[$name];
        // if classname has 'Collection' create a ModelCollection instead of a Model
        $obj = stristr($name, 'Collection') ?
            Factory::createModelCollection($className) : Factory::createModel($className);
        // make sure we use the same service for the reference
        $obj->setService($this->getService()); 
        $obj->setData($obj->getService()->findReference($this, $obj, $name));
        return $obj;
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
     * Set the name of the ID attribute/key
     */
    public function setId($id)
    {
        return $this->{$this->getIdKey()} = is_null($id) ? $id : intval($id);
    }

    /**
     * @deprecated Use getObjectName()
     */
    public function getName()
    {
        return $this->getObjectName();
    }

    /**
     * Return name of Domain Object mapped to storage
     */
    public function getObjectName()
    {
        if (!$this->objectName) {
            $className = get_class($this);
            // take only last class name if namespaced
            if (($pos = strrpos($className, '\\')) !== false) {
                $className = substr($className, $pos+1);
            }
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

            $this->keys = array('id'); // id mandatory for storage
            foreach($refProps as $refProp) {
                $this->keys[] = $refProp->name;
            }
            $this->keys = array_unique($this->keys);
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
    public function setService(Service $Service)
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
