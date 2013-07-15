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
		if ( function_exists('fastcgi_finish_request') ) fastcgi_finish_request();
		ini_set("aop.enable","0");
		$this->start = false;
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
