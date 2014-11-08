<?php
/**
 * Fiji Mail Server 
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_Mail
 */

namespace Fiji\App;

use Fiji\Service\DomainObject;
use Fiji\Factory;

/**
 * Configuration Object
 */
class Config extends DomainObject implements \IteratorAggregate
{

    /**
     * @var Bool Allow setting arbitrary keys
     */
    protected $strictOnlyKeys = true;

    /**
     * Construct our Configuration
     * @param Fiji\App\Service $Service 
     * @param Array $options
     */
    public function __construct(Array $options = array())
    {
        parent::__construct($options);

        // make each property an instance of Fiji\App\Config for easy access
        $this->setArraysToConfig();
    }

    /**
     * Namespace the object name to config so it doesn't class when stored
     */
    public function getName()
    {
        return strtolower(str_replace('\\', '_', get_class($this)));
    }
    
    /**
     * Get a config value by name
     * @param String $name
     * @param Mixed $default
     */
    public function get($name, $default = null)
    {
        return isset($this->$name) ? $this->$name : $default;
    }

    /**
     * Set data from Array
     * @param Array $data Arbitrary associative array of property => value to set
     *
     * @see parent::setData()
     */
    public function setData(Array $data = array())
    {
        // set each directly as parent::setData() has too much logic
        foreach($data as $name => $value) {
            $this->$name = $value;
        }
        return $this;
    }

    /**
     * Set arbitrary properties
     */
    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public function __isset($name)
    {
        return isset($this->$name);
    }

    public function __get($name)
    {
        return isset($this->$name) ? $this->$name : null;
    }
    
    /**
     * Serialize Config class to array
     */
    public function toArray()
    {
        $keys = $this->getKeys();
        
        $array = array();
        foreach($keys as $name) {
            $value = $this->$name;
            if ($value instanceof Config) {
                $value = $value->toArray();
            }
            $array[$name] = $this->$name;
        }
        
        return $array;
    }

    /**
     * Make all Array propeties a Config object
     */
    public function setArraysToConfig()
    {
        foreach($this as $name => $value) {
            if (is_array($value) && $this->isArrayHasIndex($value)) {
                $this->$name = new Config();
                $this->$name->setStrictOnlyKeys(false); // allow arbitrary keys
                $this->$name->setData($value);
            }
        }
    }

    /**
     * Is the Array has at least one index
     */
    protected function isArrayHasIndex($array){
        foreach($array as $key => $value) {
            if ($key === (int) $key) {
                return true;
            }
        }
        return false;
    }

    /**
     * Return Object name
     */
    public function getObjectName()
    {
        return str_replace('\\', '_', strtolower(get_class($this)));
    }

    /**
     * Record the user and time the configuration was saved
     */
    public function save()
    {
        $this->date = time();
        $this->user_id = Factory::getUser()->id;
        parent::save();
    }

    /**
     * IteratorAggregate Interface
     */
    public function getIterator() {
        return new \ArrayIterator($this);
    }
}



