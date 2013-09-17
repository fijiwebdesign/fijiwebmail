<?php

namespace app\mail\widget\navigation\model;

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
use Fiji\App\Model;
/**
 * Menu Items are individual menu links
 */
class MenuItem extends Model
{
    /**
     * Text displayed in menu
     */
    public $text;
	
	/**
     * Menu href attribute value
     */
    public $link;
	
	/**
     * Menu Icon
     */
    public $icon;
	
	/**
     * CSS className attribute value
     */
    public $className;
	
	/**
	 * Small bubble that shows on menu
	 * @var {app\mail\widget\navigation\model\MenuItemFlag}
	 */
	public $flag;
	
    
}
