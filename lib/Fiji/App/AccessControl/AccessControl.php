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

use Fiji\App\Model\User;
use Fiji\App\AccessControl\Model\Permissions;

/**
 * Application Access control Interface
 */
interface AccessControl
{
    
    public function __construct(User $User = null, Permissions $Permissions);
    
    /**
     * Can the user execute the given $action
     * @param {String}
     */
    public function isPermitted($action);

    /**
     * Retrieve the permissions for an action
     * @param {String}
     */
    public function getPermissions($action = null);
    
}
