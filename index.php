<?php

use Zend\Loader\StandardAutoloader;
use Fiji\Factory;

// autoloading Fiji classes
require_once 'lib/Autoload.php';

// our base application configuration
$Config = Factory::getSingleton('config\\App');

// turn on errors in development mode
if ($Config->get('mode') == config\App::MODE_DEV) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

// necessary file paths
$base_path = __DIR__;
$zendPath = $Config->get('zendPath');

// autoloading zf2 classes
require_once $zendPath . '/library/Zend/Loader/StandardAutoloader.php';
$loader = new StandardAutoloader(array('autoregister_zf' => true));
$loader->register();

// Zend framework compat
require $zendPath . '/library/Zend/Stdlib/compatibility/autoload.php';
require $zendPath . '/library/Zend/Session/compatibility/autoload.php';

// Required instances
$Req = Factory::getRequest();
$Doc = Factory::getDocument();
$App = Factory::getApplication();
$Uri = Factory::getSingleton('Fiji\\App\\Uri');

$options = array(
    'remember_me_seconds' => 2419200,
    'use_cookies' => true,
    'cookie_httponly' => true
);

use Zend\Session;
use Zend\Session\Config\SessionConfig;
use Zend\Session\SessionManager;
use Zend\Session\Container;

$config = new SessionConfig();
$config->setOptions($options);
$manager = new SessionManager($config);
Container::setDefaultManager($manager);

// application and page requested. App is the module, and page is the controller
$app = $Req->getAlphaNum('app', $Config->get('defaultApp'));

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

// load template
ob_start();
if ($siteTemplate == 'app') {
    require('templates/chromatron/app.php');
} elseif ($siteTemplate == 'ajax') {
	require('templates/chromatron/ajax.php');
} else {
    require('templates/chromatron/page.php');
}
ob_end_flush();
