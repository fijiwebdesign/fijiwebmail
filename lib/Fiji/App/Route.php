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
use Fiji\App\Config;

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
    
    public function __construct(Config $Config = null) {
        $this->Req = Factory::getSingleton('Fiji\App\Request');
        $this->User = Factory::getSingleton('Fiji\App\User');
        $this->Config = $Config ? $Config : Factory::getConfig();
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
    
    public function setController()
    {
        $this->Controller = $Controller;
    }
    
    public function getController()
    {
        if (!isset($this->Controller)) {
            $this->Controller = Factory::getController();
        }
        return $this->Controller;
    }
    
    /**
     * Translate the SEF URL to request parameters
     * @param String SEF URL
     */
    public function route($url)
    {
        $parts = explode('/', $url);
        
        $app = isset($parts[0]) ? $parts[0] : null;
        $controller = isset($parts[1]) ? $parts[1] : null;
        $method = isset($parts[2]) ? $parts[2] : null;
        
        $this->Request->set('app', $app);
        $this->Request->set('page', $controller);
        $this->Request->set('view', $method);
        
    }
    
}
