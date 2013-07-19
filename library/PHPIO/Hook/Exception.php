<?php
/*
1. throw in php script
   hook the Exception->__construct and ignore the rest of the method calls;
2. throw in Extension
   can't hook Exception->__construct(), but hook first Exception->*() call in catch block.
3. set default exception handler
   the exception handler function can't be hooked, it could be re-register in user script.
*/
class PHPIO_Hook_Exception extends PHPIO_Hook {
	const classname = 'Exception';
	var $exceptions = array();
    var $exceptionHandler = null;
    var $otherHandler = null;
	var $hooks = array(
		'*',
    );

    function __construct() {
    	$this->exceptionHandler = array($this, 'exceptionHandler');
    	set_exception_handler($this->exceptionHandler);
    	aop_add_before('set_exception_handler()', array($this, 'set_exception_handler_pre'));
    }

    function _preCallback($jp, $traces=array()){
    }

    function _postCallback($jp){
    	$exception = $jp->getObject();
    	if ( $this->isProcessed($exception) ) return;

		$method = $jp->getMethodName();
		if ( $method === '__construct' ) {
			$this->__construct_post($jp);
		} else {
			$this->traceLog($exception);
		}
    }

    function __construct_post($jp) {
		$traces = debug_backtrace();
		$trace = $traces[2];
		$args = $jp->getArguments();
		if(isset($args[0])) $trace['cmd'] = $trace['error'] = $args[0];
		if(isset($args[1])) $trace['errno'] = $args[1];
		$trace['trace']  = $this->getPrintTrace($traces,2);
		$trace['classname'] = $this::classname;
		unset($trace['class']);
		$trace['function'] = get_class($jp->getObject());

		PHPIO::$log->append($trace);
    }

    function traceLog($exception) {
		$traces = $exception->getTrace();
		$trace = $traces[0];
		$trace['errno'] = $exception->getCode();
		$trace['error'] = $trace['cmd'] = $exception->getMessage();
		$trace['file'] = $exception->getFile();
		$trace['trace'] = $this->getPrintTrace($traces,0);
		$trace['classname'] = $this::classname;
		unset($trace['class']);
		$trace['function'] = get_class($exception);

		PHPIO::$log->append($trace);
    }

    function exceptionHandler($exception) {
    	if ( !$this->isProcessed($exception) ) {
    		$this->traceLog($exception);
    	}

		if ( $this->otherHandler ) {
			call_user_func($this->otherHandler, $exception);
		}
    }

    function set_exception_handler_pre($jp) {
		$args = $jp->getArguments();
		$this->otherHandler = $args[0];
		$jp->setArguments(array($this->exceptionHandler));
    }

    function isProcessed($exception) {
    	$exception_id = $this->getObjectId($exception);
		if ( isset($this->exceptions[$exception_id]) ) {
			return true;
		} else {
			$this->exceptions[$exception_id] = $exception;
			return false;
		}
    }
}
