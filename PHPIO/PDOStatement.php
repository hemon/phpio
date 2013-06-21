<?php

class PHPIO_PDOStatement extends PHPIO_Hook_Class {
	const classname = 'PDOStatement';
	var $hooks = array(
        'execute',
    );

    function preCallback($args, $traces) {
    	$traces[1]['cmd'] = $this->queryString();
		parent::preCallback($args, $traces);
	}

	function debugDump() {
		ob_start();
		$this->object->debugDumpParams();
		return ob_get_clean();
	}

	function queryString() {
		return $this->object->queryString;
	}
}