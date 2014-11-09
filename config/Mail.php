<?php
/**
 * Fiji Mail Server 
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_Mail
 */

namespace config;

use Fiji\App\Config;

/**
 * Gobal Email Server Configuration
 */
class Mail extends Config
{
    
    /**
     * IMAP Server settings
     */
    public $authServer = array(
        'host'     => 'localhost',
        'port'     => 143,
        'ssl'      => 'tls'
    );
    
    /**
     * Settings for sending email
     * Options as smtp|sendmail|mail
     */
    public $mailTransport = 'smtp';
    public $mailTransportOptions = array(
        'name'=> 'localhost',
        'host'=> 'localhost',
        'port' => 25,
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