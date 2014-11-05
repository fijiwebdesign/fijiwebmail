<?php
/**
 * Fiji Mail Server
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_Mail
 */

namespace data;

use Fiji\Data\Collection;
use Fiji\Factory;

/**
 * Default Users
 */
class Users extends Collection
{

    protected $Model = 'Fiji\App\Model\User';

    /**
     * Users in Collection
     */
    protected $data = array(
        array(
            'username' => 'admin',
            'name' => 'Super Administrator',
            'title' => 'Mr.',
            'password' => 'fc76fb58a797b9fbbbe1738a3ca62df3324855c3', // admin
            'secret' => '075eeef997c63c6f6d7817d2ff9949caf7d1f81b',
            'RoleCollection' => array(
                array('name' => 'superadmin'), 
                array('name' => 'admin'),
                array('name' => 'user')
            )
        ),
        array(
            'username' => 'sam',
            'name' => 'Sam the man',
            'title' => 'Bro',
            'password' => 'fc76fb58a797b9fbbbe1738a3ca62df3324855c3', // admin
            'secret' => '075eeef997c63c6f6d7817d2ff9949caf7d1f81b',
            'RoleCollection' => array(
                array('name' => 'user')
            )
        )
    );

    /**
     * Create our RoleCollection from given data
     * @todo implement using Unique ID constraings on Model properties and have setData() handle References as well
     */
    public function setData(Array $data = array())
    {
        // Entity map for this UntiOfWork
        $RoleCollectionEM = array();
        // build the Role References
        foreach($data as $i => $_data) {
            $roles = $_data['RoleCollection'];
            $RoleCollection = Factory::createModelCollection('Fiji\App\AccessControl\Model\Role');

            foreach($roles as $role) {
                // get role from Entity Map
                $key = serialize($role);
                if (!isset($RoleCollectionEM[$key])) {
                    $Role = Factory::createModel('Fiji\App\AccessControl\Model\Role')->find($role)->setData($role);
                    $RoleCollectionEM[$key] = $Role;
                }
                $RoleCollection->push($RoleCollectionEM[$key]);
            }
            $data[$i]['RoleCollection'] = $RoleCollection;
        }

        // now set the data and allow RoleCollecton 
        parent::setDynamic($data);
    }

}
