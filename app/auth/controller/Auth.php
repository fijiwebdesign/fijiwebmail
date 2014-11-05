<?php

namespace app\controller;

/**
 * Fiji Mail Server
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_App
 */

use Fiji\Factory;

/**
 * Generic Auth exception
 */
class AuthException extends \Exception {}

/**
 * Login Actions
 */
class Auth extends \Fiji\App\Controller
{
    
    protected $User;
    
    protected $App;
    
    public function __construct(\Fiji\App\View $View = null)
    {
        $this->User = Factory::getUser();
        $this->App = Factory::getApplication();
        $this->Request = Factory::getRequest();
        $this->Config = Factory::getConfig();

        parent::__construct($View);
    }
    
    public function index()
    {
        $status = $this->Request->getVar('status', '');
        
        require( __DIR__ . '/../view/login/form.php');
        
    }
    
    public function login()
    {
        // parameters
        $username = $this->Request->getVar('username', '');
        $password = $this->Request->getVar('password', '');
        
        // authenticate the user
        if (!$this->User->authenticate($username, $password)) {
            return $this->App->redirect('?app=auth&status=fail');
        }
        
        // redirect to app that called the login module or home
        $this->App->redirect($this->App->getReturnUrl('?'));
    }
    
    public function logout()
    {
        $this->User->logout();
        // redirect to app that called the login module
        $this->App->redirect($this->App->getReturnUrl('?'));
    }
    
}
