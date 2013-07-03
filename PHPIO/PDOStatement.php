<?php

class PHPIO_PDOStatement extends PHPIO_Hook_Class {
	const classname = 'PDOStatement';
	var $hooks = array(
        'execute',
    );

	function postCallback($jp) {
		$this->trace['link'] = PHPIO::$links[$this->getObjectId($this->object)];
		$this->trace['cmd'] = $this->queryString();
		if ( $result ) {
			$this->trace['rowcount'] = $this->object->rowCount();
		} else {
			list(,$errno, $error) = $this->object->errorInfo();
			$this->trace['errno'] = $errno;
			$this->trace['error'] = $error;
		}
		parent::postCallback($jp);
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
