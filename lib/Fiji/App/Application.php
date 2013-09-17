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
	
	protected $name;
	
	protected $User;
    
    public function __construct(Controller $Controller = null) {
        
        $this->Controller = $Controller;
		
		// @todo is this a bug? or misunderstanding of PHP dynamic properties?
		unset($this->User); // allow dynamic getting of User
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
		if ($name == 'User') {
			return Factory::getUser();
		}
	}
	
}
