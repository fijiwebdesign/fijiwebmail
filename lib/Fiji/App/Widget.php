<?php
/**
 * Fiji Mail Server 
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_Mail
 */

namespace Fiji\App;

use Fiji\Factory;

/**
 * Application Widget
 */
abstract class Widget {

    /**
     * Generated Unique ID of widget
     */
    public $guid; 
    
    /**
     * Widget name/id
     */
    public $name;
    
    /**
     * Autoloading paths
     */
    static $includePaths = array();

    /**
     * Number of times we've invoked this object
     */
    static $invoke_count = 0;
    
    /**
     * Require a Widget Name
     * @todo should require a Fiji\App\Model parameter so each widget is a model view
     */
    public function __construct($name = null) {
        
        $this->name = $name;

        self::$invoke_count++;
        $this->guid = (is_object($name) ? crc32(serialize($name)) : $name) . '-' . self::$invoke_count;
    }
    
    /**
     * Render the widget
     */
    abstract function render($format = 'html');
    
    /**
     * Register file paths for auto-including widget files
     * @param Array Paths
     */
    static function addAutoloadPath($includePaths = array())
    {
        self::$includePaths[] = array_merge(self::$includePaths, $includePaths);
    }
    
    /**
     * Register file paths for auto-loading widget classes
     * @param Array Paths
     */
    static function autoloadRegister($includePaths = array())
    {        
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
    }
    
}
