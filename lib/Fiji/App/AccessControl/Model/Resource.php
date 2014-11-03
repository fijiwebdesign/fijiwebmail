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
 * Resource in Role based access control
 */
class Resource extends Model
{
    /**
     * @var String Resource Name
     */
    public $name;

    /**
     * @var String Title
     */
    public $title;

    /**
     * @var String Description of Resource
     */
    public $description;

    /**
     * @var Fiji\Service\DomainCollection[Fiji\App\AccessControl\Model\Action] Actions provided for this resource
     */
    protected $ActionCollection;

    /**
     * Define the references
     */
    protected $references = array(
        'ActionCollection' => 'Fiji\App\AccessControl\Model\Action'
    );

}
