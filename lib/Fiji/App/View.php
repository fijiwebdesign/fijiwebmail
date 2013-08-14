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
 * Application control
 */
class View {
    
    protected $App;
    
    protected $templatePath;
    
    /**
     * 
     */
    public function __construct(Application $App = null) {
        $this->App = $App ? $App : Factory::getSingleton('Fiji\App\Application');
    }
    
    /**
     * Set variables to use in the template
     */
    public function set($name, $var)
    {
        $this->props[$name] = $var;
    }
    
    /**
     * Path to view
     */
    public function getTemplatePath()
    {
        if (!isset($this->templatePath)) {
            $path = str_replace('\\', '/', get_class($this));
            $this->templatePath = $path;
        }
        return $this->templatePath;
    }
    
    public function setTemplatePath($path)
    {
        $this->templatePath = $path;
    }
    
    /**
     * Load the template
     */
    public function getTemplate($tmpl)
    {
        // localize properties
        foreach($this->props as $name => $value) {
            $$name = $value;
        }
        
        ob_start();
        include($this->getTemplatePath() . '/' . $tmpl . '.php');
        return ob_get_clean();
    }
    
    /**
     * Display a template
     */
    public function display($tmpl)
    {
        // localize properties
        foreach($this->props as $name => $value) {
            $$name = $value;
        }
        include($this->getTemplatePath() . '/' . $tmpl . '.php');
    }
    
}
