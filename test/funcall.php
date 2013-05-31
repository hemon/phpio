<?php 
/*
(user|system)class
    static method(none-args or have-args)
    none-static method(none-args or have-args)

(user|system)function(none-args or have-args)

include* require*
*/
if ( !function_exists('fc_add_pre') ) dl('funcall.so');

function includePath() {
    echo "****************abc\n";
    return '../test_include.php';
}
fc_add_pre('include','pre_cb');
fc_add_post('include','post_cb');
echo "first icnlude\n";
//include  '../test_include.php';
echo "2nd icnlude\n";
include  includePath();
echo "starting ...\n";
function m1() {
    return 'm1 ret_v';
}
function m2($a,$b,$c) {
    echo "iii2\n";
    return 'm2 ret_v';
}
class testc2 {
    public function testf() {
        echo 'testf';
    }
}
function pre_cb($args) {
echo 'pre----------';
var_dump($args);
    /*if (count($args)==0) {
        echo 'zero';
    } else if (count($args)==1) {
        echo 'trim';
    } else {
        $args[2]->testf();
    }*/
}
function post_cb($args,$result,$t) {
echo 'post_cb----------';
$traces = debug_backtrace();
$func = $traces[1]['function'];
print_r(array($func, $result, $t));
    if (count($args)==0) {
        echo 'zero';
    } else if (count($args)==1) {
        echo 'trim';
    } else {
        $args[2]->testf();
    }
}
fc_add_pre('m1','pre_cb');
fc_add_pre('m2','pre_cb');
fc_add_post('m1','post_cb');
fc_add_post('m2','post_cb');

fc_add_post('trim','pre_cb');
fc_add_post('trim','post_cb');

//trim(' ok ');
$t2=new testc2;

m1();
$b=m2('abc',true,$t2);
echo 'XXXXXXXXXXXXXXX--------';
//var_dump(xdebug_get_declared_vars());
echo 'endXXXXXXXXXXXXXXX--------';
trim(' ok ');
die;
