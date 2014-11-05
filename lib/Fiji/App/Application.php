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
class Application {
	
    /**
     * @var String Application Name
     */
	protected $name;
	
    /**
     * @var Fiji\App\Model\User Current User
     */
	protected $User;

    /**
     * @var Fiji\App\Request HTTP or CLI Request
     */
    protected $Req;

    /**
     * @var Array Properties to lazy load
     */
    protected $LazyProperties;
    
    public function __construct(Controller $Controller = null)
    {
        
        $this->Controller = $Controller;

        // lazy load these properties
        $this->lazyLoad('User', array($this, 'getUser'));
        $this->lazyLoad('Req', array($this, 'getRequest'));
    }

    /**
     * Get the User
     */
    public function getUser()
    {
        return $this->User = Factory::getUser();
    }

    /**
     * Get the Request
     */
    public function getRequest()
    {
        return $this->Req = Factory::getRequest();
    }

    /**
     * Makes the given property lazy loaded with the given callable
     * @param String Property name
     * @param Callable Function to lazy load $this->$name
     */
    public function lazyLoad($name, $callable = false)
    {
        if ($callable) {
            unset($this->$name); // allows __get() to be triggered
            $this->LazyProperties[$name] = $callable;
        } elseif (isset($this->LazyProperties[$name])) {
            return call_user_func($this->LazyProperties[$name]);
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
    public function getReturnUrl($default = '')
    {
        $return = $this->User->returnUrl ? $this->User->returnUrl : $default;
        $this->User->returnUrl = null;
        return $return;
    }
    
    public function redirect($url, $msg = null)
    {
        if ($msg) {
            $this->User->addNotification($msg);
        }

        // if this is an html ajax call or app template then use js redirect
        $siteTemplate = $this->Req->getVar('siteTemplate');
        $contentType = $this->Req->getVar('contentType', 'html');
        if ($siteTemplate == 'app' || ($siteTemplate == 'ajax' && $contentType == 'html')) {
             echo '<script>location="' . $url . '";</script>';
             die;
        }
        
        header('Location: ' . $url);
        die;
    }
    
    public function getPathBase()
    {
        return dirname(dirname(dirname(__DIR__)));
    }
    
    /**
	 * Return the application name
	 */
	public function getName()
	{
		if (!isset($this->name)) {
			$this->name = Factory::getRequest()->get('app', Factory::getConfig()->get('defaultApp'));
		}
		return $this->name;
	}
    
	/**
	 * Return application path
	 */
    public function getPath()
    {
        return $this->getPathBase() . '/app/' . $this->getName();
    }
    
	public function __get($name)
	{
		return $this->lazyLoad($name);
	}
	
}
