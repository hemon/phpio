--TEST--
Checking methods
--SKIPIF--
<?php if (!extension_loaded("funcall")) print "skip"; ?>
--FILE--
<?php
class testc1 {
    static public function sm1() {
        return 0;
    }
    static public function sm2($a,$b,$c) {
        return 2;
    }
    public function m1() {
        return 0;
    }
    public function m2($a,$b,$c) {
        return 3;
    }
}
class testc2 {
    public function testf() {
        echo 'testf';
    }
}
function pre_cb($args) {
    if (count($args)==0) {
        echo 'zero';
    } else {
        $args[2]->testf();
    }
}
function post_cb($args,$result,$t) {
    if (count($args)==0) {
        echo 'zero'.$result;
    } else {
        $args[2]->testf();
    }
}
fc_add_pre('testc1::m1','pre_cb');
fc_add_pre('testc1::m2','pre_cb');
fc_add_pre('testc1::sm1','pre_cb');
fc_add_pre('testc1::sm2','pre_cb');
fc_add_post('testc1::m1','post_cb');
fc_add_post('testc1::m2','post_cb');
fc_add_post('testc1::sm1','post_cb');
fc_add_post('testc1::sm2','post_cb');

$t2=new testc2;

$t1=new testc1;
$r=$t1->m1();
$r=$t1->m2('abc',true,$t2);
$r=testc1::sm1();
$r=$t1->m2('abc',true,$t2);
$r=$t1->m2('abc',true,$t2);
$r=testc1::sm2('abc',true,$t2);
?>
--EXPECT--
zerozero0testftestfzerozero0testftestftestftestftestftestf
