<?php

class PHPIO {
	static $enabled = array();
	static $run_id;
	static $hooks = array();
	static $log;
	static $links = array();

	static function hook() {
		$run_id = uniqid();
		self::$run_id = (self::requestId() > 1 ?  self::requestId().'.'.$run_id : $run_id);
		setcookie('XDEBUG_PROFILE_ID', self::$run_id);

		self::$log->append(array('_SERVER'=>$_SERVER,'_GET'=>$_GET,'_POST'=>$_POST));
		foreach ( self::$enabled as $hook => $enabled ) {
			if ( !$enabled ) {
				continue;
			}

			$hook_class = "PHPIO_Hook_$hook";
			self::$hooks[$hook] = new $hook_class;
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
