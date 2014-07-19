<?php
/**
 * Fiji Mail Server 
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_Mail
 */

error_reporting(E_ALL & ~E_USER_DEPRECATED);
ini_set('display_errors', 0);
register_shutdown_function( "check_for_fatal" );
set_error_handler( "log_error" );
set_exception_handler( "log_exception" );

function log_debug($str)
{
    if (!is_scalar($str)) {
        $str = '<pre>' . print_r($str, 1) . '</pre>';
    }
    log_exception( new ErrorException( $str, 0, 1, 1, 1 ), "Debug" );
}

/**
 * Error handler, passes flow over the exception logger with new ErrorException.
 */
function log_error( $num, $str, $file, $line, $context = null )
{
    if ($num & error_reporting()) {
        log_exception( new ErrorException( $str, 0, $num, $file, $line ) );
    }
}

/**
 * Uncaught exception handler.
 */
function log_exception( Exception $e, $title = 'Exception Occured')
{
    print "<div style='text-align: center;'>";
    print "<h2 style='color: rgb(190, 50, 50);'>$title</h2>";
    print "<table style='width: 800px; display: inline-block;'>";
    print "<tr style='background-color:rgb(230,230,230);'><th style='width: 80px;'>Type</th><td>" . get_class( $e ) . "</td></tr>";
    print "<tr style='background-color:rgb(240,240,240);'><th>Message</th><td>{$e->getMessage()}</td></tr>";
    print "<tr style='background-color:rgb(230,230,230);'><th>File</th><td>{$e->getFile()}</td></tr>";
    print "<tr style='background-color:rgb(240,240,240);'><th>Line</th><td>{$e->getLine()}</td></tr>";
    print "<tr style='background-color:rgb(240,240,240);'><th colspan=2>Backtrace</th></tr>";
    print "<tr style='background-color:rgb(240,240,240);'><td colspan=2><pre>" . print_r(debug_backtrace(), true) . "</pre></td></tr>";
    print "</table></div>";
}

/**
 * Checks for a fatal error, work around for set_error_handler not working on fatal errors.
 */
function check_for_fatal()
{
    $error = error_get_last();
    if ( $error["type"] == E_ERROR )
        log_error( $error["type"], $error["message"], $error["file"], $error["line"] );
}