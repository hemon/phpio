<?php

class PHPIO {
	static $available = array(
		'Curl' => 1,
		'Mysql' => 1,
		'PDO' => 1,
		'PDOStatement' => 1,
		'Redis' => 1,
		'Memcache' => 1,
		'Memcached' => 1,
	);
	static $run_id;
	static $hooks = array();
	static $log_class = 'PHPIO_Log_File';
	static $log;
	static $links = array();
	
	static function hook() {
		self::$run_id = uniqid();
		self::$log = new self::$log_class();
		self::$log->append(array('_SERVER'=>$_SERVER,'_GET'=>$_GET,'_POST'=>$_POST));
	
		require __DIR__."/PHPIO/Hook.php";
		foreach ( self::$available as $hook => $enabled ) {
			if ( !$enabled ) {
				continue;
			}
			
			require __DIR__."/PHPIO/$hook.php";
			$phpio_class_name = "PHPIO_$hook";
			self::$hooks[$hook] = new $phpio_class_name;
			self::$hooks[$hook]->init();
		}
		
		register_shutdown_function(array(self::$log, 'save'));
	}
}

class PHPIO_Log_File {
	var $save_dir = '/tmp/phpio';
	var $logs = array();
	function append($value) {
		$this->logs[] = $value;
	}
	
	function count() {
		return count($this->logs);
	}
	function save() {
		if ( !file_exists($this->save_dir) ) {
			mkdir($this->save_dir);
		}
		
		if ( $this->count() > 0 ) {
			//echo '<pre>',var_dump($this);
			file_put_contents($this->save_dir.'/'.PHPIO::$run_id, serialize($this->logs));
		}
	}
}


PHPIO::hook();