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

/**
 * Base Model
 */
abstract class Model extends DomainObject
{
    
    /**
     * Construct and set the service used to retrieve/store data
     * @param $Service {Fiji\App\Service} Service Instance
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
        if (is_callable(array($this, $method))) {
            return $this->$method();
        }
        return $this->$name;
    }
    
    /**
     * Custom property setters 
     * @example setting $this->foo = $bar maps to $this->setFoo($bar)
     */
    public function __set($name, $value)
    {
        $method = 'set' . ucfirst($name);
        if (is_callable(array($this, $method))) {
            return $this->$method($value);
        }
        return $this->$name = $value;
    }
    
    /**
     * Custom method calls
     */
    public function __call($method, $params = array())
    {
        if (method_exists($this, $method)) {
            return call_user_func_array(array($this, $method), $params);
        }
    }
    
    /**
     * Trigger onFindById()
     */
    public function findById($id)
    {
        if ($this->onFindById($id) !== false) {
            return parent::findById($id);
        }
    }
    
    /**
     * Trigger onFind()
     */
    public function find($query)
    {
        if ($this->onFind($query) !== false) {
            return parent::find($query);
        }
    }
    
    /**
     * Trigger onSave()
     */
    public function save()
    {
        if ($this->onSave() !== false) {
            return parent::save();
        }
    }
    
    /**
     * Trigger onDelete()
     */
    public function delete()
    {
        if ($this->onDelete() !== false) {
            return parent::delete();
        }
    }

}
