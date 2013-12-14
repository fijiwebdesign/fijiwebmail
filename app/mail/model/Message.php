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

use Fiji\App\Model;
use Fiji\Factory;
use Fiji\Mail\AddressList;

/**
 * Email Message model
 */
class Message extends Model
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
     * Unique Message ID on IMAP server. 
	 * $uid+$folder should always be unique. 
	 * $uid changes when folder changes.
     */
    public $uid;
    
    /**
     * Raw Mime Message
     */
    public $mime;
	
	/**
	 * Folder message is in
	 */
	public $folder;
	
	public function __construct($data = null)
	{
		// dynamic properties
		unset($this->from);
		unset($this->to);
		
		// set properties
		parent::__construct($data);
	}
	
	public function getFrom()
	{
		if (is_object($this->from)) {
			return $this->from;
		} else {
			return new AddressList($this->from);
		}
	}
	
	public function setFrom($value)
	{
		if (is_object($this->from)) {
			$this->from = $value;
		} else {
			$this->from = new AddressList($value);
		}
	}
	
	public function getTo()
	{
		if (is_object($this->to)) {
			return $this->to;
		} else {
			return new AddressList($this->to);
		}
	}
	
	public function setTo($value)
	{
		if (is_object($this->to)) {
			$this->to = $value;
		} else {
			$this->to = new AddressList($value);
		}
	}

}
