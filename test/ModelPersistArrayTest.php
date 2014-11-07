<?php
/**
 * Test case for Fiji\App\Model Array persistence
 * @author gabe@fijiwebdesign.com\
 * @example Using PHPUnit in vendor/
 *          php ./vendor/phpunit/phpunit/phpunit.php --verbose test/ModelPersistArrayTest.php
 *          Using PHPUnit installed globally
 *            phpunit --verbose test/ModelPersistArrayTest.php
 */

// mocks Factory, Service and Models
require_once __DIR__ . '/bootstrap/Autoload.php';

use Fiji\Factory;

/**
 * Test the Model Persistence of Arrays properties
 * @group model
 */
class ModelPersistArrayTest extends PHPUnit_Framework_TestCase
{

    /**
     * Test saving and retrieving Array property
     * @dataProvider provider
     */
    public function testSave($className1, $className2)
    {
        $Model1 = Factory::createModel($className1);
        $Model2 = Factory::createModel($className2);

        // array data to save
        $arr = array(
            'view'  => array('everyone'),
            'add'   => array('registered'),
            'edit'  => array('owner', 'moderator', 'admin'),
            'delete'=> array('admin')
        );

        // Model1 needs data to save
        $Model1->public = $arr;

        // save $Model1 to save the reference as well
        $Model1->save(); // should save our reference

        // test that it was saved
        $Models[] = Factory::createModel($className1)
            ->findById($Model1->id);
        $Model3 = Factory::createModel($className1)
            ->find(array('id' => $Model1->id));

        foreach($Models as $Model) {

            // remove the ref to compensate lazy loading difference
            unset($Model1->ModelMock2);
            unset($Model->ModelMock2);

            // assert the models are the same
            $this->assertEquals($Model1, $Model);

            // ensure array was persisted
            $this->assertEquals($arr, $Model->public);
        }

    }

    public function provider()
    {
        return array(
            array('ModelMock1', 'ModelMock2')
        );
    }
}
