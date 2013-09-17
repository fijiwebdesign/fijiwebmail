<?php
/**
 * Mealku Gallery Prototype
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_Mail
 */

namespace app\mail\model;


/**
 * User model for Gallery
 */
class User extends \Fiji\App\Model\User
{
    
    /**
	 * Construct from parent
	 */
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Retrieve mailboxes for this user
     */
    public function getMailBoxes($query = array())
    {
        $query = array_merge(array('user_id' => $this->getId()), $query);
        $mailboxList = Factory::getModelCollection('app\gallery\model\mailbox');
        $mailboxList->find(array('user_id' => $this->id));
        
        return $mailboxList;
    }
}
