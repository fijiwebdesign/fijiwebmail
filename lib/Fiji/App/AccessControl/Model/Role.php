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
 * User Roles in Role based access control
 */
class Role extends Model
{
    /**
     * @var String Role Name
     */
    public $name;

    /**
     * @var String Title
     */
    public $title;

    /**
     * @var String Description of role
     */
    public $description;

    /**
     * @var Fiji\App\AccessControl\Model\Resource The resource this role applies to
     */
    protected $Resource;

    /**
     * @var Fiji\App\Model\User The users belonging to this role
     */
    protected $UserCollection;

    /**
     * Define the references
     */
    protected $references = array(
        'UserCollection' => 'Fiji\App\Model\User'
    );

}
