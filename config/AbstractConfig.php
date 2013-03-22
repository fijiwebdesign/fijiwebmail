<?php
/**
 * Fiji Mail Server 
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_Mail
 */

namespace config;

abstract class AbstractConfig 
{
    public function __construct($options = null)
    {
        if (!is_null($options)) {
            $this->setOptions($options);
        }
    }
    
    /**
     * Set overriding configs
     */
    public function setOptions($options = array())
    {
        // @todo implement
    }
    
    /**
     * Get a config value by name
     */
    public function get($name, $default = null)
    {
        isset($this->$name) ? $this->$name : $default;
    }
}



