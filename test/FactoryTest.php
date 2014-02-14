<?php
/**
 * Test case for Fiji\Factory
 * @author gabe@fijiwebdesign.com
 * @example Using PHPUnit in vendor/ 
 *            ./vendor/phpunit/phpunit/phpunit.php --verbose test/FactoryTest.php
 *          Using PHPUnit installed globally
 *            phpunit --verbose test/FactoryTest.php
 */

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../lib/Autoload.php';

use Fiji\Factory;

class FactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * Singletons should be retrieved for each class
     * @dataProvider provider
     */
    public function testGetSingleton($className, Array $params = array())
    {
        $Ref = new ReflectionClass($className);
        $Expected = $Ref->newInstanceArgs($params);
        $Actual = Factory::getSingleton($className, $params);

        $this->assertEquals($Expected, $Actual);
    }

    /**
     * Class names should be translated according to Configurations
     * @example Model will use config\Model::$defaultClass
     * @example User will use config\Model::$User
     * @dataProvider providerUntranslated
     */
    public function testTranslateClassName($className, Array $params = array(), $expectedClassName = null)
    {
        $Actual = Factory::translateClassName($className, 'Model');

        $this->assertEquals($expectedClassName, $Actual);
    }

    public function provider()
    {
        // mock user so we don't invoke Session
        $User = $this->getMockBuilder('Fiji\App\Model\User')
            ->disableOriginalConstructor()
            ->getMock();

        return array(
          array('Fiji\App\Application'),
          array('Fiji\App\Authentication', array($User)),
          array('Fiji\App\Config'),
          //array('Fiji\App\Controller'), // abstract
          array('Fiji\App\Document'),
          array('Fiji\App\Exception'),
          //array('Fiji\App\Model'), // abstract
          array('Fiji\App\ModelCollection', array($User)),
          array('Fiji\App\Request'),
          array('Fiji\App\Route'),
          array('Fiji\App\Uri'),
          array('Fiji\App\View'),
          //array('Fiji\App\Widget'), // abstract
          array('Fiji\App\Model\User'),
          array('Fiji\App\Model\Widget')
        );
    }

    public function providerUntranslated()
    {
        $Config = Factory::getConfig('config\Model');
        $appName = Factory::getApplication()->getName();
        
        // get translations for User models in Model config
        $classNames = $Config->get('User');
        
        if (!is_array($classNames) && !is_object($classNames)) {
            $classNames = array($classNames);
        }
        // try each class path for existence of model class
        $className = 'User';
        foreach($classNames as $_className) {
            $_className = str_replace(array('{App}', '{Model}'), array($appName, $className), $_className);
            if (class_exists($_className)) {
                $className = $_className;
                break;
            }
        }

        return array(
          array('User', array(), $className)
        );
    }
}