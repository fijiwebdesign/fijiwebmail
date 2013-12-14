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

use Zend\Mail\AddressList as ZendAddressList;

/**
 * Parses an address into parts
 */
class AddressList extends ZendAddressList
{
    
    public function __construct($emails)
    {
        $this->parse($emails);
    }
    
    /**
     * Parse a string of email addresses
     */
    protected function parse($emails)
    {
        $emails = explode(',', $emails);
        
        $emailList = array();
        foreach($emails as $email) {
            $this->add(new Address($email));
        }
    }
	
	/**
	 * Convert addresses to string in the form 
	 * "Name <user@domain.com>, Name2 <user2@domain.com>"
	 */
	public function __toString()
	{
		$this->rewind();
        $str = '';
        while($this->valid()) {
            $name = htmlentities(trim($this->current()->getName(), ' "'), ENT_QUOTES, 'UTF-8');
            $email = htmlentities(trim($this->current()->getEmail()), ENT_QUOTES, 'UTF-8');
            $html .= ($name ? '"' . $name . '" ' : '') . $email; 
            $this->next();
        }
        return $str;
	}
     
}

