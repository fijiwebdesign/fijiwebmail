<?php
/**
 * Fiji Mail Server 
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_Mail
 */
 
use Zend\Mail\Storage\Imap;
use Fiji\Cache\File as Cache;
use Fiji\Factory;

// our application control
$App = Factory::getApplication();
$Doc = Factory::getDocument();
$Req = Factory::getRequest();
$ComposeForm = Factory::getSingleton('app\mail\form\Compose');

// we need a session
$User = Factory::getUser();
if (!$User->isAuthenticated()) {
    // set our return path and redirect to login page
    $App->setReturnUrl('?app=mail');
    $App->redirect('?app=auth');
}

// configs @todo
$Config = Factory::getSingleton('config\Mail');

// user imap configs
$options = $User->imapOptions;
if (!$options) {
    $App->redirect('?app=auth');
}

$ComposeForm->sendComposed();
