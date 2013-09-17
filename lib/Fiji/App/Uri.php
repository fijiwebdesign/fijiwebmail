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
 * URI information
 */
class Uri {
    
    /**
     * @var Fiji\App\Request
     */
    public $Req;
    
    
    public function __construct() {
        $this->Req = Factory::getRequest();
    }
    
    public function getBase()
    {
        return str_replace('index.php', '', $_SERVER['PHP_SELF']);
    }
    
}
