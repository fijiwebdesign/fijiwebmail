<?php

// for dev
error_reporting(E_ALL);
ini_set('display_errors', 1);

$app = isset($_GET['app']) ? $_GET['app'] : 'default';
$page = isset($_GET['page']) ? $_GET['page'] : 'index';

// for now show email by default
if ($app == 'default') {
    header('Location: ?app=mail');
    die;
}

if (!preg_match('/^[a-z0-9]+$/i', $app)) {
    die;
}
if (!preg_match('/^[a-z0-9]+$/i', $page)) {
    die;
}

use Zend\Loader\StandardAutoloader;
use Fiji\Factory;

$base_path = __DIR__;
$zend_path = '/var/lib/zf2';
$zend_path = 'C:\wamp\www\fijicloud\zf2';

// autoloading zf2 classes
require_once $zend_path . '/library/Zend/Loader/StandardAutoloader.php';
$loader = new StandardAutoloader(array('autoregister_zf' => true));
$loader->register();

// Zend framework compat
require $zend_path . '/library/Zend/Stdlib/compatibility/autoload.php';
require $zend_path . '/library/Zend/Session/compatibility/autoload.php';

// autoloading Fiji classes
require_once 'lib/Autoload.php';

// get the document 
$Doc = Factory::getSingleton('Fiji\App\Document');

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

// email search widget
ob_start();
require 'app/mail/view/widget/search.php';
$Doc->search = ob_get_clean();

// main content
if ($app) {
    ob_start();
    require( 'app/' . $app . '/' . $page . '.php');
    $Doc->content = ob_get_clean();
}

// folders menu
$Doc->folderListWidget = new app\mail\view\widget\folderList('folder-list');

// navigation menu
ob_start();
require 'templates/chromatron/widgets/navigation.php';
$Doc->navigation = ob_get_clean();

// task list
ob_start();
require 'app/mail/view/widget/taskList.php';
$Doc->taskList = ob_get_clean();

$Doc->breadcrumbs = '';

// user profile widget
ob_start();
require 'templates/chromatron/widgets/userProfile.php';
$Doc->userProfile = ob_get_clean();

// growl widget
ob_start();
require 'templates/chromatron/widgets/notifications.php';
$Doc->notifications = ob_get_clean();

// load template
require( 'templates/chromatron/page.php');
