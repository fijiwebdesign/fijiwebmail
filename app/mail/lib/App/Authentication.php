<?php

namespace app\mail\lib\App;

/**
 * Fiji Cloud EMail
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_App
 */

use Fiji\Factory;
use Fiji\App\Model\User;
use Fiji\App\Authentication as AuthenticationInterface;
use Exception;

use Zend\Loader\StandardAutoloader;
use Zend\Session;
use Zend\Session\Config\SessionConfig;
use Zend\Session\SessionManager;
use Zend\Session\Container;

/**
 * Authentication against Email Server
 */
class Authentication extends AuthenticationInterface
{
   
   public function __construct(User $User)
   {

        // zend session options
        $options = array(
            'remember_me_seconds' => 2419200,
            'use_cookies' => true,
            'cookie_httponly' => true
        );

        // initialize zend session
        // @todo Session should be moved to a service
        $config = new SessionConfig();
        $config->setOptions($options);
        $manager = new SessionManager($config);
        Container::setDefaultManager($manager);

        parent::__construct($User);
   }
    
   public function authenticate($username, $password)
   {
	   
       $Config = Factory::getConfig('config\\Mail');
       
       $authServer = $Config->get('authServer');
       
       // hardcode mail authentication for now
        $options = array(
            'host'     => $authServer->get('host'),
            'port'     => $authServer->get('port'),
            'user'     => $username,
            'password' => $password,
            'ssl'      => $authServer->get('ssl')
        );
        
        // try to login
        try {
            $Imap = Factory::getSingleton('Zend\Mail\Storage\Imap', array($options));
        } catch(\Zend\Mail\Storage\Exception\RuntimeException $e) {
            return false;
        } catch(Exception $e) {
            return false;
        }

        // find user
        $this->User->find(array('email' => $username));
        
        // populate user with new data
        if (!$this->User->getId()) {
            $this->User->username = $username;
            $this->User->name = substr($username, 0, strpos($username, '@'));
            $this->User->email = $username;
            
        }

        $this->User->password = $password; // User model handles hashing
        $this->User->isAuthenticated(true);
        $this->User->imapOptions = $options;
        
        // find this user in local user via email or create it
        $this->User->save();
		
		    // persist user to session
        $this->User->persist();
        
        return true;
   }
   
   public function logout()
   {
       $this->User->username = null;
       $this->User->email = null;
       $this->User->password = null;
       $this->User->imapOptions = null;
       $this->User->isAuthenticated(false);
   }

}
