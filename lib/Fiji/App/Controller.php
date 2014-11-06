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
    /**
     * @var {Fiji\App\Model\User}
     */
    protected $User;

    /**
     * @var {Fiji\App\Application}
     */
    protected $App;

    /**
     * @var {Fiji\App\View}
     */
    protected $View;

    /**
     * @var {Fiji\App\Request}
     */
    protected $Req;

    /**
     * @var {Fiji\App\Document}
     */
    protected $Doc;

    /**
     * @var {Fiji\App\AccessControl\AccessControl}
     */
    protected $AccessControl;
    
    public function __construct(View $View = null, $execute = true)
    {
        // @todo lazy load these
        $this->User = Factory::getUser();
        $this->App = Factory::getApplication($this);
        $this->View = $View ? 
            $View : Factory::getView('Fiji\App\View', $this->App);
        $this->Req = Factory::getRequest();
        $this->Doc = Factory::getDocument();
        $this->AccessControl = Factory::getAccessControl(get_class($this));
        
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
    protected function execute($method)
    {
        if ($this->onExecute($method) === false) {
            return false;
        }
        if (in_array($method, get_class_methods($this))) {
            try {
                $this->$method();
            } catch(Exception $e) {
                // @todo handle exceptions app level
                die($e->getMessage());
            }
        } else {
            var_dump($method);
            var_dump(get_class_methods($this));
            throw new controllerException('Method doesn\'t exist!');
        }
    }

    /**
     * Event triggered when executing a method. Return false to prevent execution.
     * @param String Controller method
     * @return Bool False will stop execution
     */
    protected function onExecute($method)
    {
        return true;
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
