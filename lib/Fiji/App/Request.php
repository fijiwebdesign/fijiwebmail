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

/**
 * Interface to the HTTP request received by Server/PHP
 */
class Request {
    
    public function __construct() {}
    
    /**
     * Retrieve a GET or POST variable
     */
    public function get($name, $default = null)
    {
        return isset($_REQUEST[$name]) ? $_REQUEST[$name] : $default;
    }
    
    /**
     * Retrieve a GET or POST variable
     */
    public function set($name, $value = null)
    {
        $_REQUEST[$name] = $value;
    }
    
    /**
     * Retrieve a GET or POST variable
     */
    public function getVar($name, $default = null)
    {
        return isset($_REQUEST[$name]) ? $_REQUEST[$name] : $default;
    }
    
    /**
     * Retrieve only alphanumeric http var
     */
    public function getAlphaNum($name, $default = null)
    {
      $var = $this->getVar($name, $default);
      return preg_replace("/[^a-z0-9_\-]+/i", '', $var);
    }
	
	/**
	 * Return the current URI
	 */
	public function getUri()
	{
		return $_SERVER['REQUEST_URI'];
	}
    
}
