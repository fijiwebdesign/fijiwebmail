<?php
/**
 * Fiji Mail Server
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_Mail
 */

namespace config\user;

use Fiji\App\Model\User as ModelUser;
use Fiji\Factory;

/**
 * Account Settings
 */
class User extends ModelUser
{

    /**
     * Username
     */
    public $username;

    /**
     * Full Name
     */
    public $name;

    /**
     * Password
     * @type password
     */
    public $password;

    /**
     * Primary Email
     */
    public $email;

    /**
     * Find the User
     */
    public function find($query = null)
    {
        $User = Factory::getUser();
        $this->setData($User->toArray());
        $this->password = null;
        return $this;
    }

    /**
     * Save the User
     */
    public function save()
    {
        if ($this->password) {
            // hash the password before saving
            $Auth = Factory::getAuthentication();
            $this->secret = sha1($this->secret . rand() . microtime(true) . rand()); // new secret
            $this->password = $Auth->getPasswordHash($this->password, $this->secret);
        }
        parent::save();
    }

}
