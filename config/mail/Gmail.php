<?php
/**
 * Fiji Mail Server 
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_Mail
 */

namespace config\mail;

use Fiji\App\Config;

/**
 * Gmail Email Accounts
 */
class Gmail extends Config
{
    
    /**
     * GMail IMAP Server settings
     */
    $this->authServer = array(
        'host'     => 'imap.gmail.com',
        'port'     => 993,
        'ssl'      => 'ssl'
    );
    
    /**
     * Settings for sending email
     * Options as smtp|sendmail|mail
     */
    public $mailTransport = 'smtp';
    $this->mailTransportOptions = array(
        'name' => 'smtp.gmail.com',
        'host' => 'smtp.gmail.com',
        'port' => 587,
        'connection_class'  => 'login',
        'connection_config' => array(
            'username' => '',
            'password' => '',
            'ssl' => 'tls'
        ),
    );

    /**
     * Default Email folders
     * @note Only sub folders of INBOX are creatable on our Postfix/Dovecot setup
     */
    public $folders = array(
        'inbox' => 'INBOX',
        'sent' => 'INBOX.Sent',
        'drafts' => 'INBOX.Drafts',
        'archive' => 'INBOX.Archive',
        'spam' => 'INBOX.Spam',
        'trash' => 'INBOX.Trash'
    );
    
    /**
     * Messages to how per page in mailbox view
     */
    public $messagesPerPage = 10;
    
}