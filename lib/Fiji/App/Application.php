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
class Application {
    
    public $User;
    
    public function __construct() {
        $this->User = Factory::getSingleton('Fiji\App\User');
    }
    
    /**
     * A URL to return to after performing a function
     */
    public function setReturnUrl($returnUrl)
    {
        $this->User->returnUrl = $returnUrl;
    }
    
    /**
     * Get the URL we want to redirect to
     */
    public function getReturnUrl($default = '')
    {
        $return = $this->User->returnUrl ? $this->User->returnUrl : $default;
        $this->User->returnUrl = null;
        return $return;
    }
    
    public function redirect($url, $msg = null)
    {
        if ($msg) {
            $this->User->addNotification($msg);
        }
        
        header('Location: ' . $url);
        die;
    }
    
    
}
