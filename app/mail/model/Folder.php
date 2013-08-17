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
 * Mail Folder Model
 */
class Folder extends \Fiji\App\Model
{
    public $id;
    
    public $title;
    
    public $mailbox_id;
    
    public $type;
    
    public $caption;
    
    public $created;
    
    public $created_by;
    
    public function __construct(Array $data = array())
    {
      parent::__construct($data);
    }
    
    /**
     * Get Emails in this folder
     */
    public function getMessages()
    {
        $ModelList = Factory::createModelCollection('app\mail\model\Message');
        $ModelList->find(array('folder_id' => $this->getId()));
        
        return $ModelList;
    }


}
