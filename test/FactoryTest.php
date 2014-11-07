<?php
/**
 * Test case for Fiji\Factory
 * @author gabe@fijiwebdesign.com
 * @example Using PHPUnit in vendor/
 *          php  ./vendor/phpunit/phpunit/phpunit.php --verbose test/FactoryTest.php
 *          Using PHPUnit installed globally
 *            phpunit --verbose test/FactoryTest.php
 */

require_once __DIR__ . '/../lib/Autoload.php';

// mocks Factory, Service and Models
require_once __DIR__ . '/bootstrap/Mocks.php';

use Fiji\Factory;

/**
 * @todo Mock configuration. Currently dependent on config\Factory
 */
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

    /**
     * @todo Mock configuration. Currently dependent on config\Factory
     */
    public function providerUntranslated()
    {
        return array(
          array('User', array(), 'app\mail\model\User')
        );
    }
}
