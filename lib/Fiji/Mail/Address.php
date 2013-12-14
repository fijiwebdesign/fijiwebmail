<?php
/**
 * Fiji Mail Server 
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_Mail
 */

namespace Fiji\Mail;

use Zend\Mail\Address as ZendAddress;

/**
 * Parses an address into parts
 */
class Address extends ZendAddress
{
    /**
     * email domain
     */
    public $domain;
    
    /**
     * Email username
     */
    public $user; 
    
    /**
     * The name portion of emails in the format "Name <user@domain>"
     */
    public $name;
    
    /**
     * The email portion
     */
    public $email;
    
    /**
     * Will parse "Name" <email@domain> as well as email@domain
     * @param String Email in the form "Name" <email@domain> or just email@domain
     * @param String Optional "Name" for compat with \Zend\Mail\Address
     */
    public function __construct($email, $name = null)
    {
        if (is_null($name)) {
            $this->parse($email);
        } else {
            parent::__construct($email, $name);
        }
    }
    
    protected function parse($email)
    {
        $email = trim($email);
        if (preg_match('/^([^>]+)<([^>]+)>$/i', $email, $parts)) {
            $this->name = trim($parts[1], '" \'');
            $this->user = strtok($parts[2], '@');
            $this->domain = strtok('@');
        } else {
            $this->name = '';
            $this->user = strtok($email, '@');
            $this->domain = strtok('@');
        }
        $this->email = $this->user . '@' . $this->domain;
    }
     
}


