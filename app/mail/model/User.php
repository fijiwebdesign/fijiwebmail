<?php
/**
 * Fiji Webmail
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_Mail
 */

namespace app\mail\model;

use Fiji\Factory;


/**
 * User model for Mail Application
 */
class User extends \Fiji\App\Model\User
{

    /**
     * Mailbox Configuration class
     */
    protected $mailboxConfigClass = 'config\user\Mail';

    /**
     * Retrieve configurations for mailbox
     */
    public function getDefaultMailboxConfig()
    {
        $Config = Factory::getConfig($this->mailboxConfigClass);
        $Config->find(array('user_id' => $this->id));
        
        return $Config;
    }
    
    /**
     * Retrieve mailboxes configured for this user
     */
    public function getMailBoxesConfig($query = array())
    {
        $query = array_merge(array('user_id' => $this->getId()), $query);
        $mailboxList = Factory::createModelCollection($this->mailboxConfigClass);
        $mailboxList->find(array('user_id' => $this->id));
        
        return $mailboxList;
    }
}
