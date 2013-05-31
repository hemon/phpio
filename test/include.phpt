--TEST--
Checking include
--SKIPIF--
<?php if (!extension_loaded("funcall")) print "skip"; ?>
--FILE--
<?php
touch(sys_get_temp_dir().'/funcall_include.php');
touch(sys_get_temp_dir().'/funcall_include2.php');
touch(sys_get_temp_dir().'/funcall_include3.php');
touch(sys_get_temp_dir().'/funcall_include4.php');
function include_monitor($path) {
    echo 'pre:',basename($path[0]),"\n";
}
function include_monitor_post($path,$result,$time) {
    echo 'post:',basename($path[0]),"\n";
}

$include_prefix='funcall_include';
$include_file='funcall_include.php';
$include_full_path=sys_get_temp_dir().'/funcall_include.php';
$include4_full_path=sys_get_temp_dir().'/funcall_include4.php';
function includePath() {
    return sys_get_temp_dir().'/funcall_include.php';
}

fc_add_pre('include','include_monitor');
fc_add_pre('require','include_monitor');
fc_add_pre('include_once','include_monitor');
fc_add_pre('require_once','include_monitor');
fc_add_post('include','include_monitor_post');
fc_add_post('require','include_monitor_post');
fc_add_post('include_once','include_monitor_post');
fc_add_post('require_once','include_monitor_post');
echo 'include is_var';
echo "\n";
include includePath();//is_var
echo 'include is_const';
echo "\n";
include sys_get_temp_dir().'/funcall_include.php';//is_const
echo 'include_once is_const';
echo "\n";
include_once sys_get_temp_dir().'/funcall_include2.php';//is_const
echo 'include_once is_const (not include)';
echo "\n";
include_once sys_get_temp_dir().'/funcall_include2.php';//is_const
echo 'require_once is_tmpvar';
echo "\n";
require_once sys_get_temp_dir().'/'.$include_prefix.'3.php';//is_tmpvar
echo 'require_once is_const (not include)';
echo "\n";
require_once sys_get_temp_dir().'/funcall_include3.php';//is_const
echo 'include is_tmpvar';
echo "\n";
include sys_get_temp_dir().'/'.$include_file;//is_tmpvar
echo 'include is_cv';
echo "\n";
include $include_full_path;//is_cv
echo 'require_once is_cv';
echo "\n";
require_once $include4_full_path;//is_cv
echo 'require is_const';
echo "\n";
require sys_get_temp_dir().'/funcall_include.php';//is_const
echo 'require is_tmpvar';
echo "\n";
require sys_get_temp_dir().'/'.$include_file;//is_tmpvar
echo 'require is_cv';
echo "\n";
require $include_full_path;//is_cv
echo 'require is_var';
echo "\n";
require includePath();//is_var
unlink(sys_get_temp_dir().'/funcall_include.php');
unlink(sys_get_temp_dir().'/funcall_include2.php');
unlink(sys_get_temp_dir().'/funcall_include3.php');
unlink(sys_get_temp_dir().'/funcall_include4.php');
?>
--EXPECT--
include is_var
pre:funcall_include.php
post:funcall_include.php
include is_const
pre:funcall_include.php
post:funcall_include.php
include_once is_const
pre:funcall_include2.php
post:funcall_include2.php
include_once is_const (not include)
require_once is_tmpvar
pre:funcall_include3.php
post:funcall_include3.php
require_once is_const (not include)
include is_tmpvar
pre:funcall_include.php
post:funcall_include.php
include is_cv
pre:funcall_include.php
post:funcall_include.php
require_once is_cv
pre:funcall_include4.php
post:funcall_include4.php
require is_const
pre:funcall_include.php
post:funcall_include.php
require is_tmpvar
pre:funcall_include.php
post:funcall_include.php
require is_cv
pre:funcall_include.php
post:funcall_include.php
require is_var
pre:funcall_include.php
post:funcall_include.php
