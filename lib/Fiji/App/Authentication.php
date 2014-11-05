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

use Fiji\App\Model\User;
use Fiji\Factory;

/**
 * user authentication against default service
 */
class Authentication {

    protected $User;

    public function __construct(User $User)
    {
        $this->User = $User;
    }

    /**
     * Authenticate the User
     * @param String Username supplied by user
     * @param String Plain text password supplied by user
     */
    public function authenticate($username, $password)
    {
        $this->User->find(array('username' => $username));
        if ($this->User->id) {
            $hash = $this->getPasswordHash($password, $this->User->secret);
            if ($hash == $this->User->password) {
                return true;
            }
        }
        return false;
    }

    /**
     * Log the user out.
     * @return Bool log out status. TRUE For successfully logging user out. FALSE for error.
     */
    public function logout()
    {
       $this->User->isAuthenticated(false);
       return true;
    }

    /**
     * Retrieve password hash given password and secret
     * @param String Plain text password supplied by user
     * @param String Secret (password hash seed) for the user with associated username and password
     */
    public function getPasswordHash($password, $secret)
    {
        return sha1($password . $secret);
    }


}
