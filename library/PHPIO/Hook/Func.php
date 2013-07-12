<?php

abstract class PHPIO_Hook_Func extends PHPIO_Hook_Class {
	const classname = '';
	var $log = array();
	var $hooks = array();
	
	function getFunctions() {
		return get_extension_funcs($this::classname);
	}

	function getLink($args) {
		$link = $args[ count($args)-1 ];
		$link = (is_resource($link) ? $link : $this->link);

		return $link;
	}

	function getHookFunc($func) {
		return $func . '()';
	}
}
