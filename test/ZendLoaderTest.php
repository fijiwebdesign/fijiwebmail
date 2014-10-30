<?php
/**
 * Test case for ZendLoaderTest
 * @author gabe@fijiwebdesign.com
 * @example phpunit --verbose test/ZendLoaderTest.php
 *          Do not use phpunit from composer as it depends on composer autoloader which also loads the tested classes giving false posiives. 
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
     * ../lib/Autoload.php loads Zend\Session\Container
     */
    public function testZendLoaderUsingFijiAutoloader()
    {
        require __DIR__ . '/../lib/Autoload.php';
        
        $Actual = new Zend\Session\Container();

        $this->assertInstanceOf('Zend\Session\Container', $Actual, 'Instance of Model');
    }

}