<?php
/**
 * Fiji Cloud Mail
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_Mail
 */

namespace app\gallery\model;


/**
 * Mailboxes are individual email accounts
 */
class Mailbox extends \Fiji\App\User
{
    
    public $email;
    
    public $password;
    
    public $host;
    
    public $port;
    
    public $ssl;
    
    public function __construct()
    {
        
    }
    
    
    
}
