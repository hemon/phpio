<?php

//aop_add_after('Exception->*()',function($jp){var_dump('Exception',$jp->getArguments(),$jp->getMethodName(),$jp->getObject()->getTraceAsString(),'Exception-END');});
//aop_add_after('RedisException->*()',function($jp){var_dump('RedisException',$jp->getArguments());});

//function exception_handler($exception) {var_dump('exception_handler',$exception->getTraceAsString(),'exception_handler_end');}
//set_exception_handler('exception_handler');
// make set_error_handler useless

// 1.ignore Exception; can't hook!
try {
    $redis = new Redis();
    $redis->connect('192.168.1.1',6379,1);
} catch(Exception $e) {
	// don't call any Exception method
}

// 2.call any Exception method in catch
try {
    $redis = new Redis();
    $redis->connect('192.168.1.1',6379,1);
} catch(Exception $e) {
	$e->getMessage();
}

// 3. throw without catch
class DivideByZeroException extends Exception {}
//Throw new DivideByZeroException("DivideByZeroException1");

// 4. throw with exception handler
// can NOT hook a exception_handler

class ExceptionHandler {
	static $callback = array();

	static function _exceptionHandler($e) {
		var_dump('ExceptionHandler');
		call_user_func(self::$callback, $e);
	}
}

function exception_handler($exception) {
	var_dump('exception_handler',$exception->getTraceAsString(),'exception_handler_end');
}
set_exception_handler('ExceptionHandler::_exceptionHandler');

aop_add_before('set_exception_handler()',function($jp){
	$args = $jp->getArguments();
	ExceptionHandler::$callback = $args[0];
	$jp->setArguments(array('ExceptionHandler::_exceptionHandler'));
});

set_exception_handler('exception_handler');
//Throw new DivideByZeroException("DivideByZeroException3");
$redis->connect('192.168.1.1',6379,1);
$redis->ping();
