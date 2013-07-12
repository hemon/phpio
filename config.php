<?php
set_time_limit(0);
error_reporting(E_ALL ^ E_NOTICE);

define('ROOT', __DIR__);
define('STORE', '/tmp/phpio/');
define('LIB', ROOT.'/library/');
define('FUNC', ROOT.'/functions/');

require FUNC.'/common.func.php';
spl_autoload_register('__phpio_loader');
