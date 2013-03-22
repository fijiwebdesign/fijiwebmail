<?php
/**
 * Fiji Mail Server 
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_Mail
 */

use Fiji\Factory;
use Fiji\App\Request;

// requests
$Request = Factory::getSingleton('Fiji\App\Request');

$func = $Request->getVar('func', 'loginForm');

// call the controllers correct method
if (in_array($func, get_class_methods('ControllerLogin'))) {
    $Controller = new ControllerLogin();
    $Controller->$func();
}

/**
 * Login Actions
 */
class ControllerLogin
{
    
    protected $User;
    
    protected $App;
    
    public function __construct()
    {
        $this->User = Factory::getSingleton('Fiji\App\User');
        $this->App = Factory::getSingleton('Fiji\App\Application');
        $this->Request = Factory::getSingleton('Fiji\App\Request');
    }
    
    public function loginForm()
    {
        $status = $this->Request->getVar('status', '');
        
        require( __DIR__ . '/view/login/form.php');
        die;
    }
    
    public function login()
    {
        // parameters
        $username = $this->Request->getVar('username', '');
        $password = $this->Request->getVar('password', '');
        
        // @todo implement calls to authentication modules
        $this->User->authenticate($username, $password);
        
        // hardcode mail authentication for now
        $options = array(
            'host'     => 'imap.gmail.com',
            'port'     => 993,
            'user'     => $username,
            'password' => $password,
            'ssl'      => 'ssl'
        );
        
        // try to login
        try {
            $Imap = Factory::getSingleton('Zend\Mail\Storage\Imap', array($options));
        } catch(Exception $e) {
            return $this->App->redirect('?app=auth&func=loginForm&status=fail');
        }
        
        // saving username to session
        $this->User->username = $username;
        $this->User->password = $password;
        $this->User->isAuthenticated(true);
        // save imap to session
        $this->User->imapOptions = $options;
        
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
