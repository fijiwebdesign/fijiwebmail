<?php
/**
 * Fiji Mail Server 
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_Mail
 */

use Zend\Loader\StandardAutoloader;
use Fiji\Factory;

/**
 * Include Zend Framework Autoloader
 */
$Config = Factory::getConfig();
$zendPath = $Config->get('zendPath');

// autoloading zf2 classes
require_once $zendPath . '/Loader/StandardAutoloader.php';
$loader = new StandardAutoloader(array('autoregister_zf' => true));
$loader->register();

// Zend framework compat
require $zendPath . '/Stdlib/compatibility/autoload.php';
require $zendPath . '/Session/compatibility/autoload.php';