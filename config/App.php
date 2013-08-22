<?php
/**
 * Fiji Mail Server 
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_Mail
 */

namespace config;

/**
 * Application Config
 */
class App extends \Fiji\App\Config
{
    const MODE_DEV = 'dev';
    const MODE_PROD = 'prod';
    
    public $error_reporting = E_ALL;
    
    public $supportEmail = 'info@fijiwebdesign.com';
    
    // zend frameworl library path (/var/lib/zf2 on my linux)
    public $zendPath = 'C:\wamp\www\fijicloud\zf2';
    
    public $defaultApp = 'mail';    
    
    public $mode = self::MODE_DEV;
    
    public $service = array(
        'dataProvider' => 'service\\DataProvider\\MySql',
        'host' => 'localhost',
        'user' => 'root',
        'password' => '',
        'database' => 'fiji_webmail2'
    );
    
    /**
     * Authentication Handling Class
     */
    public $Authentication = 'app\\mail\\lib\\App\\Authentication';
    
    public function __construct($options = array())
    {
        parent::__construct($options);
        
        // @todo remote in production
        if (in_array($_SERVER['HTTP_HOST'], array('fijiwebdesign.com', 'fijisoftware'))) {
            $this->zendPath = '/var/lib/zf2/';
        }
        
    }
    
}



