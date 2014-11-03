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

/**
 * Default User Roles
 */
class Roles extends Collection
{
    
    protected $Model = 'Fiji\App\AccessControl\Model\Role';
    
    /**
     * Different Roles
     */
    protected $data = array(
        /**
         * Registered Users
         */
        array(
            'name' => 'user', // unique name
            'title' => 'Registered User',
            'description' => 'A user that is registered and logged into the application'
        ),
        /**
         * Resource Owner
         */
        array(
            'name' => 'owner', // unique name
            'title' => 'Owner',
            'description' => 'The user that created the resource'
        ),
        /**
         * Resource Admin (eg: Admin over specific email domain)
         */
        array(
            'name' => 'admin', // unique name
            'title' => 'Administrator',
            'description' => 'Is the administrator for a specific company, group, domain or application'
        ),
        /**
         * Super Admin is allowed everything ;)
         */
        array(
            'name' => 'superadmin', // unique name
            'title' => 'Super Administrator',
            'description' => 'The overall administrator with full access to every aspect of the application'
        ),
        
    );
    
}



