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

// @todo handle from router
$View = Factory::getSingleton('app\\mail\\view\\Mail');

$page = Factory::getRequest()->getAlphaNum('page', 'mailbox');

try {
    $Controller = Factory::getSingleton('app\\mail\\controller\\' . $page, array($View));
} catch (\Exception $e) {
    Factory::getErrorHandler()->catchException($e);
}