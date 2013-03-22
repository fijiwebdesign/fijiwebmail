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

use Fiji\Factory;

/**
 * Application control
 */
class Route {
    
    /**
     * @var Fiji\App\Request
     */
    public $Req;
    
    /**
     * @var Fiji\App\User
     */
    public $User;
    
    public function __construct() {
        $this->Req = Factory::getSingleton('Fiji\App\Request');
        $this->User = Factory::getSingleton('Fiji\App\User');
    }
    
    /**
     * Call the method of the controller
     */
    public function execControllerFunc($controller, $func)
    {
        // call the controllers correct method
        if (in_array($func, get_class_methods($controller))) {
            $Controller = Factory::getSingleton($controller);
            $Controller->$func();
        } else {
            throw new Exception('Controller does not have method.');
        }
    }
    
    /**
     * A URL to return to after performing a function
     */
    public function setReturnUrl($returnUrl)
    {
        $this->User->returnUrl = $returnUrl;
    }
    
    /**
     * Get the URL we want to redirect to
     */
    public function getReturnUrl()
    {
        return $this->User->returnUrl;
    }
    
    public function redirect($url)
    {
        header('Location: ' . $url);
        die;
    }
    
    
}
