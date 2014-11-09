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

    /**
     * Site Name
     */
    public $SiteName = 'Fiji Cloud Mail';

    /**
     * Error Reporting
     * @var Int
     * @type checkbox
     */
    public $error_reporting = E_ALL;

    /**
     * Random Secret
     * @var String
     */
    public $secret = '3scjDSs8*D@dszpxcSDsdoijdDsz';

    /**
     * Support Email Address
     */
    public $supportEmail = 'info@fijiwebdesign.com';

    // zend framework library path
    // /var/lib/zf2 on my debian linux (via aptitude)
    // /usr/share/php/Zend/ on CentoOS (via yum)
    //      to find the file path use: rpm -ql php-ZendFramework2-Loader
    //
    // vendor/zendframework/zendframework/library/Zend/ via composer
    // To install via composer, cd to the app's base directory eg:
    //          cd /www/var/webmail/
    //          composer install
    /**
     * Path to Zend Famework Library
     * Only needed if you manually install Zend Famework
     * @type directory
     */
    public $zendPath = '/var/lib/zf2/library/Zend/';

    /**
     * Base URL of our app.
     */
    public $baseUrl = '/fijiwebmail/';

    /**
     * Base Application Folder
     */
    public $basePath;

    public $defaultApp = 'mail';

    public $mode = self::MODE_DEV;

    /**
     * Authentication Handling Class
     */
    public $Authentication = 'Fiji\\App\\Authentication';

    /**
     * Base site template
     * @type select
     * @options chromatron|emerald
     * @validate file_exists $Config->basePath . '/templates/$template/'
     */
    public $template = 'chromatron';

    /**
     * Set the dynamic settings
     */
    public function __construct()
    {
        // dynamic options
        $this->baseUrl = str_replace('?' . @$_SERVER['QUERY_STRING'], '', @$_SERVER['REQUEST_URI']); // suppress errors for cli mode
        $this->basePath = realpath('/../');
    }

}
