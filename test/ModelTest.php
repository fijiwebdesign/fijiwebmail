<?php
/**
 * Test case for Fiji\App\Model
 * @author gabe@fijiwebdesign.com
 * @example Using PHPUnit in vendor/ 
 *            ./vendor/phpunit/phpunit/phpunit.php --verbose test/ModelTest.php
 *          Using PHPUnit installed globally
 *            phpunit --verbose test/ModelTest.php
 */

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../lib/Autoload.php';

use Fiji\Factory;
use Fiji\App\Model;

class ModelTest extends PHPUnit_Framework_TestCase
{
    /**
     * Model inherits from Fiji\Service\DomainObject
     * @dataProvider provider
     */
    public function testInheritence($className, Array $params = array())
    {
        $Expected = new $className();
        $Actual = Factory::createModel($className);

        $this->assertEquals($Expected, $Actual);
        $this->assertInstanceOf('Fiji\App\Model', $Actual, 'Instance of Model');
        $this->assertInstanceOf('Fiji\Service\DomainObject', $Actual, 'Instance of DomainObject');

    }

    /**
     * Model methods __set() and __get() expose protected properties allowing them to be set and get. 
     * @dataProvider provider
     */
    public function testGettersAndSetters($className, Array $params = array())
    {
        $Actual = Factory::createModel($className);

        $Actual->public = 'public';
        $Actual->private = 'private';
        $Actual->protected = 'protected';

        $this->assertEquals('public', $Actual->public);
        $this->assertNull($Actual->private); // should not be set
        $this->assertEquals('protected', $Actual->protected); // accessed through __set() and __get()

    }

    /**
     * Model::toArray() returns an Array of public properties of Model
     * @dataProvider provider
     */
    public function testToArray($className, Array $params = array())
    {
        $Model= Factory::createModel($className);

        $Model->public = 'public';
        $Model->private = 'private';
        $Model->protected = 'protected';

        $Actual = $Model->toArray();

        $Expected = array(
            'id' => null,
            'public' => 'public'
        );

        $this->assertEquals($Expected, $Actual);

    }

    /**
     * Model::set{Property}() method is called when protected {property} is set
     * @dataProvider provider
     */
    public function testSetterCall($className, Array $params = array())
    {

        $Model = $this->getMock($className, array('setProtected'));
        $Model->expects($this->once())
             ->method('setProtected')
             ->with($this->equalTo('protected'));

        $Model->public = 'public';
        $Model->private = 'private';
        $Model->protected = 'protected'; // triggers call $Model->setProtected('protected')

    }

    /**
     * Model::getKeys() method controls keys used when converting Model to Array
     * @dataProvider providerGetKeys
     */
    public function testToArrayGetKeys($className, Array $params = array())
    {

        $Model= Factory::createModel($className);

        $Model->public = 'public';
        $Model->private = 'private';
        $Model->protected = 'protected';

        $Actual = $Model->toArray();

        $Expected = array(
            'id' => null,
            'public' => 'public',
            'protected' => 'protected' // from $Model->getKeys()
        );

        $this->assertEquals($Expected, $Actual);

    }

    /**
     * Model::getKeys() method controls keys used when converting Model to Array
     * A setter and getter will provide the value for a key
     */
    public function testToArrayGetKeysSetter()
    {

        $Model= Factory::createModel('ModelMockGetKeysSetter');

        $Model->public = 'public';
        $Model->private = 'private';
        $Model->protected = 'protected';

        $Actual = $Model->toArray();

        $Expected = array(
            'id' => null,
            'public' => 'public',
            'protected' => sha1('protected') // from $Model->getKeys()
        );

        $this->assertEquals($Expected, $Actual);

        foreach($Model as $name => $value) {
             $this->assertEquals($Expected[$name], $Model->$name);
        }

    }

    public function provider()
    {
        return array(
            array('ModelMock')
        );
    }

    public function providerGetKeys()
    {
        return array(
            array('ModelMockGetKeys')
        );
    }
}

/**
 * Model Mockup
 */
class ModelMock extends Model {
    
    /**
     * @var private
     */
    private $private;

    protected $protected;

    public $public;

}

/**
 * Model Mockup wth a getter for protected method
 */
class ModelMockGetKeys extends ModelMock {

    /**
     * Return keys we want to save to database
     */
    public function getKeys()
    {
        return array('id', 'public', 'protected');
    }

}

/**
 * Model Mockup wth a getter for protected method with setter
 */
class ModelMockGetKeysSetter extends ModelMockGetKeys {

    /**
     * Get $this->protected
     */
    public function getProtected()
    {
        return $this->protected;
    }

    /**
     * Set $this->protected 
     */
    public function setProtected($value)
    {
        $this->protected = sha1($value);
    }

}