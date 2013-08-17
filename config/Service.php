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
 * Base Config
 */
class Service extends \Fiji\App\Config
{
    
    public $dataProvider = 'service\\DataProvider\\RedBean\\RedBean';
    
    public $host = 'localhost';
    public $user = 'root';
    public $password = '';
    public $database = 'fiji_webmail2';
    public $tablePrefix = 'fiji_';
    
    public function __construct($options = array())
    {
        parent::__construct($options);
        
        // @todo remove in production
        if (in_array($_SERVER['HTTP_HOST'], array('fijiwebdesign.com', 'fijisoftware'))) {
            $this->password = 'vlg4lyfe';
            $this->database = 'fiji_cloudmail';
        }
        
    }
    
}



