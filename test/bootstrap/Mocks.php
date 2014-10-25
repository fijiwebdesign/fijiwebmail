<?php
/**
 * Test Mocks
 * @author gabe@fijiwebdesign.com
 *
 * Fiji Communication Server
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_App
 */

use Fiji\Factory as FijiFactory;
use Fiji\App\Model;

/**
 * Model Mockup
 */
class ModelMock1 extends Model
{

    /**
     * @var private
     */
    private $private;

    protected $protected;

    public $public;

    /**
     * @var ModelMock2 Reference to ModelMock2 instance
     */
    //protected $ModelMock2; // intentional. Allow setting references at runtime

    protected $References = array(
        'ModelMock2' => 'ModelMock2'
    );

}

/**
 * Model Mockup
 */
class ModelMock2 extends Model
{

    /**
     * @var private
     */
    private $private;

    protected $protected;

    public $public;

    protected $ref1;

    protected $References = array(
        'ref1' => 'ModelMock1'
    );

}

/**
 * Model Mockup with collection reference
 */
class ModelMock3 extends Model
{

    /**
     * @var private
     */
    private $private;

    protected $protected;

    public $public;

    protected $RefCollection;

    protected $References = array(
        'RefCollection' => 'ModelMock1',
        'RefCollection2' => 'ModelMock1'
    );

}


/**
 * Service Configuration for in memory data persistence
 *
 */
class Service extends Fiji\App\Config
{
    public $dataProvider = 'service\\DataProvider\\RedBean\\RedBean';
    /**
     * Use in memory database for testing
     */
    public $dbtype = 'sqlite';
    public $path = ':memory:';
    public $database = 'fiji_webmail';
    public $tablePrefix = 'fiji_';

    public function __construct()
    {
        // so we can view db in dev because :memory" db isn't across session
        ///$this->path = (__DIR__ . '/../.db/test.db');
        //echo "db: " . $this->path . PHP_EOL;
        parent::construct();
    }

    public function clearStorage()
    {
        if ($this->path !== ':memory:') {
            @unlink($this->path);
        }
    }

}

/**
 * Mock our factory so we can mock data provider for our models
 * @todo We should have Factory::setCreateModel(function() {}); 
 *       to make Factory extensible without changing namespace.
 *       or we could set an autoloader for Fiji\Factory poiting to this
 */
class Factory extends FijiFactory
{

    /**
     * Create a model instance
     */
    static function createModel($className, Array $params = array())
    {
        $Model = parent::createModel($className, $params);
        $DataProvider = self::getDataProvider();
        $Service = self::getService($DataProvider);
        $Model->setService($Service);
        return $Model;
    }

    /**
     * Retrieve a Fiji\App\DataProvider Instance
     */
    static public function getDataProvider(Fiji\App\Config $Config = null)
    {
        $Config = new Service;
        $dataProvider = $Config->get('dataProvider');

        return self::getSingleton($dataProvider, array($Config));
    }

}