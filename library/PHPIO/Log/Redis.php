<?php

class PHPIO_Log_Redis extends PHPIO_Log {
	var $redis = null;
	var $host = '127.0.0.1';
	var $port = 6379;
	var $auth = '';
	var $processor = array('saveArgnames','saveCurlHeader','saveSource','saveProfileFlow','saveProfileList','saveProfile');

	function saveProfile() {
		$this->getRedis()->hSet('PHPIO_PROF', PHPIO::$run_id, serialize($this->logs));
	}

	function saveProfileList() {
		$this->getRedis()->zAdd(
			'PHPIO_LIST', 
			$_SERVER['REQUEST_TIME'], 
			serialize(array(PHPIO::$run_id, $this->getURI($this->logs[0]['_SERVER'])))
		);
	}

	function saveProfileFlow() {
		list($root_profile_id, $profile_ids) = explode('.', PHPIO::$run_id, 2);
		if ( !empty($profile_ids) ) {
			$this->getRedis()->sAdd('PHPIO_FLOW_'.$root_profile_id, PHPIO::$run_id);
		}
	}

	function saveCurlHeader() {
		$curls = glob($this->save_dir.'/curl_*');
		foreach ( $curls as $curl ) {
			if ( filesize($curl) > 0 ) {
				$curl_id = basename($curl);
				$this->getRedis()->hSet('PHPIO_CURL', $curl_id, file_get_contents($curl));
				unlink($curl);
			}
		}
	}

	function getSource($file) {
		return $this->getRedis()->hGet('PHPIO_SRC', $file);
	}

	function saveSource() {
		$files = array();
		foreach ( $this->logs as $log ) {
			if ( isset($log['trace']) ) foreach ($log['trace'] as $trace) {
				if ( preg_match('|\[(.*?)\:\d+\]|', $trace, $matches) ) {
					$files[] = $matches[1];
				}
			}
		}

		$files = array_unique($files);
		foreach ( $files as $file ) {
			if ( !is_file($file) ) continue;

			$hash = md5_file($file);
			if ( !$this->getRedis()->hExists('PHPIO_SRC', $hash) ) {
				$this->getRedis()->hSet('PHPIO_SRC', $hash, file_get_contents($file));
			}
			$this->logs[0]['_SRC'][$file] = $hash;
		}
	}

	function getProfiles($limit=10) {
		$profiles = $this->getRedis()->zRevRange('PHPIO_LIST', 0, $limit-1);
		foreach ( $profiles as &$profile) {
			$profile = unserialize($profile);
		}
		return $profiles;
	}

	function getProfile($profile_id) {
		$data = $this->getRedis()->hGet('PHPIO_PROF', $profile_id);
		return unserialize($data);
	}

	function getFlow($root_profile_id) {
		$root_profile_id = substr($root_profile_id,0,13);
		$flow = $this->getRedis()->sMembers('PHPIO_FLOW_'.$root_profile_id);

		if ( is_array($flow) ) {
			array_unshift($flow, $root_profile_id);
		} else {
			$flow = array($root_profile_id);
		}
		return $flow;
	}

	function getCurlHeader($curl_id) {
		$curl_id = basename($curl_id);
		$data = $this->getRedis()->hGet('PHPIO_CURL', $curl_id);
		return $data;
	}

	function getRedis() {
		if ( $this->redis === null ) {
			$this->redis = new Redis();
			$this->redis->pconnect($this->host, $this->port);
			if ( !empty($this->auth) ) {
				$this->redis->auth($this->auth);
			}
		}

		return $this->redis;
	}
}

