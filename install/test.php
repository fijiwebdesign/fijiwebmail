<?php
/**
 * Fiji Mail Server
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_Mail
 */

use Fiji\Factory;

// autoloading Fiji classes
require_once '../lib/Autoload.php';

// our base application configuration
$Config = Factory::getSingleton('config\\App');

// developement mode requires finer error reporting
if ($Config->get('mode') == config\App::MODE_DEV) {
    require '../dev.php';
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

// test
function test() {

    echo '<h2>Perm Model</h2>';
    $Model = Factory::createModel('Fiji\App\AccessControl\Model\Permissions')
        ->find(array('resource' => 'app\\settings\\controller\\Settings'));
    var_dump($Model);

    return;
}

echo "<h1>Test</h1>";
test();


return; 

// old tests

function test1() {

    echo '<h2>Perm Model</h2>';
    $Model = Factory::createModel('Fiji\App\AccessControl\Model\Permissions')->find(array('name' => 'x'));
    var_dump($Model);

    echo '<h2>User Model</h2>';
    $Model = Factory::createModel('Fiji\App\Model\User')->find(array('username' => 'admin'));
    var_dump($Model);

    return;

    echo '<h2>Direct</h2>';
    var_dump('fiji_permissions', '`name` = :name', array(':name' => 'x'));
    $beans = R::findOne('fiji_permissions', '`name` = :name', array(':name' => 'x'));
    var_dump($beans);



    return;
}