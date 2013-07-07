<?php

//aop_add_after('foo->bar()',function($jp){var_dump('aop_add_after',$jp->getArguments());});

$func = function($arg1, $arg2) {
    return $arg1 * $arg2;
};
call_user_func_array($func, array(2, 4)); /* As of PHP 5.3.0 */

class foo {
    function bar($arg, $arg2) {
        echo __METHOD__, " got $arg and $arg2\n";
    }

    function hook() {
        aop_add_after('foo->bar()', array($this, 'dump'));
    }

    function dump($jp) {
        var_dump('class:aop_add_after',$jp->getArguments());
    }
}

class boo {
    function __construct($class) {
        $this->class = $class;
    }
    function __call($name, $args) {
	return call_user_func_array(array($this->class,$name), $args);
    }
}

$foo = new foo;
$foo->hook();


call_user_func_array(array($foo, "bar"), array("call_user_func_array", "four"));

$method = 'bar';
$foo->$method("\$method","four");

$boo = new boo($foo);
$boo->bar("__call","four");
