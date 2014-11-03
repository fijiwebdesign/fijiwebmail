<?php

namespace Fiji\App\AccessControl\Model;

/**
 * Fiji Cloud Mail
 *
 * @link       http://www.fijiwebdesign.com/
 * @copyright  Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license    http://framework.zend.com/license/new-bsd New BSD License
 */

use Fiji\App\Model;
use Fiji\Factory;

/**
 * User Actions in Action based access control
 */
class Action extends Model
{
    /**
     * @var String Action Name
     */
    public $name;

    /**
     * @var String Title
     */
    public $title;

    /**
     * @var String Description of Action
     */
    public $description;

    /**
     * @var Fiji\Service\DomainCollection[Fiji\App\AccessControl\Model\Resource] The resources this Action applies to
     */
    protected $ResourceCollection;

    /**
     * Define the references
     */
    protected $references = array(
        'ResourceCollection' => 'Fiji\App\AccessControl\Model\Resource'
    );

}
