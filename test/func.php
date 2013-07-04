<?php

aop_add_after('foo->bar()',function($jp){var_dump('aop_add_after',$jp->getArguments());});

$func = function($arg1, $arg2) {
    return $arg1 * $arg2;
};
call_user_func_array($func, array(2, 4)); /* As of PHP 5.3.0 */

class foo {
    function bar($arg, $arg2) {
        echo __METHOD__, " got $arg and $arg2\n";
    }
}
$foo = new foo;
call_user_func_array(array($foo, "bar"), array("three", "four"));

$method = 'bar';
$foo->$method("three","four");
