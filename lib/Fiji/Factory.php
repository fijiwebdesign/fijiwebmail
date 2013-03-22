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
    * Retrieve a single instance of a class through the application (current php process)
    * @param $className String Class Name
    * @param $params Array parameters to pass to class constructor
    */
   static function getSingleton($className, $params = array())
   {
        $classKey = $className . '(' . serialize($params) . ')';
        if (!isset(self::$instances[$classKey])) {
            $refl = new \ReflectionClass($className);
            self::$instances[$classKey] = $refl->newInstanceArgs($params);
        }
        return self::$instances[$classKey];
   }
   
   /**
    * Retrieve current user session
    */
   static function getUserSession()
   {
       return self::getSingleton('Zend_Session_Namespace', array('Mail::userSession'));
   }
   
}


