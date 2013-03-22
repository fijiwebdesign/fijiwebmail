<?php

use Zend\Loader\StandardAutoloader;
use Fiji\Factory;

// autoloading zf2 classes
require_once '/var/lib/zf2/library/Zend/Loader/StandardAutoloader.php';
$loader = new StandardAutoloader(array('autoregister_zf' => true));
$loader->register();

// Zend framework compat
require '/var/lib/zf2/library/Zend/Stdlib/compatibility/autoload.php';
require '/var/lib/zf2/library/Zend/Session/compatibility/autoload.php';

// autoloading Fiji classes
require_once 'lib/Autoload.php';

// get the document 
$Doc = Factory::getSingleton('Fiji\App\Document');
$User = Factory::getSingleton('Fiji\App\User');
$App = Factory::getSingleton('Fiji\App\Application');
$Req = Factory::getSingleton('Fiji\App\Request');


