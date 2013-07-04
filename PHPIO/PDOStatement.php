<?php

class PHPIO_PDOStatement extends PHPIO_Hook_Class {
	const classname = 'PDOStatement';
	var $hooks = array(
        'execute',
        'bindParam',
        'bindValue',
    );
    var $params = array();
	function postCallback($jp) {
		$object_id = $this->getObjectId($this->object);
		$this->trace['classname'] = 'PDO';
		$this->trace['link'] = PHPIO_PDO::$statements[$object_id];
		$this->trace['cmd'] = $this->queryString();
		$this->trace['args'] = (isset($this->params[$object_id]) ? $this->params[$object_id] : null);
		if ( $this->result !== false ) {
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
		$object_id = $this->getObjectId($this->object);
		$queryString = $this->object->queryString;

		// Translate SQL parmas ?,? To :1,:2
		$queryStrings = explode('?', $queryString);
		if ( count($queryStrings) > 1 ) {
			$queryString = '';
			foreach ($queryStrings as $i => $query) {
				$queryString .= $query.':'.$i+1;
			}
		}
		// Prepare params array for [strtr] function
		// 1. replace numeric key(1,2,3) to string key(:1,:2,:3)
		// 2. use [var_export] quote string value
		if ( isset($this->params[$object_id]) && !empty($this->params[$object_id]) ) {
			$params = array();
			foreach ( $this->params[$object_id] as $parameter => $value ) {
				$value = var_export($value, true);
				if ( is_numeric($parameter) ) {
					$parameter = ':'.$parameter;
				}
				$params[$parameter] = $value;
			}
			$queryString = strtr($queryString, $params);
		}
		return $queryString;
	}

	function bindParam_post($jp) {
		$object_id = $this->getObjectId($this->object);
		$parameter = $this->args[0];
		$value = $this->args[1];
		$this->params[$object_id][$parameter] = $value;
	}

	function bindValue_post($jp) {
		$this->bindParam_post($jp);
	}
}
