<?php
set_time_limit(0);
error_reporting(E_ALL ^ E_NOTICE);

define('PHPIO_ROOT' , __DIR__);
define('PHPIO_LIB'  , PHPIO_ROOT.'/library/');
define('PHPIO_FUNC' , PHPIO_ROOT.'/functions/');
define('PHPIO_TMP' , '/tmp/phpio/');

if ( !file_exists(PHPIO_TMP) ) {
	mkdir(PHPIO_TMP, 0, true);
}

$_PHPIO = array(
	'enabled' => array(
		'APC' => 1,
		'Curl' => 1,
		'Mysql' => 1,
		'PDO' => 1,
		'PDOStatement' => 1,
		'Redis' => 1,
		'Memcache' => 1,
		'Memcached' => 1,
		'CallUserFunc' => 1,
	),
	'log' => array(
		'class' => 'PHPIO_Log_Redis',
		'save_dir' => PHPIO_TMP,
	)
);

require PHPIO_FUNC.'/common.func.php';
spl_autoload_register('phpio_loader');

foreach ( $_PHPIO as $_name => $_value ) {
	if ( isset($_value['class']) ) {
		PHPIO::$$_name = new $_value['class'];
		phpio_class_set_properties(PHPIO::$$_name, $_value);
	} else {
		PHPIO::$$_name = $_value;
	}
}
