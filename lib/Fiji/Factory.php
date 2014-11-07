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
    * Retrieve the User Session
    * @todo Create a Interface
    */
   static function getUserSession($expiresSecs = 3600)
   {
      return self::getSession('user', $expiresSecs);
   }

   /**
    * Retrieve the Session
    * @todo Create a Interface
    */
   static function getSession($namespace = 'app', $expiresSecs = 3600)
   {
      $Session = Factory::getSingleton('Zend\Session\Container', array($namespace));
      $Session->setExpirationSeconds($expiresSecs);
      return $Session;
   }

   /**
    * Create a model instance
    * @return Fiji\App\Model
    */
   static function createModel($className, Array $params = array())
   {
       return self::createInstance(self::translateClassName($className, 'Model'), $params);
   }

   /**
    * Create a model collection instance
    * @return Fiji\App\ModelCollection
    */
   static function createModelCollection($className, $collectionClassName = null)
   {
       return self::createInstance(($collectionClassName ? $collectionClassName : 'Fiji\\App\\ModelCollection'),
           array($className));
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
   static public function getConfig($className = 'config\\App', $options = array())
   {
        static $Configs = array();

        $Config = Factory::createModel($className, array($options));

        if (!isset($Configs[$className])) {
            // load saved configuration
            //$Config->sort(array('id' => 'DESC'))->find();
            $Configs[$className] = $Config;
        }
        return $Configs[$className];
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
    * @todo simplify, modularize. Should have a setTranslation(). This is too dependent on config internals
    */
   static public function translateClassName($className, $classParent)
   {
           if (class_exists($className)) {
               return $className;
        }
           $Config = self::getConfig('config\\Factory');
           $appName = self::getApplication()->getName();

          // get specific translation for this class or the default set of translations

          $classNames = $Config->get($className, $Config->get(str_replace('\\', '_', $classParent) . '_' . $className, array()));

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
   static function getWidget($className, $params = array())
   {
       return self::getSingleton(self::translateClassName($className, 'Widget'), $params);
   }

   /**
    * Create a widget
    */
   static function createWidget($className, $params = array())
   {
       return self::createInstance(self::translateClassName($className, 'Widget'), $params);
   }

   /**
    * Retrieve the permissions
    */
   static function getPermissions($resource, $className = 'Fiji\App\AccessControl\Model\Permissions')
   {
      return self::createModel($className)->find(array('resource' => $resource));
   }

   /**
    * Retrieve the access control instance for a resource
    * @param String Resource namespace
    * @param Model User requesting access
    * @param String Access Control implementation class name
    */
   static function getAccessControl($resource, Model $User = null, $className = 'Fiji\App\AccessControl\RoleBasedAccessControl')
   {
      $User = isset($User) ? $User : self::getUser();
      $Perms = self::getPermissions($resource);
      return self::createInstance($className, array($User, $Perms));
   }

}
