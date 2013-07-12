<?php

class PHPIO {
	static $available = array(
		'APC' => 1,
		'Curl' => 1,
		'Mysql' => 1,
		'PDO' => 1,
		'PDOStatement' => 1,
		'Redis' => 1,
		'Memcache' => 1,
		'Memcached' => 1,
		'CallUserFunc' => 1,
	);
	static $run_id;
	static $hooks = array();
	static $log_class = 'PHPIO_Log_File';
	static $log;
	static $links = array();

	static function hook() {
		$run_id = uniqid();
		self::$run_id = (self::requestId() > 1 ?  self::requestId().'.'.$run_id : $run_id);
		setcookie('XDEBUG_PROFILE_ID', self::$run_id);

		self::$log = new self::$log_class();
		self::$log->append(array('_SERVER'=>$_SERVER,'_GET'=>$_GET,'_POST'=>$_POST));

		foreach ( self::$available as $hook => $enabled ) {
			if ( !$enabled ) {
				continue;
			}

			$phpio_hook = "PHPIO_$hook";
			self::$hooks[$hook] = new $phpio_hook;
			self::$hooks[$hook]->init();
		}

		register_shutdown_function(array(self::$log, 'save'));
	}

	static function requestId() {
		if ( isset($_REQUEST['XDEBUG_PROFILE']) ) return $_REQUEST['XDEBUG_PROFILE'];
		if ( isset($_COOKIE['XDEBUG_PROFILE'])  ) return $_COOKIE['XDEBUG_PROFILE'];
		if ( isset($_SERVER['XDEBUG_PROFILE'])  ) return $_SERVER['XDEBUG_PROFILE'];
		return 0;
	}
}
