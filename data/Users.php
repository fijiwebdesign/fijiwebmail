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
            'title' => 'Mr',
            'password' => 'fc76fb58a797b9fbbbe1738a3ca62df3324855c3', // admin
            'secret' => '075eeef997c63c6f6d7817d2ff9949caf7d1f81b',
            'RoleCollection' => array('superadmin', 'admin', 'user')
        )
    );

}
