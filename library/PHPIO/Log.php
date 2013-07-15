<?php

abstract class PHPIO_Log {
	var $save_dir = '';
	var $logs = array();
	var $start = true;

	function append($value) {
		if ($this->start) {
			$this->logs[] = $value;
		}
	}

	function count() {
		return count($this->logs);
	}

	function stop() {
		if ( function_exists('fastcgi_finish_request') ) {
			fastcgi_finish_request();
		}

		ini_set("aop.enable","0");

		$last_error = error_get_last();
		if ( is_array($last_error) ) {
			$this->errorLog($last_error);
		}
	
		$this->start = false;
	}
	
	function errorLog($error) {
		$ERROR_TYPE = array(
			1 => 'E_ERROR',
			2 => 'E_WARNING',
			4 => 'E_PARSE',
			8 => 'E_NOTICE',
			16 => 'E_CORE_ERROR',
			32 => 'E_CORE_WARNING',
			64 => 'E_COMPILE_ERROR',
			128 => 'E_COMPILE_WARNING',
			256 => 'E_USER_ERROR',
			512 => 'E_USER_WARNING',
			1024 => 'E_USER_NOTICE',
			2048 => 'E_STRICT',
			4096 => 'E_RECOVERABLE_ERROR',
			8192 => 'E_DEPRECATED',
			16384 => 'E_USER_DEPRECATED',
			30719 => 'E_ALL'
		);

		$error['classname'] = 'Error';
		$error['trace'][] = sprintf('%s: %s at [%s:%d]',$ERROR_TYPE[$error['type']],$error['message'],$error['file'],$error['line']);
		$error['function'] = $ERROR_TYPE[$error['type']];
		$error['cmd'] = $error['message'];
		$this->append($error);
	}

	function getURI($info) {
		return isset($info['DOCUMENT_URI']) ? $info['DOCUMENT_URI'] : $info['SCRIPT_NAME'];
	}

	function save() {}
	function getProfiles() {}
	function getProfile($profile_id) {}
	function getSource($file) {}
	function getFlow($root_profile_id){}
	function getCurlHeader($curl_id){}

}
