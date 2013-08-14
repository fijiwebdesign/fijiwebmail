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

class Mail extends \Fiji\App\Config 
{
    /**
     * Cache IMAP calls
     */
    public $cache_imap = false;
    
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
            //'ssl' => ''
        ),
    );
    
    
    /**
     * Folders required by email
     */
    public $folders = array(
        'inbox' => 'INBOX',
        'sent' => 'Sent Mail',
        'drafts' => 'Drafts',
        'archive' => 'Archive',
        'spam' => 'Spam',
        'trash' => 'Trash'
    );
    
    public $dev = true;
    
    /**
     * Overrides for development hosts
     */
    public function __construct(Array $options = array())
    {
        // dev settings
        if (in_array($_SERVER['HTTP_HOST'], array('fijiwebdesign.com', 'fijisoftware'))) {
            $this->authServer = array(
                'host'     => 'imap.gmail.com',
                'port'     => 993,
                'ssl'      => 'ssl'
            );
            
            $this->mailTransportOptions = array(
                'name'=> 'smtp.gmail.com',
                'host'=> 'smtp.gmail.com',
                'port' => 587,
                'connection_class'  => 'login',
                'connection_config' => array(
                    'username' => '',
                    'password' => '',
                    'ssl' => 'tls'
                ),
            );
            
        }
        
        parent::__construct($options);
    }
    
    
    
}



