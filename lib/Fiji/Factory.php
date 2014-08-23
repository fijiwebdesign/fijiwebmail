<?php
/**
 * Fiji Mail Server 
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_Mail
 */

namespace Fiji;

use ReflectionClass;
use Exception;

class Factory
{
    
    static $instances = array();
   
   /**
    * Retrieve a single instance of a class. 
    * The class namespace and parameters are unique to each instance.
    * @param $className String Class Name
    * @param $params Array parameters to pass to class constructor
    */
   static function getSingleton($className, Array $params = array())
   {
        $classKey = $className . '(' . serialize($params) . ')';
        if (!isset(self::$instances[$classKey])) {
            self::$instances[$classKey] = self::createInstance($className, $params);
        }
        return self::$instances[$classKey];
   }
   
   /**
    * Create an instance of the class
    * @param $className String Class Name
    * @param $params Array parameters to pass to class constructor
    */
   static function createInstance($className, Array $params = array())
   {
        $refl = new ReflectionClass($className);
        return $refl->newInstanceArgs($params);
   }
   
   /**
    * Retrieve current user
    */
   static function getUser($className = 'User')
   {
   	   $className = self::translateClassName($className, 'Model');
       return self::getSingleton($className ? $className : 'Fiji\App\Model\User');
   }
   
   /**
    * Retrieve current user session
    */
   static function getUserSession()
   {
       return self::getSingleton('Zend_Session_Namespace', array('Fiji::User'));
   }
   
   /**
    * Create a model instance
    */
   static function createModel($className, Array $params = array())
   {
       return self::createInstance(self::translateClassName($className, 'Model'), $params);
   }
   
   /**
    * Create a model collection instance
    */
   static function createModelCollection($className)
   {
       return self::createInstance('Fiji\\App\\ModelCollection', 
           array(is_object($className) ? $className : self::createModel($className)));
   }
   
   /**
    * Create a View Singleton
    */
   static public function getView($className, App\Application $App = null)
   {
       return self::getSingleton($className, array($App));
   }
   
   /**
    * Create an Application Singleton
    */
   static public function getApplication(App\Controller $Controller = null)
   {
       return self::getSingleton('Fiji\\App\\Application', array($Controller));
   }
   
   /**
    * Create a Document Singleton
    */
   static public function getDocument()
   {
       return self::getSingleton('Fiji\\App\\Document');
   }
   
   /**
    * Retrieve a Fiji\App\Service Instance
    */
   static public function getService(Service\DataProvider $DataProvider = null)
   {
       if (!$DataProvider) {
           $DataProvider = self::getDataProvider();
       }
       
       return self::getSingleton('Fiji\\Service\\Service', array($DataProvider));
   }
   
   /**
    * Retrieve a Fiji\App\DataProvider Instance
    */
   static public function getDataProvider(App\Config $Config = null)
   {
       // default provider
       if (!$Config) {
           $Config = Factory::getSingleton('config\\Service');
       }
       $dataProvider = $Config->get('dataProvider');
       
       return self::getSingleton($dataProvider, array($Config));
   }
   
   /**
    * Retrieve the Application request
    */
   static public function getRequest(Array $requestData = null)
   {
       return Factory::getSingleton('Fiji\App\Request', array($requestData));
   }
   
   /**
    * Retrieve the Configuration
    */
   static public function getConfig($className = null, $options = array())
   {
       if ($className) {
           return Factory::createInstance($className, array($options));
       }
       return Factory::getSingleton('config\\App', array($options));
   }

   /**
    * Retrieve the Authentication
    */
   static public function getAuthentication($className = null, $options = array())
   {
      $Config = self::getConfig();
      $User = self::getUser();
      $AuthenticationClass = $Config->get('Authentication');
      return self::getSingleton($AuthenticationClass, array($User));
   }
   
   /**
    * Translates classNames to intended class in a cascading fashion
    * Each class has translations configured in config\{classParent}. 
    * eg: config\Model or config\User or config\Widget
    */
   static public function translateClassName($className, $classParent)
   {
   		if (class_exists($className)) {
       		return $className;
        }
   		$Config = self::getConfig('config\\' . $classParent);
   		$appName = self::getApplication()->getName();
		
  		// get specific translation for this class or the default set of translations
  		$classNames = $Config->get($className, $Config->get('defaultClass'));
  		
  		if (!is_array($classNames) && !is_object($classNames)) {
  		    $classNames = array($classNames);
  		}
  		// try each class path for existence of model class
  		foreach($classNames as $_className) {
  			$_className = str_replace(array('{App}', '{' . $classParent . '}'), array($appName, $className), $_className);
  			if (class_exists($_className)) {
  				$className = $_className;
  				break;
  			}
  		}
  		
  		return $className;
   }
   
   /**
    * Retrieve widget
    */
   static function getWidget($className = null, $params = array())
   {
   	   if (!$className) {
   	   	    throw new Exception('Widget name must be defined in parameters.');
   	   }
       return self::getSingleton(self::translateClassName($className, 'Widget'), $params);
   }
   
}

