<?php
/**
 * Fiji Mail Server 
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_Mail
 */

// paths to look in for classes
$includePaths = array(
    __DIR__,
    dirname(dirname(__FILE__))
);
 
/**
 * Autoload Fiji classes 
 * 
 * @param string $className 
 */
spl_autoload_register( function( $className ) use ($includePaths)
{
    
    $path = str_replace('\\', '/', $className);
    $path = $path . '.php';
    
    foreach($includePaths as $includePath) {
        // look in library
        if (file_exists($includePath . '/' . $path)) {
            include $includePath . '/' . $path;
        }
    }
    
});

/**
 * Autoload Zend Framework 2 classes
 */
require_once __DIR__ . '/ZendFramework2/Autoload.php';