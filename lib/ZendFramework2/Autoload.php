<?php
/**
 * Fiji Mail Server
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_Mail
 */

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

/**
 * This autoloading setup is really more complicated than it needs to be for most
 * applications. The added complexity is simply to reduce the time it takes for
 * new developers to be productive with a fresh skeleton. It allows autoloading
 * to be correctly configured, regardless of the installation method and keeps
 * the use of composer completely optional. This setup should work fine for
 * most users, however, feel free to configure autoloading however you'd like.
 */

// Composer autoloading
if (file_exists(__DIR__ . '/../../vendor/autoload.php')) {
    $loader = require __DIR__ . '/../../vendor/autoload.php';
}

// check autoloading works and we have at least Zend\Session
if (class_exists('Zend\Loader\AutoloaderFactory') && class_exists('Zend\Session\Container')) {
    return;
}

// use the configured zend framework path
use Zend\Loader\StandardAutoloader;
use Fiji\Factory;

$Config = Factory::getConfig();
$zf2Path = $Config->get('zendPath');

if (file_exists($zf2Path . '/Loader/StandardAutoloader.php')) {
    require_once $zf2Path . '/Loader/StandardAutoloader.php';
    $loader = new StandardAutoloader(array('autoregister_zf' => true));
    $loader->register();
}

// Zend framework compat
try {
    @include $zf2Path . '/Stdlib/compatibility/autoload.php';
    @include $zf2Path . '/Session/compatibility/autoload.php';
} catch(\Exception $e) { /* not necessary */ }

// check again autoloading works and we have at least Zend\Session
if (class_exists('Zend\Loader\AutoloaderFactory') && class_exists('Zend\Session\Container')) {
    return;
}

// fallback to known paths
$zf2Path = false;

if (is_dir('vendor/ZF2/library')) {
    $zf2Path = 'vendor/ZF2/library';
} elseif (getenv('ZF2_PATH')) {      // Support for ZF2_PATH environment variable or git submodule
    $zf2Path = getenv('ZF2_PATH');
} elseif (get_cfg_var('zf2_path')) { // Support for zf2_path directive value
    $zf2Path = get_cfg_var('zf2_path');
}

if ($zf2Path) {
    if (isset($loader)) {
        $loader->add('Zend', $zf2Path);
        $loader->add('ZendXml', $zf2Path);
    } else {
        include $zf2Path . '/Zend/Loader/AutoloaderFactory.php';
        Zend\Loader\AutoloaderFactory::factory(array(
            'Zend\Loader\StandardAutoloader' => array(
                'autoregister_zf' => true
            )
        ));
    }
}

// failed to load
if (!class_exists('Zend\Loader\AutoloaderFactory')) {
    throw new RuntimeException('Unable to load ZF2. Run `php composer.phar install` or define a ZF2_PATH environment variable.');
}
