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
		$traces[1]['link'] = PHPIO::$links[$this->getObjectId($this->object)];
		if ( $result ) {
			$traces[1]['rowcount'] = $this->object->rowCount();
		} else {
			list(,$errno, $error) = $this->object->errorInfo();
			$traces[1]['errno'] = $errno;
			$traces[1]['error'] = $error;
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
