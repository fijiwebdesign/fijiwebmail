<?php
/**
 * Test case for Access Control
 * @author gabe@fijiwebdesign.com
 * @example Using PHPUnit in vendor/
 *          php  ./vendor/phpunit/phpunit/phpunit.php --verbose test/PermissionsTest.php
 *          Using PHPUnit installed globally
 *            phpunit --verbose test/PermissionsTest.php
 */

require_once __DIR__ . '/../lib/Autoload.php';

// mocks Factory, Service and Models
require_once __DIR__ . '/bootstrap/Mocks.php';

use Fiji\App\Model;

class PermissionsTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test getPermitted()
     * @dataProvider provider
     */
    public function testGetPermitted($PermsModel, $perms, $resource)
    {

        $viewers = $PermsModel->getPermitted('view');
        $editors = $PermsModel->getPermitted('edit');

        $this->assertEquals($perms['view'], $viewers);
        $this->assertEquals($perms['edit'], $editors);


    }

    /**
     * Test role based access control
     * @dataProvider provider
     */
    public function testRBAccessControl($PermsModel, $perms, $resource)
    {
        // user with roles []
        $User = $this->userProvider(array('registered', 'moderator'));

        $ACL = new Fiji\App\AccessControl\RoleBasedAccessControl($User, $PermsModel);
        $view = $ACL->isPermitted('view');
        $add = $ACL->isPermitted('add');
        $edit = $ACL->isPermitted('edit');
        $delete = $ACL->isPermitted('delete');

        // is permitted for matching roles (registered, moderator)
        $this->assertTrue($view); // true because "everyone" role is set
        $this->assertTrue($add); // matches "registered" role
        $this->assertTrue($edit); // matches "moderator" role
        $this->assertFalse($delete);

    }

    /**
     * Test super admin should be able to do everything
     * @dataProvider provider
     */
    public function testRBAccessControl_SuperAdmin($PermsModel, $perms, $resource)
    {
        // user with roles []
        $User = $this->userProvider(array('superadmin'));

        $ACL = new Fiji\App\AccessControl\RoleBasedAccessControl($User, $PermsModel);
        $view = $ACL->isPermitted('view');
        $add = $ACL->isPermitted('add');
        $edit = $ACL->isPermitted('edit');
        $delete = $ACL->isPermitted('delete');

        // super can do all
        $this->assertTrue($view);
        $this->assertTrue($add);
        $this->assertTrue($edit);
        $this->assertTrue($delete); 
    }

    /**
     * Test owner role permissions
     * @dataProvider provider
     */
    public function testRBAccessControl_Owner($PermsModel, $perms, $resource)
    {
        // user with roles []
        $User = $this->userProvider(array('owner'));

        $ACL = new Fiji\App\AccessControl\RoleBasedAccessControl($User, $PermsModel);
        $ACL->setOwner(true); // make user owner of this ACL resource

        $view = $ACL->isPermitted('view');
        $add = $ACL->isPermitted('add');
        $edit = $ACL->isPermitted('edit');
        $delete = $ACL->isPermitted('delete');

        // owner permissions 
        $this->assertTrue($view); // true because "everyone" role is set
        $this->assertFalse($add);
        $this->assertTrue($edit); // matches "owner" role defined in permissions list
        $this->assertFalse($delete); 
    }

    public function userProvider($roles = array())
    {
        $User = Factory::createModel('Fiji\App\Model\User');
        $User->username = 'tester';

        $data = array();
        foreach($roles as $role) {
            $data[] = array('name' => $role);
        }

        $User->RoleCollection->setData($data);
        return $User;
    }

    public function provider()
    {

        $PermsModel = Factory::createModel('Fiji\App\AccessControl\Model\Permissions');
        // permissions array
        $perms = array(
            'view'  => array('everyone'),
            'add'   => array('registered'),
            'edit'  => array('owner', 'moderator', 'admin'),
            'delete'=> array('admin')
        );
        $resource = 'app\mail';

        // save for mail
        $PermsModel->resource = $resource;
        $PermsModel->permissions = $perms;

        return array(
            array($PermsModel, $perms, $resource)
        );
    }
}

/**
 * Model Mockup
 */
//class Permissions extends Fiji\App\AccessControl\Model\Permissions {}