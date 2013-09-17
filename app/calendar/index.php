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

$mode = Factory::getSingleton('config\\App')->get('mode');

try {
    $Controller = Factory::getSingleton('app\\calendar\\controller\\calendar');
} catch (\Exception $e) {
    if($mode == config\App::MODE_DEV) {
        echo "<h3>Error occurred!</h3>";
        echo '<p class="alert fade in">Message: ' . $e->getMessage();
        echo '<br>File: ' . $e->getFile();
        echo '<br>Line: ' . $e->getLine() . '</p>';
        
        if (isset($e->xdebug_message)) {
            echo '<pre class="alert">'. $e->xdebug_message . '</pre>';
            var_dump($e);
        } else {
            echo '<pre class="alert">' . print_r($e, true) . '</pre>';
        }
        
    } else {
        // @todo custom error page
        echo "<h1>Error Loading page!</h1>";
        echo "<p class=\"alert fade in\">A error occurred with the message: " . $e->getMessage() . "</p>";
    }
}

