<?php
/**
 * Fiji Mail Server 
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_Mail
 */

use Fiji\Factory;
 
require __DIR__ . '/controller/Auth.php';

// @todo handle from router
$View = Factory::getSingleton('app\\auth\\view\\Auth');
$Controller = new app\controller\Auth($View);
