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

// @todo handle from router
$View = Factory::getSingleton('app\\gallery\\view\\Gallery');

$mode = Factory::getSingleton('config\\App')->get('mode');

try {
    $Controller = new app\gallery\controller\Gallery($View);
} catch (\Exception $e) {
    if($mode == config\App::MODE_DEV) {
        throw new \Exception($e->getMessage());
    } else {
        // @todo custom error page
        echo "<h1>Error Loading page!</h1>";
        echo "<p class=\"alert fade in\">A error occurred with the message: " . $e->getMessage() . "</p>";
    }
}

