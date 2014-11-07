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
