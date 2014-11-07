<?php

/**
 * Autoload Fiji classes
 *
 * @param string $className
 */
spl_autoload_register( function( $className )
{

    if ($className == 'config\Service') {
        require __DIR__ . '/Service.php';
    }

});

// Fiji Application autoload
require __DIR__ . '/../../lib/Autoload.php';

// @todo Autoload individually mocked
require __DIR__ . '/Mocks.php';
