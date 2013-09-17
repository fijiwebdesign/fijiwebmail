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

/**
 * Configuration Object
 */
class Config extends DomainObject
{
    /**
     * Construct our Configuration
     * @param Fiji\App\Service $Service 
     * @param Array $options
     */
    public function __construct(Array $options = array())
    {
        parent::__construct($options);
        
        // make each property an instance of Fiji\App\Config for each access
        foreach($this->toArray() as $name => $value) {
            if (is_array($value)) {
                $this->$name = new Config($value);
            }
        }
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
}



