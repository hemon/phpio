<?php
abstract class PHPIO_Hook {
	const classname = '';
	var $hooks = array();
	var $jp = null;
	var $args = array();
	var $trace = array();
	var $object = null;
	var $link = null;
	var $links = array();
	var $time_start = 0;

	function _preCallback($jp, $traces=array()) {
		$this->object = $jp->getObject();
	    $this->args = $jp->getArguments();
	    $this->time_start = microtime(true);

	    $traces = (!empty($traces) ? $traces : debug_backtrace());
	    $trace = $traces[1];
		$trace['trace']  = $this->getPrintTrace($traces);
		if ( isset($trace['object']) ) $trace['object'] = $this->getObjectId($trace['object']);
		$trace['time_start'] = $this->time_start;
		$trace['classname']  = $this::classname;

	    $this->trace = $trace;
	    $this->func =  $trace['function'];

		$callback = (method_exists($this, "{$this->func}_pre") ? "{$this->func}_pre" : "preCallback");
		$this->$callback($jp);
	}

	function _postCallback($jp) {
	    $this->result = $jp->getReturnedValue();
		$this->trace['result'] = $this->dump($this->result);
		$this->trace['time_end'] = microtime(true);

		$callback = (method_exists($this, "{$this->func}_post") ? "{$this->func}_post" : "postCallback");
		$this->$callback($jp);
	}
	
	function preCallback($jp) {
	}
	
	function postCallback($jp) {
		PHPIO::$log->append($this->trace);
	}
	
	function getPrintTrace($traces, $max_level=1) {
		//0  c() called at [/tmp/include.php:10]
		$trace_len = count($traces)-1;
		$printTraces = array();
		for ($i = $trace_len; $i >= $max_level; $i--) {
			if ( isset($traces[$i]['class']) ) {
				$traces[$i]['function'] = $traces[$i]['class'] .'->'.$traces[$i]['function'];
			}
			// set default value avoid Undefined Index error
			$traces[$i] += array('function'=>'','file'=>'','line'=>0);
			$printTraces[] = sprintf("%s() called at [%s:%d]", $traces[$i]['function'],$traces[$i]['file'],$traces[$i]['line']);
		}
		return $printTraces;
	}

	function dump($var) {
		ob_start();
        var_dump($var);
		return ob_get_clean();
	}
	
	function getObjectId($var) {
		$dump = $this->dump($var);
		if ( is_object($var) ) {
			if ( preg_match('|object\((.*?)\)#(\d+)|', $dump, $match) ) {
				return $match[0];
			}
		}

		return $dump;
	}
	
	function init() {
		//$this->log = $log;
		$this->hooks = $this->getHooks();
		$this->hook();
		
		return $this;
	}
	
	function getHooks() {
		$hooks = array();
		$hooks_file = __DIR__.'/'.$this::classname.'.hooks';
		if ( file_exists($hooks_file) ) {
			$hooks = file($hooks_file, FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES);
		} elseif ( !empty($this->hooks) ) {
			array_walk($this->hooks,array($this, 'replaceClassPrefix'));
            $hooks = $this->hooks;
		} else {
			$hooks = $this->getFunctions();
		}
		return $hooks;
	}
    
    function replaceClassPrefix(&$method) {
        $method = str_replace($this::classname.'::', '', $method);
    }
	
	function getFunctions() {
		return get_class_methods($this::classname);
	}
	
	function hook() {
		if ($this->hooks) foreach ( $this->hooks as $func ) {
			$function = $this->getHookFunc($func);
			aop_add_before($function, array($this, '_preCallback'));
			aop_add_after($function, array($this, '_postCallback'));
		}
	}
	
	function getHookFunc($func) {
		return $this::classname . '->' . $func . '()';
	}
}

abstract class PHPIO_Hook_Func extends PHPIO_Hook {
	const classname = '';
	var $log = array();
	var $hooks = array();
	
	function getFunctions() {
		return get_extension_funcs($this::classname);
	}

	function getHookFunc($func) {
		return $func . '()';
	}
}
