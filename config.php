<?php

$_PHPIO = array(
	'enabled' => array(
		'APC' => 1,
		'Curl' => 1,
		'Mysql' => 1,
		'Core' => 1,
		'PDO' => 1,
		'PDOStatement' => 1,
		'Redis' => 1,
		'Memcache' => 1,
		'Memcached' => 1,
		'CallUserFunc' => 1,
		'Error' => 1,
		'Exception' => 1,
		'UDF' => 1,
	),
	'log' => array(
		'class' => 'PHPIO_Log_File',
		'save_dir' => PHPIO_TMP,
	),
	'colors' => array(
		'APC' => '#BA55D3',
		'Mysql' => '#0476D6',
		'Mysql' => 'green',
		'PDO' => '#015A84',
		'PDOStatement' => '#015A84',
		'Redis' => '#3CC472',
		'Memcache' => '#9BCD9B',
		'Memcached' => '#9BCD9B',
		'Curl' => '#3CA9C4',
		'Error' => 'red',
		'Exception' => 'orange',
		'Core' => 'pink',
		'UDF' => '#999999',
	)
);
