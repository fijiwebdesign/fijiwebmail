<?php
/**
 * Fiji Mail Server
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_Mail
 */

use Fiji\Factory;

// autoloading Fiji classes
require_once '../lib/Autoload.php';

// our base application configuration
$Config = Factory::getSingleton('config\\App');

// developement mode requires finer error reporting
if ($Config->get('mode') == config\App::MODE_DEV) {
    require '../dev.php';
}

// necessary file paths
$base_path = __DIR__;

// Required instances
$Req = Factory::getRequest();
$Doc = Factory::getDocument();
$App = Factory::getApplication();
$Uri = Factory::getSingleton('Fiji\\App\\Uri');

// base URL
if ($base_url = $Config->get('baseUrl')) {
    $Uri->setBase($base_url);
}

// populate users
function clearUsers() {
    $UserCollection = Factory::createInstance('data\Users');

    foreach($UserCollection as $User) {
        // find user with matching username
        $SavedUser = Factory::createModel(get_class($User))->find(array('username' => $User->username));
        // user exists
        if ($SavedUser->id) {
            $SavedUser->delete();
            echo "<p>Deleted user {$User->username}. Already exists in storage.</p>";
        } else {
            echo "<p>User {$User->username} does NOT exist in storage.</p>";
        }
    }
}

// populate roles
function clearRoles() {
    $RoleCollection = Factory::createInstance('data\Roles');
    foreach($RoleCollection as $Role) {
        // Role with the same title exists in storage
        $SavedRole = Factory::createModel(get_class($Role))->find(array('name' => $Role->name));
        if ($SavedRole->id) {
            $SavedRole->delete();
            echo "<p>Deleted role {$Role->name}. Already exists in storage.</p>";
        } else {
            echo "<p>Role {$Role->name} does NOT exist in storage.</p>";
        }
    }
}

// populate permissions
function clearPerms() {
    $PermCollection = Factory::createInstance('data\Permissions');
    foreach($PermCollection as $Perm) {
        // Perms for the same resource exists in storage
        $SavedPerm = Factory::createModel(get_class($Perm))->find(array('resource' => $Perm->resource));
        var_dump($SavedPerm);
        if ($SavedPerm->id) {
            $SavedPerm->delete();
            echo "<p>Deleted perm {$Perm->resource}. Already exists in storage.</p>";
        } else {
            echo "<p>Perm {$Perm->resource} does NOT exist in storage.</p>";
        }
    }
}

clearUsers();
clearRoles();
clearPerms();
