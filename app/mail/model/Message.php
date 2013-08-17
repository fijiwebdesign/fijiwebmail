<?php

namespace app\mail\model;

/**
 * Fiji Cloud Email
 *
 * @link       http://www.fijiwebdesign.com/
 * @copyright  Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license    http://framework.zend.com/license/new-bsd New BSD License
 * @package    Fiji_App
 * @subpackage Gallery
 */
 
use Fiji\Factory;

/**
 * Email Message model
 */
class Message extends \Fiji\App\Model
{
    
    public $subject;
    
    /**
     * String in the form: "Name <user@domain.com>, Name2 <user2@domain.com>"
     * @var String or Fiji\Mail\AddressList
     */
    public $from;
    
    /**
     * String in the form: "Name <user@domain.com>, Name2 <user2@domain.com>"
     * @var String or Fiji\Mail\AddressList
     */
    public $to;
    
    /**
     * Unique Message ID on IMAP server
     */
    public $messageid;
    
    /**
     * Raw Mime Message
     */
    public $body;

}
