<?php
/**
 * Test case for ZendLoaderTest
 * @author gabe@fijiwebdesign.com
 * @example Using PHPUnit in vendor/ 
 *            ./vendor/phpunit/phpunit/phpunit.php --verbose test/ZendLoaderTest.php
 *          Using PHPUnit installed globally
 *            phpunit --verbose test/ZendLoaderTest.php
 */

class ZendLoaderTest extends PHPUnit_Framework_TestCase
{
    
    /**
     * /../vendor/audoload.php loads Zend\Loader\AutoloaderFactory
     */
    public function testZendLoaderAutoloaderFactory()
    {
        require __DIR__ . '/../vendor/autoload.php';
        
        $this->assertTrue(class_exists('Zend\Loader\AutoloaderFactory'), 'Zend\Loader\AutoloaderFactory exists');
    }
    
    /**
     * /../vendor/audoload.php loads Zend\Session\Container
     */
    public function testZendLoaderSession()
    {
        require __DIR__ . '/../vendor/autoload.php';
        
        $Actual = new Zend\Session\Container();

        $this->assertInstanceOf('Zend\Session\Container', $Actual, 'Instance of Model');
    }
    
    /**
     * /../vendor/audoload.php loads Zend\Session\Container
     */
    public function testZendLoaderUsingFijiAutoloader()
    {
        require __DIR__ . '/../lib/Autoload.php';
        
        $Actual = new Zend\Session\Container();

        $this->assertInstanceOf('Zend\Session\Container', $Actual, 'Instance of Model');
    }

}