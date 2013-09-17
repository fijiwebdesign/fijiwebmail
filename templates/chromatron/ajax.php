<?php 
/**
 * Fiji Mail Server 
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_Mail
 */

$contentType = $Req->get('contentType');
if ($contentType == 'json') {
	header('Content-Type: application/json');
}

echo $Doc->content;
