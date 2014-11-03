<?php
/**
 * Fiji Mail Server 
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_Mail
 */

namespace Fiji\App\AccessControl;

use Fiji\Factory;
use Fiji\App\Model\User;
use Fiji\App\AccessControl\AccessControl;
use Fiji\App\AccessControl\Model\Permissions;

/**
 * Application Group Access control implementation
 *
 * Sets and Gets the access control for a given user and resource
 */
class RoleBasedAccessControl implements AccessControl
{
    
    /**
     * @var Fiji\App\AccessControl\Permissions
     */
    protected $Permissions;
    
    /**
     * @var Fiji\App\Model\User
     */
    protected $User;

    /**
     * @var bool Owner of resource in $Permissions->resource
     */
    protected $owner = false;
    
    /**
     * Set User and Permissions
     * @param Fiji\App\Model\User $User User Model which defineds the RoleCollection for the user
     * @param Fiji\App\AccessControl\Permissions $Permissions Permissions Model that defineds the resource and roles per action
     */
    public function __construct(User $User = null, Permissions $Permissions)
    {
        $this->User = $User;
        $this->Permissions = $Permissions;
    }

    public function getPermissions($action = null)
    {
        if ($action) {
            return $this->Permissions->$action;
        }
        $data = $this->Permissions->toArray();
        return $data['permissions'];

    }

    /**
     * Set flag for user's ownership of the resource $Permissions->resource
     * @param Bool Is resource owner
     */
    public function setOwner($bool)
    {
        $this->owner = (bool) $bool;
    }

    /**
     * Is user the owner (has the "owner" role) of this $Permissions->resource
     */
    public function isOwner()
    {
        return $this->owner;
    }
    
    /**
     * Can the user execute the given $action
     * Matches the User's roles to the roles defined in the Permissions. 
     * Accounts for 'magic' roles owner and superadmin
     *
     * @param String Action
     */
    public function isPermitted($action)
    {
        // users Roles
        $Roles = $this->User->RoleCollection->getPropertyList('name');
        $Roles[] = 'everyone';

        // if user is owner
        if ($this->isOwner()) {
            $Roles[] = 'owner';
        }

        // superadmin can do anything
        if (in_array('superadmin', $Roles)) {
            return true;
        }

        // only roles user is in can perform an action with those roles set
        $AllowedGroups = $this->Permissions->getPermitted($action);
        if ($permitted = (bool) count(array_intersect($Roles, $AllowedGroups))) {
            return true;
        }

        return false;
    }

    /**
     * Call a callback if we're not permitted
     * @param String The action
     * @param Callback Callback function
     */
    public function ifNotPermitted($action, $callback)
    {
        if (!$this->isPermitted($action)) {
            $callback();
        }
    }
    
}
