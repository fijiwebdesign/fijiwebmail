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
    
    protected $Controller;
    
    /**
     * 
     */
    public function __construct() {
        $this->App = Factory::getSingleton('Fiji\App\Application');
        $this->Controller = Factory::getSingleton('Fiji\App\Controller');
    }
    
    /**
     * 
     */
    public function getTemplate($tmpl)
    {
        return $this->App->getPath() . '/view/' . $tmpl . '.php';
    }
    
    
}
