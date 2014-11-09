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
 * @todo move session to a Model that uses a DataProvider handling PHP session interface
 * @todo parent::getKeys() should also look for getters eg: __get{Key}()
 *          This way we can make keys protected properties and have a getter that is visible as a model key
 * @todo Move the getPersistentKeys() out and use separate configs per service.
 *       The Session would require a Service created. (Session is just another Service Interface)
 *       This also moves Zend session code out of Application.
 */
class User extends Model
{

    /**
     * @var Zend\Session\Container Session
     */
    protected $Session;

    /**
     * @var Int expiration of session in seconds
     */
    protected $expiresSecs = 3600;

    /**
     * @var String Unique User name
     */
    public $username;

    /**
     * @var String Full name
     */
    public $name;

    /**
     * @var String Unique Email address
     */
    public $email;

    /**
     * @var String Password
     */
    protected $password;

    /**
     * @var Mailboxes owned by user
     */
    protected $MailboxCollection;

    /**
     * @var String User Secret used for hasing password
     */
    protected $secret;

    /**
     * @var Fiji\App\ModelCollection of Fiji\App\AccessControl\Model\Role user belongs to
     */
    protected $RoleCollection;

    public function __construct()
    {
        // add references
        $this->setReference('RoleCollection', 'Fiji\App\AccessControl\Model\Role');
        $this->setReference('MailboxCollection', 'config\user\Mail');

        // @todo We need a session interface.
        $this->Session = Factory::getUserSession($this->expiresSecs);

        // ensure autheticated user is loaded
        if ($this->isAuthenticated()) {
            // get from session
            foreach($this->getPersistKeys() as $key) {
            		$this->$key = $this->Session->$key;
            }
        }

    }

    /**
     * Sync with session on save
     */
    public function save()
    {
        // ensure autheticated user is loaded
        if ($this->isAuthenticated()) {
            // get from session
            foreach($this->getPersistKeys() as $key) {
                    $this->Session->$key = $this->$key;
            }
        }
        parent::save();
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
        return array('id', 'username', 'name', 'email', 'password', 'secret');
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
    }

    /**
     * Authenticate the User
     */
    public function authenticate($username, $password)
    {
        // 3rd party authentication class
        $Auth = Factory::getAuthentication();
        if ($Auth->authenticate($username, $password)) {
            $this->persist(); // persist user to session. Handled here so third party only handles authentication.
            return $this->isAuthenticated( true  );
        }
        return $this->isAuthenticated( false  );
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
     * @return Array
     */
    public function getNotifications()
    {
        return isset($this->Session->msgs) ? $this->Session->msgs : array();
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
     * Log user out of application.
     * @return {Bool} Status. TRUE for successful logout of 3rd party Auth service. FALSE for failure.
     */
    public function logout()
    {
        $Auth = Factory::getAuthentication();
        $this->isAuthenticated(false); // force a logout from app even if 3rd party fails.
        return $Auth->logout(); // third party auth service logout status.
    }

}
