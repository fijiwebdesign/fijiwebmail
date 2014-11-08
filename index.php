<?php
/**
 * Fiji Mail Server
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_Mail
 */

// display any errors autoloading Fiji Framework
error_reporting(E_ALL);
ini_set('display_errors', true);

use Fiji\Factory;

// autoloading Fiji classes
require_once 'lib/Autoload.php';

// our base application configuration
$Config = Factory::getConfig();

// developement mode requires finer error reporting
if ($Config->get('mode') == config\App::MODE_DEV) {
    require 'dev.php';
// do not display errors in production
} else {
    ini_set('display_errors', false);
}

// necessary file paths
$base_path = __DIR__;

// Required instances
$Req = Factory::getRequest();
$Doc = Factory::getDocument();
$App = Factory::getApplication();
$Uri = Factory::getSingleton('Fiji\\App\\Uri');

// base URL
if ($base_url = $Config->get('baseUrl')) {
    $Uri->setBase($base_url);
}

// application and page requested. App is the module, and page is the controller
$app = $Req->getAlphaNum('app', $Config->get('defaultApp'));
$Req->set('app', $app);

// main content
// @todo Move this to application.
// @example Factory::getApplication($app)->execute()
// 			\__ app\mail\http()->execute()
//				\__ app\mail\controller\mail()->execute() etc..
if ($app) {
    ob_start();
    require( 'app/' . $app . '/index.php');
    $Doc->content = ob_get_clean();
}

$siteTemplate = $Req->get('siteTemplate');

$template = $Config->get('template', 'chromatron');

// load template
ob_start();
if ($siteTemplate == 'app') {
    require('templates/' . $template . '/app.php');
} elseif ($siteTemplate == 'ajax') {
	require('templates/' . $template . '/ajax.php');
} else {
    require('templates/' . $template . '/page.php');
}
ob_end_flush();
