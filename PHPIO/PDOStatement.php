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

	function postCallback($args, $traces, $result) {
		if ( is_object($this->object) ) {
			if ( $result ) {
				$traces['rowcount'] = $this->object->rowCount();
			} else {
				list(,$errno, $error) = $this->object->errorInfo();
				$traces['errno'] = $errno;
				$traces['error'] = $error;
			}
		}
		parent::postCallback($args, $traces, $result);
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