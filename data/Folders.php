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
 * Default Mail Folders
 * 
 * name: name folder used in Imap Eg: INBOX.Sent
 * title: Human readable Name Eg: "Sent Mail"
 */
class Folders extends Collection
{
    
    protected $Model = 'app\mail\model\DefaultFolder';
    
    /**
     * Widgets in Collection
     */
    protected $data = array(
        array(
			'def_name' => 'inbox',
            'name' => 'INBOX',
            'title' => 'Inbox',
            'icon' => 'awe-inbox'
        ),
        array(
			'def_name' => 'sent',
            'name' => 'INBOX.Sent',
            'title' => 'Sent Mail',
            'icon' => 'awe-plane'
        ),
        array(
			'def_name' => 'drafts',
            'name' => 'INBOX.Drafts',
            'title' => 'Drafts',
            'icon' => 'awe-edit'
        ),
        array(
			'def_name' => 'archive',
            'name' => 'INBOX.Archive',
            'title' => 'Archive',
            'icon' => 'awe-briefcase'
        ),
        array(
			'def_name' => 'trash',
            'name' => 'INBOX.Trash',
            'title' => 'Trash',
            'icon' => 'awe-trash'
        ),
        array(
			'def_name' => 'spam',
            'name' => 'INBOX.Spam',
            'title' => 'Spam',
            'icon' => 'awe-warning-sign'
        )
        
    );
    
}



