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

/**
 * Parses an address into parts
 */
class AddressList extends \Zend\Mail\AddressList
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
     
}

