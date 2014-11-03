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
use Fiji\Factory;

/**
 * Base Storage Configuration
 */
class Service extends Config
{
    /**
     * DataProvider class namespace that provide storage interface implementation
     * @example service\\DataProvider\\RedBean\\RedBean or service\\DataProvider\\MySQL
     */
    public $dataProvider = 'service\\DataProvider\\RedBean\\RedBean';

    /**
     * Type of database
     */
    public $dbtype = 'sqlite'; // sqlite or mysql etc.

    /**
     * Path to create database file in case of $dbtype = sqlite
     */
    public $path = '/tmp/.db/';

    /**
     * Database Host domain
     */
    public $host = 'localhost';

    /**
     * Database User
     */
    public $user = 'root';

    /**
     * Database Password
     */
    public $password = '';

    /**
     * Database name
     */
    public $database = 'fiji_webmail';

    /**
     * Optional prefix for table names
     */
    public $tablePrefix = 'fiji_';

    public function __construct()
    {
        //$this->path = sys_get_temp_dir() . '/.db/'. $this->database . '.sqlite'; // .db/ directory in temp directory eg: /tmp/.db
        // Important: This is unsafe as it is in web accessible directory. Needs to be secured if you use this or use it for development.
        $this->path = realpath(__DIR__ . '/../') . "/." . $this->database . '.sqlite'; // .db/ directory in app root directory eg: /var/www/fijiwebmail/.db/
    }

}
