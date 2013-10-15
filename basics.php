<?php

/**
 * Autoloader
 * @param $class_name
 * @throws Exception
 */
function __autoload($class_name) {
    if (class_exists($class_name, false) || interface_exists($class_name, false))
        return;

    $class_parts = explode("_",$class_name);
    $class_path = "classes/" . strtolower(implode("/", $class_parts));
    include $class_path.".php";

    if (!class_exists($class_name, false) && !interface_exists($class_name, false))
         throw new Exception('Class ' . $class_name . ' not found');
}

/**
 * Error handler, passes flow over the exception logger with new ErrorException.
 */
function log_error( $num, $str, $file, $line, $context = null )
{
    log_exception( new ErrorException( $str, 0, $num, $file, $line ) );
}

/**
 * Uncaught exception handler.
 */
function log_exception( Exception $e ) {
    print "<div style='text-align: center;'>";
    print "<h2 style='color: rgb(190, 50, 50);'>Exception Occured:</h2>";
    print "<table style='width: 800px; display: inline-block;'>";
    print "<tr style='background-color:rgb(230,230,230);'><th style='width: 80px;'>Type</th><td>" . get_class( $e ) . "</td></tr>";
    print "<tr style='background-color:rgb(240,240,240);'><th>Message</th><td>{$e->getMessage()}</td></tr>";
    print "<tr style='background-color:rgb(230,230,230);'><th>File</th><td>{$e->getFile()}</td></tr>";
    print "<tr style='background-color:rgb(240,240,240);'><th>Line</th><td>{$e->getLine()}</td></tr>";
    print "</table></div>";

    exit();
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

register_shutdown_function( "check_for_fatal" );
set_error_handler( "log_error" );
set_exception_handler( "log_exception" );
ini_set( "display_errors", "on" );
error_reporting( E_ALL );