<?php
/**
 * Fiji Mail Server 
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_Mail
 */

namespace app\auth\view;

use Fiji\Factory;

/**
 * Auth View
 */
class Auth extends \Fiji\App\View
{
    
    protected $App;
    
    /**
     * Construct view
     */
    public function __construct(Application $App = null) {
        parent::__construct($App);
    }
    
    
}
