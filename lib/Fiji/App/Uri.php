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
    protected $Req;

    /**
    * @var String
    */
    protected $base;
    
    
    public function __construct() {
        $this->Req = Factory::getRequest();
    }

    public function setBase($url)
    {
        $this->base = $url;
    }
    
    public function getBase()
    {
        if (!$this->base) {
            $this->base = str_replace('index.php', '', $_SERVER['PHP_SELF']);
        }
        return $this->base;
    }
    
}
