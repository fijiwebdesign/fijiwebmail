<?php

namespace Fiji\App;

/**
 * Model
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_App
 */

use Fiji\Factory;
use Fiji\Service\DomainObject;
use Exception;
use Fiji\App\ModelCollection;

/**
 * Base Model
 */
abstract class Model extends DomainObject
{
    /**
     * List of properties that are references to other Models or ModelCollections
     * @var Array
     */
    protected $References = array();

    /**
     * List of properties that are references to other Models or ModelCollections
     * @var Array
     */
    protected $DynamicProps = array();

    /**
     * Construct and set data
     * @param $data {Array} Data Array
     */
    public function __construct(Array $data = null)
    {
        parent::__construct($data);
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
            if (!($value instanceof Model || $value instanceof ModelCollection)) {
                throw new Exception('Invalid type set to reference. Must be Model or ModelCollection.');
            }
        }
        // dynamic properties
        if (!property_exists($this, $name)) {
            // dynamically set a reference 
            if ($value instanceof Model || $value instanceof ModelCollection) {
                $this->References[$name] = get_class($value);
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
        if (in_array($name, array_keys($this->References))) {
            return true;
        }
        // dynamic properties
        if (!property_exists($this, $name)) {
            return isset($this->DynamicProps[$name]);
        }
        return isset($this->$name);
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

    /**
     * Do not allow modifying the ID once it's set
     */
    public function setId($value)
    {
        if (!isset($this->id)) {
            parent::setId($value);
        }
    }

    /**
     * Trigger onFindById()
     */
    public function findById($id)
    {
        if ($result = $this->onFindById($id) !== false) {
            $model = parent::findById($id);
            $result = $this->afterFindById($model, $id);
        }
        return $result;
    }

    /**
     * Trigger onFind()
     */
    public function find($query = null)
    {
        if ($result = $this->onFind($query) !== false) {
            $model = parent::find($query);
            $result = $this->afterFind($model, $query);
        }
        return $result;
    }

    /**
     * Trigger onSave()
     */
    public function save()
    {
        if ($result = $this->onSave() !== false) {
            $result = parent::save();
            $result = $this->afterSave($result);
        }
        return $result;
    }

    /**
     * Trigger onDelete()
     */
    public function delete()
    {
        if ($result = $this->onDelete() !== false) {
            $result = parent::delete();
            $result = $this->afterDelete($result);
        }
        return $result;
    }

    /**
     * Retrieve a reference to another Model
     */
    public function findReference($name)
    {
        if ($result = $this->onFindReference($name) !== false) {
            $result = parent::findReference($name);
            $result = $this->afterFindReference($result);
        }
        return $result;
    }

    /**
     * Retrieve HTML encoded property
     * @todo decorate from template?
     * @param $name {String} Property name
     * @deprecated violates SRP. Models should not be concerned with view (rendering)
     */
    public function html($name, $quotes = ENT_QUOTES, $encoding = 'utf-8')
    {
        throw new Exception('The Model::html() method is deprecated.');
    }

}
