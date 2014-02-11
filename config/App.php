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

use Fiji\App\Config;

/**
 * Application Config
 */
class App extends Config
{
    const MODE_DEV = 'dev';
    const MODE_PROD = 'prod';
    
    public $error_reporting = E_ALL;
    
    public $supportEmail = 'info@fijiwebdesign.com';
    
    // zend frameworl library path 
    // /var/lib/zf2 on my debian linux (via aptitude)
    // /usr/share/php/Zend/ on CentoOS (via yum)
    //      to file path use: rpm -ql php-ZendFramework2-Loader 
    public $zendPath = '/usr/share/php/Zend';

    public $baseUrl = '/webmail/fijiwebmail/';
    
    public $defaultApp = 'mail';    
    
    public $mode = self::MODE_DEV;
    
    /**
     * Authentication Handling Class
     */
    public $Authentication = 'app\\mail\\lib\\App\\Authentication';
    

}



