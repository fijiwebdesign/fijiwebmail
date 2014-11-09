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
 * Default Mail Settings
 *
 * name: name folder used in Imap Eg: INBOX.Sent
 * title: Human readable Name Eg: "Sent Mail"
 */
class SettingsUser extends Collection
{

    protected $Model = 'app\settings\model\Settings';

    /**
     * Our settings files
     */
    protected $data = array(
        array(
            'namespace' => 'config\\user\\User',
            'title' => 'Your Account',
            'isUser' => false,
            'icon' => 'awe-app'
        ),
        array(
            'namespace' => 'config\\user\\Mail',
            'title' => 'Mailboxes',
            'isUser' => false,
            'icon' => 'awe-app'
        ),
    );

}
