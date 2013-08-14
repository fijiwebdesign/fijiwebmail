<?php

namespace Fiji\App\Model;

/**
 * Fiji MVC Framework
 *
 * @link       http://www.fijiwebdesign.com/
 * @copyright  Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license    http://framework.zend.com/license/new-bsd New BSD License
 * @package    Fiji_App
 * @subpackage Gallery
 */
 
use Fiji\Factory;

/**
 * Application Widgets (Modules in Joomla / Blocks in Drupal etc.)
 */
class Widget extends \Fiji\App\Model
{
    /**
     * ID of widget. Used in HTML/JSON/XML as ID of widget
     * @var Int $id
     * @example id="widget_1"
     */
    public $id;
    
    /**
     * Name of Widget. Used in HTML/JSON/XML as class of widget.
     * @var String $name
     * @example class="widget widget_name"
     */
    public $name;
    
    /**
     * Title of widget
     * @param String $title
     */
    public $title;
    
    /**
     * HTML Output
     * @var HTML
     */
    public $html;
    
    /**
     * Parameter/Options of widget
     * @var Stirng $params
     */
    public $params;
    
    /**
     * Classname that renders widget
     * @var String Class name (eg: app\myapp\widget\AwesomeWidget)
     */
    public $class;
    
    /**
     * Position Widget is published in
     */
    public $position;
    
    /**
     * Widget Publish state
     */
    public $published;
    
    public function __construct(Array $data = array())
    {
      parent::__construct($data);
    }
    
    /**
     * Handle getting URLs ($this->url);
     */
    public function getHtml()
    {
        
    }

}
