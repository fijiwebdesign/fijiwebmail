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
 * Menu Badges are little bubbles/flags that display on individual menu links
 */
class MenuItemBadge extends Model
{
    /**
     * Badge Title displays when hover over text
     */
    public $title;
	
	/**
     * Badge Text displays in menu bubble/flag
     */
	public $text;
    
}
