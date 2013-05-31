--TEST--
Checking functions
--SKIPIF--
<?php if (!extension_loaded("funcall")) print "skip"; ?>
--FILE--
<?php
function m1() {
    return 0;
}
function m2($a,$b,$c) {
    return 2;
}
class testc2 {
    public function testf() {
        echo 'testf';
    }
}
function pre_cb($args) {
    if (count($args)==0) {
        echo 'zero';
    } else if (count($args)==1) {
        echo 'trim';
    } else {
        $args[2]->testf();
    }
}
function post_cb($args,$result,$t) {
    if (count($args)==0) {
        echo 'zero';
    } else if (count($args)==1) {
        echo 'trim';
    } else {
        $args[2]->testf();
    }
    echo $result;
}
fc_add_pre('m1','pre_cb');
fc_add_pre('m2','pre_cb');
fc_add_post('m1','post_cb');
fc_add_post('m2','post_cb');

fc_add_post('trim','pre_cb');
fc_add_post('trim','post_cb');

$t2=new testc2;

$ret=m1();
$ret=m2('abc',true,$t2);
$ret=trim(' ok ');
?>
--EXPECT--
zerozero0testftestf2trimtrimok
