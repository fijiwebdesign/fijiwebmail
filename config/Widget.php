<?php
/**
 * Fiji Mail Server 
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_Mail
 */

namespace config;

use Fiji\App\Config;

/**
 * Widgets configuration
 * 
 */
class Widget extends Config
{
    // look in data\Widgets for widget positions
    
    /**
	 *  Paths to look for widgets
	 */
	public $defaultClass = array(
		'app\\{App}\\widget\\{Widget}',
		'app\\{App}\\widget\\{Widget}\\{Widget}',
		'app\\{App}\\view\\widget\\{Widget}',
		'app\\{App}\\view\\widget\\{Widget}\\{Widget}',
		'Fiji\\App\\Widget\\{Widget}'
	);
    
}

