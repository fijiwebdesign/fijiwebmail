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

/**
 * Authentication against Email Server
 */
class Authentication extends \Fiji\App\Authentication
{
   
   public function __construct(\Fiji\App\User $User)
   {
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
        
        // populate user
        $this->User->username = $username;
        $this->User->name = substr($username, 0, strpos($username, '@'));
        $this->User->email = $username;
        $this->User->password = $password;
        $this->User->isAuthenticated(true);
        $this->User->imapOptions = $options;
        
        // persist user to session
        $this->User->persist();
        
        // find this user in local user via email or create it
        $this->User->find(array('email' => $username));
        if (!$this->User->id) {
            $this->User->save();
            if (!$this->User->id) {
                throw new \Exception('Could not save user');
            }
        }
        
        
        
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
