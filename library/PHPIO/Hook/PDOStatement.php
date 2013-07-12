<?php

class PHPIO_Hook_PDOStatement extends PHPIO_Hook_Class {
	const classname = 'PDOStatement';
	var $hooks = array(
        'execute',
        'bindParam',
        'bindValue',
    );
    var $bindParams = array();
    var $inputParams = array();
    var $object_id = 0;
    var $PDO = null;

    function execute_pre($jp) {
    	$this->object_id = $this->getObjectId($this->object);
    	$this->inputParams = $this->getInputParams();
    	$this->PDO = $this->getPDO();
    	$this->link = $this->getLink();
    }

	function execute_post($jp) {
		$this->trace['classname'] = 'PDO';
		$this->trace['link'] = $this->link;
		$this->trace['cmd'] = $this->queryString($this->inputParams);
		$this->trace['args'] = $this->inputParams;

		if ( $this->result === true ) {
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

	function queryString($inputParams) {
		$queryString = $this->object->queryString;
		if ( empty($inputParams) ) {
			return $queryString;
		}
		// Translate SQL parmas ?,? To :1,:2
		$queryStrings = explode('?', $queryString);
		$argc = count($queryStrings)-1;
		if ( $argc > 0 ) {
			$queryString = '';
			foreach ($queryStrings as $i => $query) {
				$quote = ( ($i < $argc) ? ':'.($i+1) : '');
				$queryString .= $query . $quote;
			}
		}
		// Prepare params array for [strtr] function
		// 1. replace numeric key(1,2,3) to string key(:1,:2,:3)
		// 2. use [var_export] quote string value
		$bindParams = array();
		foreach ( $inputParams as $parameter => $value ) {
			$value     = $this->quote($value); // quote
			$parameter = ( is_numeric($parameter) ? ':'.$parameter : $parameter );
			$bindParams[$parameter] = $value;
		}
		$queryString = strtr($queryString, $bindParams);

		return $queryString;
	}

	function bindParam_post($jp) {
		$object_id = $this->getObjectId($this->object);
		$parameter = $this->args[0];
		$value = $this->args[1];
		$this->bindParams[$object_id][$parameter] = $value;
	}

	function bindValue_post($jp) {
		$this->bindParam_post($jp);
	}

	function getInputParams() {
		if ( isset($this->args[0]) ) {
			return $this->args[0];
		}

		if ( isset($this->bindParams[$this->object_id]) ) {
			return $this->bindParams[$this->object_id];
		}

		return array();
	}

	function quote($value) {
		return $this->PDO->quote($value);
	}

	function getLink() {
		return PHPIO::$hooks['PDO']->getLink( $this->PDO );
	}

	function getPDO() {
		return PHPIO_PDO::$statements[$this->object_id];
	}
}
