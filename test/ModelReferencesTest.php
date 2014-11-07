<?php
/**
 * Test case for Fiji\App\Model references
 * @author gabe@fijiwebdesign.com\
 * @example Using PHPUnit in vendor/
 *          php ./vendor/phpunit/phpunit/phpunit.php --verbose test/ModelReferencesTest.php
 *          Using PHPUnit installed globally
 *            phpunit --verbose test/ModelReferencesTest.php
 */

// mocks Factory, Service and Models
require_once __DIR__ . '/bootstrap/Autoload.php';

use Fiji\Factory;

/**
 * Test the References are being lazy loaded and work correctly when __isset(), __get(), __set()
 * @group model
 */
class ModelReferencesTest extends PHPUnit_Framework_TestCase
{

    /**
     * Clean up the storage
     */
    public function setUp()
    {
        $Service = new config\Service;
        $Service->clearStorage();
    }

    /**
     * Clean up the storage
     */
    public function tearDown()
    {
        $Service = new config\Service;
        //$Service->clearStorage();
    }

    /**
     * The reference is an instance of className2 lazy loaded with __get('RefName')
     * @dataProvider provider
     */
    public function testReference__get($className1, $className2)
    {
        $Model1 = Factory::createModel($className1);
        $Model2 = Factory::createModel($className2);

        // ModelMock2 is lazy loaded as an instance of $Model1->References[ModelMock2] = ModelMock2
        $this->assertInstanceOf('ModelMock2', $Model1->ModelMock2, 'Instance of ModelMock2');
        // ModelMock1 is lazy loaded as an instance of $Model2->References[ref1] = ModelMock1
        $this->assertInstanceOf('ModelMock1', $Model2->ref1, 'Instance of ModelMock1');
    }

    /**
     * The references given the correct behaviour with isset($Model->RefName)
     * @dataProvider provider
     */
    public function testReference__isset($className1, $className2)
    {
        $Model1 = Factory::createModel($className1);
        $Model2 = Factory::createModel($className2);

        // Any References should be set
        $this->assertTrue(isset($Model1->ModelMock2));
        $this->assertTrue(isset($Model2->ref1));
        // not a reference
        $this->assertFalse(isset($Model2->invalidReferenceName));
    }

    /**
     * Only allow setting of Fiji\App\Model or Fiji\App\ModelCollection as References
     * @dataProvider provider
     * @throws Exception
     * @expectedException Exception
     */
    public function testReference__setException($className1, $className2)
    {
        $Model1 = Factory::createModel($className1);
        $Model2 = Factory::createModel($className2);

        // Do not allow invalid type setting
        $Model2->ref1 = null; // throws exception
    }

    /**
     * Only allow setting of Fiji\App\Model or Fiji\App\ModelCollection as References
     * @dataProvider provider
     */
    public function testReference__set($className1, $className2)
    {
        $Model1 = Factory::createModel($className1);
        $Model2 = Factory::createModel($className2);

        // allow setting References from outside
        $Model1->ModelMock2 = $Model2;
        // assert they are equals
        $this->assertEquals($Model1->ModelMock2, $Model2);

    }

    /**
     * Only allow setting of Fiji\App\Model or Fiji\App\ModelCollection as References
     * @dataProvider provider
     */
    public function testReference__unset($className1, $className2)
    {
        $Model1 = Factory::createModel($className1);
        $Model2 = Factory::createModel($className2);

        // we have the reference in index
        $this->assertTrue(isset($Model1->References['ModelMock2']));

        unset($Model1->ModelMock2);

        // allow setting References from outside
        $Model1->ModelMock2 = $Model2;

        unset($Model1->ModelMock2);

        // assert ref was removed
        $this->assertEquals(null, $Model1->ModelMock2);
        $this->assertFalse(isset($Model1->ModelMock2));

        // assert the reference was removed from index
        $this->assertFalse(isset($Model1->References['ModelMock2']));

    }

    /**
     * Test lazy loading a Reference
     * @dataProvider provider
     */
    public function testReferenceLazyload($className1, $className2)
    {
        $Model1 = Factory::createModel($className1);
        $Model2 = Factory::createModel($className2);

        // setting a reference property should lazy load reference and set property
        $Model1->ModelMock2->public = 'test1';
        $this->assertEquals($Model1->ModelMock2->public, 'test1');
    }

    /**
     * Test assigning a Reference
     * @dataProvider provider
     */
    public function testReferenceAssign($className1, $className2)
    {
        $Model1 = Factory::createModel($className1);
        $Model3 = $Model1->ModelMock2; // assign reference to variable

        // assignment does not copy
        $Model3->public = 'test1';
        $this->assertEquals($Model1->ModelMock2->public, 'test1');

    }

    /**
     * Test Saving a Reference
     * @dataProvider provider
     */
    public function testReferencePersist($className1, $className2)
    {
        $Model1 = Factory::createModel($className1);
        $Model2 = Factory::createModel($className2);

        $ModelMock2 = $Model1->ModelMock2; // for simplicity

        // Model1 needs data to save
        $Model1->public = 'test1';

        // set the public property
        $ModelMock2->public = 'test2';
        // save $Model1 to save the reference as well
        $Model1->save(); // should save our reference

        // test that it was saved
        $Model3 = Factory::createModel($className1)
            ->findById($Model1->id);
        $ModelMock3 = $Model3->ModelMock2; // trigger the lazy load of ref

        $this->assertEquals($Model1, $Model3);

        // assert same storage namespace
        $this->assertEquals($ModelMock2->getObjectName(), $Model2->getObjectName());

        // assert $Model2 is NOT the same data $ModelMock2 (control)
        $this->assertNotEquals($ModelMock2->public, $Model2->public);

        // find the same Model in storage
        $Model2->find(array('public' => 'test2'));

        // assert $Model2 is the same data as $ModelMock2
        $this->assertEquals($ModelMock2->public, $Model2->public);

        // find non-existent model in storage
        // @note we shouldn't use the same model instance for two different datasets in practice. 
        //  Though it should work.
        $Model2->find(array('public' => '__none__'));

        // assert $Model2 is NOT the same data as $ModelMock2
        $this->assertNotEquals($ModelMock2->public, $Model2->public);
    }

    /**
     * Test Saving a Reference Collection
     * @todo implement completely
     * @dataProvider provider
     */
    public function testReferenceCollectionPersist($className1, $className2)
    {
        $Model1 = Factory::createModel('ModelMock3');

        // Model1 needs data to save
        $Model1->public = 'testReferenceCollectionPersist';
        $RefCollection = $Model1->RefCollection; // for simplicity

        // add to the referece collection
        $RefCollection[] = Factory::createModel($className1)
            ->findById(1);
        $RefCollection[] = Factory::createModel($className1)
            ->findById(2);

        $Model1->RefCollection2[] = Factory::createModel($className2)
            ->findById(1);

        // save $Model1 to save the reference as well
        $Model1->save(); // should save our reference

        // retrieve the same $Model1 with references
        $Model2 = Factory::createModel('ModelMock3')
            ->find(array('public' => 'testReferenceCollectionPersist'));

        // assert the references were saved and retrieved
        // @note we cannot use direct compare since Model2->RefCollection hasn't lazy loaded the DataProvider
        //$this->assertEquals($Model1->RefCollection, $Model2->RefCollection); // @todo fix?
        $this->assertEquals(count($Model1->RefCollection), 2);

        $this->assertEquals(count($Model1->RefCollection2), 1);

    }

    public function provider()
    {
        return array(
            array('ModelMock1', 'ModelMock2')
        );
    }
}
