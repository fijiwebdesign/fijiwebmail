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
        if (method_exists($this, $method)) {
            return $this->$method();
        }
        return isset($this->$name) ? $this->$name : null;
    }
    
    /**
     * Custom property setters 
     * @example setting $this->foo = $bar maps to $this->setFoo($bar)
     */
    public function __set($name, $value)
    {
        $method = 'set' . ucfirst($name);
        if (method_exists($this, $method)) {
            return $this->$method($value);
        }
        return $this->$name = $value;
    }

    /**
     * Custom property isset() calls 
     * @example isset($this->foo) will work as intended
     */
    public function __isset($name)
    {
        $method = 'get' . ucfirst($name);
        if (method_exists($this, $method)) {
            return true;
        }
        return isset($this->$name) ? true : false;
    }
    
    /**
     * Custom method calls
     */
    public function __call($method, $params = array())
    {
        if (method_exists($this, $method)) {
            return call_user_func_array(array($this, $method), $params);
        }
        return isset($params[0]) ? $params[0] : null;
    }
    
    /**
     * Trigger onFindById()
     */
    public function findById($id)
    {
        if ($this->onFindById($id) !== false) {
            $model = parent::findById($id);
            return $this->afterFindById($model, $id);
        }
    }
    
    /**
     * Trigger onFind()
     */
    public function find($query)
    {
        if ($this->onFind($query) !== false) {
            $model = parent::find($query);
            return $this->afterFind($model, $query);
        }
    }
    
    /**
     * Trigger onSave()
     */
    public function save()
    {
        if ($this->onSave() !== false) {
            $result = parent::save();
            return $this->afterSave($result);
        }
    }
    
    /**
     * Trigger onDelete()
     */
    public function delete()
    {
        if ($this->onDelete() !== false) {
            $result = parent::delete();
            return $this->afterDelete($result);
        }
    }

    /**
     * Retrieve HTML encoded property
     * @todo decorate from template?
     * @param $name {String} Property name
     */
    public function html($name, $quotes = ENT_QUOTES, $encoding = 'utf-8')
    {
        return htmlspecialchars($this->$name, $quotes, $encoding);
    }

}
