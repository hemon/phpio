<?php

abstract class PHPIO_Log {
	var $save_dir = '';
	var $logs = array();
	var $start = true;
	var $processor = array();

	function save() {
		$this->stop();
		foreach ($this->processor as $processor) {
			$this->$processor();
		}
	}

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
			//fastcgi_finish_request();
		}

		ini_set("aop.enable","0");

		$last_error = error_get_last();
		if ( is_array($last_error) ) {
			call_user_func_array(array(PHPIO::$hooks['Error'], '_error_handler'), $last_error);
		}
	
		//$this->start = false;
	}

	function getURI($info) {
		if ( isset($info['HTTP_HOST']) ) {
			$uri = $info['HTTP_HOST'];
			if ( $info['SERVER_PORT'] != '80' ) {
				$uri .= ':'.$info['SERVER_PORT'];
			}
			list($path, ) = explode('?', $info['REQUEST_URI']);
			return $uri . $path;
		} else {
			return $info['SCRIPT_NAME'];
		}
	}

	function getProfileUri($profile_ids) {
		$profile_uri = array();
		if ($profile_ids) foreach ($profile_ids as $profile_id) {
			$profile = $this->getProfile($profile_id);
			$profile_uri[$profile_id] = $this->getURI($profile[0]['_SERVER']);
		}
		return $profile_uri;
	}

	function saveArgnames() {
		foreach ( $this->logs as &$log ) {
			if ( isset($log['args']) && !in_array($log['classname'],array('Exception','Error')) ) {
				$function = ( isset($log['class']) ? array($log['class'],$log['function']) : $log['function'] );
				$log['argnames'] = phpio_argnames($function);
			}
		}
	}

	function getProfiles() {}
	function getProfile($profile_id) {}
	function getSource($file) {}
	function getFlow($root_profile_id){}
	function getCurlHeader($curl_id){}
	function saveSource(){}
	function saveCurlHeader(){}
	function saveProfileFlow(){}
	function saveProfileList(){}
	function saveProfile(){}
}
