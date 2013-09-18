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
 * Base Config
 */
class Service extends Config
{
    
    public $dataProvider = 'service\\DataProvider\\RedBean\\RedBean';
    
    public $host = 'localhost';
    public $user = 'root';
    public $password = '';
    public $database = 'fiji_webmail2';
    public $tablePrefix = 'fiji_';
    
}



