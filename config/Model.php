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

/**
 * Model configurations
 */
class Model extends \Fiji\App\Config
{
	/**
	 * Default model class paths
	 */
	public $defaultClass = array(
		'app\\{App}\\model\\{Model}',
		'Fiji\\App\\Model\\{Model}'
	);
    
	/**
	 * Specific class path definition for "User" model
	 */
    public $User = array(
    	'app\\joomla\\model\\User', // joomla takes precedence to the current app
		'app\\{App}\\model\\User',
		'Fiji\\App\\Model\\User'
	);
    
}