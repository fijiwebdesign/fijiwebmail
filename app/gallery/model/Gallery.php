<?php

namespace app\gallery\model;

/**
 * Gallery Model
 *
 * @link       http://www.fijiwebdesign.com/
 * @copyright  Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license    http://framework.zend.com/license/new-bsd New BSD License
 * @package    Fiji_App
 * @subpackage Gallery
 */

use Fiji\Factory;

/**
 * Gallery Model
 */
class Gallery extends \Fiji\App\Model
{
    public $id;
    
    public $title;
    
    public $type;
    
    public $caption;
    
    public $created;
    
    public $created_by;
    
    public function __construct(Array $data = array())
    {
      parent::__construct($data);
    }
    
    /**
     * Get Media for this Gallery
     */
    public function getMedia()
    {
        $MediaList = Factory::createModelCollection('app\gallery\model\Media');
        $MediaList->find(array('gallery_id' => $this->getId()));
        
        return $MediaList;
    }


}
