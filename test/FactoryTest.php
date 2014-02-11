<?php
/**
 * Test case for Fiji\Factory
 * @author gabe@fijiwebdesign.com
 * @example Using PHPUnit in vendor/ 
 *            ./vendor/phpunit/phpunit/phpunit.php --verbose test/Factory.php
 *          Using PHPUnit installed globally
 *            phpunit --verbose test/Factory.php
 */

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../lib/autoload.php';

use Fiji\Factory;

use Fiji\App\Model;
use Fiji\App\Session;
use Fiji\App\User;

class MathNotationTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provider
     */
    public function testGetSingleton($className, Array $params = array())
    {
        $Expected = new $classname();
        $Actual = Factory::testGetSingleton($className);

        $this->assertEquals($Expected, $Actual);
    }

    public function provider()
    {
        return array(
          array('Fiji\App\User'),
          array('Fiji\App\Session'),
          array('Fiji\App\Model')
        );
    }
}