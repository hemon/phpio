<?php
set_time_limit(0);
error_reporting(E_ALL ^ E_NOTICE);

define('PHPIO_ROOT', __DIR__);
define('PHPIO_STORE', '/tmp/phpio/');
define('PHPIO_LIB', PHPIO_ROOT.'/library/');
define('PHPIO_FUNC', PHPIO_ROOT.'/functions/');

require PHPIO_FUNC.'/common.func.php';
spl_autoload_register('__phpio_loader');
