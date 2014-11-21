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
     * @var Array Sort order for find()
     */
    protected $sort = array();

    /**
     * List of properties that are references to other DomainObjects or DomainCollections
     * @var Array
     */
    protected $References = array();

    /**
     * List of properties that are references to other DomainObjects or DomainCollections
     * @var Array
     */
    protected $DynamicProps = array();

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
     * @param Array List of properties to clear
     */
    public function clearData(Array $keys = null)
    {
        // clear storables
        $keys = $keys ? $keys : $this->getKeys();
        foreach($keys as $key) {
            if(isset($this->$key) && !is_null($this->$key)) {
                if (is_array($this->$key)) {
                    $this->$key = array();
                } else {
                    $this->$key = null;
                }
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
     * Set the sorting order for queries
     * @param Array $sort Associative array(column => order, ...) to sort by. Order may be ASC|DESC
     *
     * @example $this->setSort(array('id' => 'ASC', 'name' => 'DESC'))
     */
    public function setSort(Array $sort = array())
    {
        $this->sort = $sort;
        return $this;
    }

    /**
     * Get the sorting order for queries
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * Shortcut to set() or get() the sort.
     * @param Array $sort Optional. Set $sort or get the sort if $sort is empty.
     */
    public function sort(Array $sort = array())
    {
        return $sort ? $this->setSort($sort) : $this->getSort($sort);
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
     * Set a property as a reference to another class
     * @param String $name Property Name to make a reference
     * @param String $class Reference Class name
     */
    public function setReference($name, $class)
    {
        $this->References[$name] = $class;
        return $this;
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
     * Add a key to Domain Object so property is persisted on save()
     * @return self
     */
    public function addKey($name)
    {
        $this->getKeys();
        $this->keys[] = $name;
        return $this;
    }

    /**
     * Removes a key from Domain Object so property is not persisted on save()
     * @return self
     */
    public function removeKey($name)
    {
        $this->getKeys();
        if(($key = array_search($name, $this->keys)) !== false) {
            unset($this->keys[$key]);
        }
        return $this;
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

    /**
     * Custom property getters
     * @example retrieving $this->foo maps to $this->getFoo()
     */
    public function __get($name)
    {
        $method = 'get' . ucfirst($name);
        if (method_exists($this, $method)) {
            return $this->$method();
        }
        // retrieve a reference to another Model
        if (in_array($name, array_keys($this->References))) {
            // dynamic references
            if (isset($this->DynamicProps[$name])) {
                return $this->DynamicProps[$name];
            }
            // check if property exists since isset() returns true if it doesn't
            if (!property_exists($this, $name) || !isset($this->$name)) {
                // @note if !property_exists($this, $name) then $this->$name resolves to $this->DynamipProps[$name]
                $this->$name = $this->findReference($name);
            }
        }
        // get a dynamically set property
        if (!property_exists($this, $name) && isset($this->DynamicProps[$name])) {
            return $this->DynamicProps[$name];
        }
        return isset($this->$name) ? $this->$name : null;
    }

    /**
     * Custom property setters
     * @example setting $this->foo = $bar maps to $this->setFoo($bar)
     */
    public function __set($name, $value)
    {
        // call custom set method
        $method = 'set' . ucfirst($name);
        if (method_exists($this, $method)) {
            return $this->$method($value);
        }
        // references can only be models or ModelCollections
        if (in_array($name, array_keys($this->References))) {
            if (!($value instanceof DomainObject || $value instanceof DomainCollection)) {
                throw new Exception('Invalid type set to reference. Must be Model or ModelCollection.');
            }
        }
        // dynamic properties
        if (!property_exists($this, $name)) {
            // dynamically set a reference
            if ($value instanceof DomainObject || $value instanceof DomainCollection) {
                $this->setReference($name, get_class($value));
            }
            // add a persistence key for dynamic property
            if (!$this->strictOnlyKeys) {
                $this->addKey($name);
            }
            return $this->DynamicProps[$name] = $value;
        }
        return $this->$name = $value;
    }

    /**
     * Custom property isset() calls
     * @example isset($this->foo) will work as intended
     */
    public function __isset($name)
    {
        // properties with getters are always set
        $method = 'get' . ucfirst($name);
        if (method_exists($this, $method)) {
            return true;
        }
        // references are always set since they are lazy loaded
        if (array_key_exists($name, $this->References)) {
            return true;
        }
        // dynamic properties
        if (!property_exists($this, $name)) {
            return isset($this->DynamicProps[$name]);
        }
        return isset($this->$name);
    }

    /**
     * Custom property_exist()
     * PHP's built in property_exists() cannot detect properties exposed by __get().
     * Call this method to correct check if the property exists and will be returned by __get()
     *
     * @example $this->property_exists('foo') will work as intended while property_exists($this, 'foo') doesn't
     */
    public function property_exists($name)
    {
        // properties with getters are always set
        $method = 'get' . ucfirst($name);
        if (method_exists($this, $method)) {
            return true;
        }
        // references are always set since they are lazy loaded
        if (array_key_exists($name, $this->References)) {
            return true;
        }
        // dynamic properties
        if (!property_exists($this, $name)) {
            return array_key_exists($name, $this->DynamicProps);
        }
        return true;
    }

    /**
     * Custom property unset
     * @example unset($this->foo) will work as intended
     * @return null
     */
    public function __unset($name)
    {
        // call custom unset method
        $method = 'unset' . ucfirst($name);
        if (method_exists($this, $method)) {
            $this->$method($name);
        }
        // references need to be removed from references map
        if (in_array($name, array_keys($this->References))) {
            unset($this->References[$name]);
        }
        // unset dynamic properties
        if (!property_exists($this, $name) && isset($this->DynamicProps[$name])) {
            $this->removeKey($name);
            unset($this->DynamicProps[$name]);
        } else {
            unset($this->$name);
        }
    }

    /**
     * Custom method calls
     * Allows arbitrary method calls that return the first parameter if no method exists
     * Used in the onFind(), afterFind() etc. events
     */
    public function __call($method, $params = array())
    {
        if (method_exists($this, $method)) {
            return call_user_func_array(array($this, $method), $params);
        }
        return isset($params[0]) ? $params[0] : null;
    }

}
