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
use Exception;

/**
 * Permissions Model
 *
 * Maps actions to permitted roles such as groups, users, owner
 *
 */
class Permissions extends Model
{

    /**
     * @var String The resource this permissions apply to
     */
    public $resource;

    /**
     * @var Array Map of actions to roles
     * @example
     *      array(
     *          'view'  => array('everyone'),
     *          'add'   => array('registered'),
     *          'edit'  => array('owner', 'moderator', 'admin'),
     *          'delete'=> array('admin')
     *      );
     */
    public $permissions = array();
    

    /**
     * Get the allowed roles (roles, groups, users, owners) for this action
     *
     * @param String Action
     * @return Array roles
     */
    public function getPermitted($action)
    {
        return isset($this->permissions[$action]) ? $this->permissions[$action] : array();
    }

    /**
     * Set the allowed roles (roles, groups, users, owners) for this action
     *
     * @param String Action
     * @param Array roles
     */
    public function setPermitted($action, Array $roles)
    {
        return $this->permissions[$action] = $roles;
    }

    /**
     * Set data via model convention
     *
     * @param Array [ permissions => [ role, role ... ], resource => [] ]
     */
    public function setData(Array $data = array())
    {

        if (isset($data['id'])) {
            $this->id = intval($data['id']);
        }
        if (isset($data['resource'])) {
            $this->resource = $data['resource'];
        }
        if (isset($data['permissions'])) {
            $this->permissions = $data['permissions'];
        }
    }

    /**
     * Allow saving as array
     */
    public function __toArray()
    {
        return array(
            'resource' => $this->resource,
            'permissions' => $this->permissions
        );
    }
}
