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

use Zend\Session\Container;
use Fiji\Factory;

/**
 * Allows user management
 */
class User {
    
    /**
     * @var Zend\Session\Container Session
     */
    protected $Session;
    
    /**
     * @var expiration of session in seconds
     */
    protected $expiresSecs = 3600;
    
    public function __construct() {
        
       $this->Session = Factory::getSingleton('Zend\Session\Container', array('user'));
       $this->Session->setExpirationSeconds($this->expiresSecs);
        
    }
    
    /**
     * Expires the Session in given secs
     * @param Int $secs Seconds
     */
    public function setExpirationSeconds($secs)
    {
         $this->Session->setExpirationSeconds($secs);
    }
    
    /**
     * Gets persistent propery of Fiji\App\User to session
     * @param String $name Property name
     */
    public function __get($name)
    {
        return isset($this->Session->$name) ? $this->Session->$name : null;
    }
    
    /**
     * Sets persistent property of Fiji\App\User to session
     * @param String $name
     * @param String $value
     */
    public function __set($name, $value)
    {
        return $this->Session->$name = $value;
    }
    
    /**
     * Authenticate the User
     */
    public function authenticate()
    {
        // @todo Implement
    }
    
    /**
     * Has the user been authenticated
     * @param $result Bool Set authenticated status
     */
    public function isAuthenticated($result = null)
    {
        if (!is_null($result)) {
            $this->Session->authenticated = $result;
        }
        return $this->Session->authenticated;
    }
    
    /**
     * Returns the user messages saved in this or previous sessions
     * @return Array | Bool
     */
    public function getNotifications()
    {
        return isset($this->Session->msgs) ? $this->Session->msgs : false;
    }
    
    /**
     * Adds a user message (persists through session)
     * @param Stirng $message
     */
    public function addNotification($msg)
    {
        if (!isset($this->Session->msgs)) {
            $this->Session->msgs = array();
        }
        // cannot set array indexes to session
        return $this->Session->msgs = array_merge($this->Session->msgs, array($msg));
    }
    
    /**
     * Clear the notifications
     */
    public function clearNotifications()
    {
        return $this->Session->msgs = array();
    }
    
    /**
     * Delete users session
     */
    public function logout()
    {
        $this->isAuthenticated(false);
    }
    
    
}
