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
    
    public $User;
    public $Doc;
    public $App;
    
    /**
     * Widget name/id
     */
    public $name;
    
    /**
     * Autoloading paths
     */
    static $includePaths = array();
    
    /**
     * Require a Widget Name
     */
    public function __construct($name) {
        $this->User = Factory::getSingleton('Fiji\App\User');
        $this->Doc = Factory::getSingleton('Fiji\App\Document');
        $this->App = Factory::getSingleton('Fiji\App\Application');
        
        $this->name = $name;
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
