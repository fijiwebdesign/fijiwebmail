<?php

namespace Fiji\App;

/**
 * Controller
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_App
 */
 
use Fiji\Factory;
use ReflectionClass, ReflectionMethod;

/**
 * Default Controller
 */
abstract class Controller
{
    
    protected $User;
    protected $App;
    protected $View;
    protected $Req;
    protected $Doc;
    
    public function __construct(View $View = null, $execute = true)
    {        
        $this->User = Factory::getUser();
        $this->App = Factory::getApplication($this);
        $this->View = $View ? 
            $View : Factory::getView('Fiji\App\View', $this->App);
        $this->Req = Factory::getRequest();
        $this->Doc = Factory::getDocument();
        
        // requests
        $view = $this->Req->getAlphaNum('view', 'index', 'trim');
        
        // call the controllers correct method
        if ($execute) {
            $this->execute($view);
        }
    }
    
    /**
     * Execute the method of controller
     */
    public function execute($method)
    {
        if (in_array($method, get_class_methods($this))) {
            try {
                $this->$method();
            } catch(Exception $e) {
                // @todo handle exceptions app level
                die($e->getMessage());
            }
        } else {
            throw new controllerException('Method doesn\'t exist!');
        }
    }
    
    /**
     * Set the View
     */
    public function setView(View $View)
    {
        $this->View = $View;
    }
    
    /**
     * Return the view
     */
    protected function getView($name = null)
    {
        return $this->View;
    }
    
    /**
     * Allow applications to discover methods of this controller
     * @todo HTML should be in View (formatting in JSON etc.)
     */
    public function discover()
    {
        $ref = new ReflectionClass($this);
        $refFuncs = $ref->getMethods(ReflectionMethod::IS_PUBLIC);
        
        $app = $this->getName();
        
        echo '<h2>' . ucfirst($app) . '</h2>';
        
        echo '<ul>';
        foreach($refFuncs as $refFunc) {
            if ($refFunc->class == get_class($this) && $refFunc->name != '__construct') {
                echo '<li><a href="?app=' . $app . '&view=' . $refFunc->name . '">';
                echo $refFunc->name;
                echo '</a></li>';
            }
        }
        echo '</ul>';
            
    }
    
    /**
     * @todo We need to instantiate a custom Application class extending Fiji\App\Application
     * @todo make Fiji\App\Application abstract
     * @todo We should not get app specific data from Request in the Application or Controller
     */
    public function getName()
    {
        $default = Factory::getSingleton('config\\App')->get('defaultApp');
        return $this->Req->getAlphaNum('app', $default);
    }

}

class controllerException extends \Exception {}
