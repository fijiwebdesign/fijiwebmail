<?php
/**
 * Fiji Mail Server 
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_Mail
 */

namespace Fiji\App\Model;

use Zend\Session\Container;
use Fiji\Factory;
use Fiji\App\Model;

/**
 * Allows user management
 *
 * @todo parent::getKeys() should also look for getters eg: __get{Key}() 
 *          This way we can make keys protected properties and have a getter that is visible as a model key
 * @todo Move the getPersistentKeys() out and use separate configs per service. 
 *       The Session would require a Service created. (Session is just another Service Interface)
 *       This also moves Zend session code out of Application. 
 */
class User extends Model {
    
    /**
     * @var Zend\Session\Container Session
     */
    protected $Session;
    
    /**
     * @var expiration of session in seconds
     */
    protected $expiresSecs = 3600;
    
    public $username;
    
    public $name;
    
    public $email;
    
    protected $password;
    
    protected $imapOptions;

    public $secret;
    
    public function __construct() {
        
       $this->Session = Factory::getSingleton('Zend\Session\Container', array('user'));
       $this->Session->setExpirationSeconds($this->expiresSecs);
       
       // ensure autheticated user is loaded
       if ($this->isAuthenticated()) {
           
           // get from session
           foreach($this->getPersistKeys() as $key) {
           		$this->$key = $this->Session->$key;
           }
       }

       $Config = Factory::getConfig();
       $this->secret = $Config->get('secret');
        
    }

    /**
     * Return keys we want to save to storage
     */
    public function getKeys()
    {
        return array('id', 'username', 'name', 'email', 'password', 'secret');
    }

    /**
     * Return keys we want to save to session
     */
    public function getPersistKeys()
    {
        return array('id', 'username', 'name', 'email', 'password', 'imapOptions');
    }
    
    /**
     * Retrieve session object for this user
     */
    public function getSession()
    {
        return $this->Session;
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
     * Persist user data to session
     */
    public function persist()
    {
        foreach($this->getPersistKeys() as $key) {
            $this->Session->$key = $this->$key;
        }
        log_debug($this->Session->$key);
    }
    
    /**
     * Authenticate the User
     */
    public function authenticate($username, $password)
    {
        // @todo Implement event/observer pattern authentication
        $this->find(array('username' => $username, 'password' => $password));
        return $this->isAuthenticated($this->getId());
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

    /**
     * Make sure the saved password is encrypted
     */
    public function setPassword($password)
    {
        $this->password = sha1($password . $this->secret);
    }

    /**
     * Retrieve password
     */
    public function getPassword()
    {
        return isset($this->password) ? $this->password : null;
    }
    
}
