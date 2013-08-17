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
        $refl = new \ReflectionClass($className);
        return $refl->newInstanceArgs($params);
   }
   
   /**
    * Retrieve current user
    */
   static function getUser($className = null)
   {
       return self::getSingleton($className ? $className : 'Fiji\\App\\User');
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
       return self::createInstance($className, $params);
   }
   
   /**
    * Create a model collection instance
    */
   static function createModelCollection($className)
   {
       return self::createInstance('Fiji\\App\\ModelCollection', 
           array(self::createModel($className)));
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
   
}


