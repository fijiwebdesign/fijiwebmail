<?php

namespace app\gallery\model;

/**
 * Mealku Gallery Prototype
 *
 * @link       http://www.fijiwebdesign.com/
 * @copyright  Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license    http://framework.zend.com/license/new-bsd New BSD License
 * @package    Fiji_App
 * @subpackage Gallery
 */
 
use Fiji\Factory;

/**
 * Gallery Media Model
 */
class Media extends \Fiji\App\Model
{
    
    public $title;
    
    public $filename;
    
    public $description;
    
    public $gallery_id;
    
    public $created;
    
    public $created_by;
    
    public function __construct(Array $data = array())
    {
      parent::__construct($data);
    }
    
    /**
     * Handle getting URLs ($this->url);
     */
    public function getUrl()
    {
        $App = Factory::getApplication();
        $basePath = $App->getPathBase();
        return str_replace($basePath . '/', '', $this->filename);
    }

}
