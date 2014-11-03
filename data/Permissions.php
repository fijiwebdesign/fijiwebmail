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
 * Default Application Permissions
 *
 * name: name folder used in Imap Eg: INBOX.Sent
 * permissions: Human readable Name Eg: "Sent Mail"
 */
class Permissions extends Collection
{

    protected $Model = 'Fiji\App\AccessControl\Model\Permissions';

    /**
     * Permissions mapping the action => array(..roles..groups...users...accessors...)
     */
    protected $data = array(
        /**
         * Settings App
         */
        array(
            'resource' => 'app\\settings\\controller\\Settings', // arbitrary resource such as controller
            'permissions' => array(
                'index' => array('superadmin'),
                'user' => array('owner'),
                'save' => array('owner', 'superadmin'),
                'edit' => array('owner', 'superadmin'),
                'delete' => array('owner', 'superadmin')
                )
        ),
        /**
         * Email App
         */
        array(
            'resource' => 'app\\mail\\controller',
            'permissions' => array(
                'mailbox' => array('owner'),
                'message' => array('owner'), /** save, delete, etc. are all included in message actions */
                'compose' => array('owner'),
                'attachments' => array('owner')
                )
        ),
        /**
         * Calendar App
         */
        array(
            'resource' => 'app\\calendar\\controller',
            'permissions' => array(
                'index' => array('owner'),
                'create' => array('owner'),
                'delete' => array('owner'),
                'edit' => array('owner')
                )
        )
    );

}
