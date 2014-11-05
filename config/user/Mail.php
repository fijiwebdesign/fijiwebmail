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

/**
 * Custom user mail accounts configuration
 */
class Mail extends \config\Mail
{
    
    /**
     * IMAP Server settings
     */
    public $authServer = array(
        'host'     => 'localhost',
        'port'     => 143,
        'ssl'      => ''
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
     * Messages to how per page in mailbox view
     */
    public $messagesPerPage = 10;
    
}



