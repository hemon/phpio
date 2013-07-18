<?php
/*
1. set_error_handler can catch errors except:
E_ERROR, E_PARSE, E_CORE_ERROR, E_CORE_WARNING, E_COMPILE_ERROR, E_COMPILE_WARNING, and most of E_STRICT
2. Luckly in [register_shutdown_function] callback,we can use [error_get_last] can catch all type of Error
final, i defined a callback in Log->stop() to call PHPIO_Hook_Error->_error_handler();
3. set_error_handler could be re-register in user script
ignore the follow-up set_error_handler call by reset the args to my error_handler
*/
class PHPIO_Hook_Error extends PHPIO_Hook_Func {
	const classname = 'Error';
	var $hooks = array(
		'set_error_handler',
    );
    var $my_handler = null;
    var $other_handler = null;
	var $ERROR = array(
		1 => 'E_ERROR',
		2 => 'E_WARNING',
		4 => 'E_PARSE',
		8 => 'E_NOTICE',
		16 => 'E_CORE_ERROR',
		32 => 'E_CORE_WARNING',
		64 => 'E_COMPILE_ERROR',
		128 => 'E_COMPILE_WARNING',
		256 => 'E_USER_ERROR',
		512 => 'E_USER_WARNING',
		1024 => 'E_USER_NOTICE',
		2048 => 'E_STRICT',
		4096 => 'E_RECOVERABLE_ERROR',
		8192 => 'E_DEPRECATED',
		16384 => 'E_USER_DEPRECATED',
		30719 => 'E_ALL'
	);

    function __construct() {
    	$this->my_handler = array($this, 'error_handler');
    	set_error_handler($this->my_handler);
    }

    // ignore the follow-up set_error_handler call
    // by reset the args to my error_handler
    function _preCallback($jp, $traces=array()) {
		$args = $jp->getArguments();
		$jp->setArguments(array($this->my_handler));
    }

    function _postCallback($jp) {
    	if ( $jp->getReturnedValue() === true ) {
    		$args = $jp->getArguments();
    		$this->other_handler = $args[0];
    	}
    }

    function error_handler($errno, $error, $file, $line){
    	$trace = $this->getPrintTrace(debug_backtrace());
    	$this->_error_handler($errno, $error, $file, $line, $trace);
    	if ( $this->other_handler !== null ) {
    		call_user_func($this->other_handler, $errno, $error, $file, $line);
    	}
    }

    function _error_handler($errno, $error, $file, $line, $trace = array()) {
    	// append last error message to trace log
    	array_push($trace, sprintf('%s: %s at [%s:%d]', $this->ERROR[$errno], $error, $file, $line));

    	$log = compact('errno','error','file','line','trace');
		$log['classname'] = 'Error';
		$log['function'] = $this->ERROR[$errno];
		$log['cmd'] = $error;

		PHPIO::$log->append($log);
    }
}
