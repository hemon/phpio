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
		setcookie(PHPIO_PROFILE.'_ID', self::$run_id);

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
		if ( isset($_REQUEST[PHPIO_PROFILE]) ) return $_REQUEST[PHPIO_PROFILE];
		if ( isset($_COOKIE[PHPIO_PROFILE])  ) return $_COOKIE[PHPIO_PROFILE];
		if ( isset($_SERVER[PHPIO_PROFILE])  ) return $_SERVER[PHPIO_PROFILE];
		return 0;
	}

	static function load($classname, $args=array()) {
		$class_path = str_replace('_', '/', $classname);
		$class_file = PHPIO_LIB. "/" . $class_path . ".php";
		if ( is_file($class_file) ) require $class_file;

		$rc = new ReflectionClass($classname);
		$class = $rc->newInstanceArgs($args);

		return $class;
	}
}
