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
 * user authentication against default service
 */
class Authentication {
    
    protected $User;
    
    public function __construct(\Fiji\App\User $User)
    {
        $this->User = $User;
    }

    /**
     * Authenticate the User
     */
    public function authenticate($username, $password)
    {
        // @todo Implement event/observer pattern authentication
        $this->User->find(array('username' => $username, 'password' => $password));
        return $this->User->isAuthenticated($this->User->getId());
    }
    
    public function logout()
   {
       $this->User->isAuthenticated(false);
   }
    
    
}
