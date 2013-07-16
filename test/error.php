<?php

class ErrorHandler {
	static $callbacks = array();

	static function _errorHandler($errno, $errstr, $errfile, $errline) {
		var_dump('errorHandler:');
		foreach ( self::$callbacks as $callback ) {
			call_user_func($callback, $errno, $errstr, $errfile, $errline);
		}
	}
}

function myErrorHandler($errno, $errstr, $errfile, $errline)
{
	var_dump('myErrorHandler:'.$errstr."\n");
}

$error_handler = 'ErrorHandler::_errorHandler';
// first set
set_error_handler($error_handler);

// make set_error_handler useless
aop_add_before('set_error_handler()',function($jp){
	$args = $jp->getArguments();
	ErrorHandler::$callbacks[] = $args[0];
	$jp->setArguments(array($GLOBALS['error_handler']));
});

// other user set
set_error_handler('myErrorHandler');

// trigger error
mysql_connect();

trigger_error("log(x) for x <= 0 is undefined, you used: scale = $scale", E_USER_ERROR);
trigger_error("Incorrect input vector, array of values expected", E_USER_WARNING);
trigger_error("Value at position $pos is not a number, using 0 (zero)", E_USER_NOTICE);