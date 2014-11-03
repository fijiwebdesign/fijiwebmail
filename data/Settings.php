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
class Settings extends Collection
{
    
    protected $Model = 'app\settings\model\Settings';
    
    /**
     * Widgets in Collection
     */
    protected $data = array(
        array(
            'namespace' => 'app\\settings\\model\\App', // extends config\\App
            'title' => 'Global App',
            'isUser' => false,
            'icon' => 'awe-app'
        ),
        array(
            'namespace' => 'config\\Mail',
            'title' => 'Mail App',
            'isUser' => false,
            'icon' => 'awe-inbox'
        ),
        array(
            'namespace' => 'config\\Service',
            'title' => 'Storage Service',
            'isUser' => false,
            'icon' => 'awe-'
        )
    );
    
}



