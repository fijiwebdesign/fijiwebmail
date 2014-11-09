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

use Fiji\App\Config;

/**
 * Your Mailbox Configuration
 */
class Mail extends Config
{

    /**
     * Email
     * @example user@domain.com
     */
    public $email;

    /**
     * Password
     * @type password
     */
    public $password;
    
    /**
     * IMAP Server
     */
    public $authServer = array(
        'host'     => 'localhost',
        'port'     => 143,
        'ssl'      => 'tls'
    );
    
    /**
     * Send email using
     * Options are smtp, sendmail, mail
     */
    public $mailTransport = 'smtp';

    /**
     * SMTP Email Server
     */
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
     * Messages per page
     */
    public $messagesPerPage = 10;
    
}