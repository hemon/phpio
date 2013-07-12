<?php

abstract class PHPIO_Hook_Func extends PHPIO_Hook_Class {
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
