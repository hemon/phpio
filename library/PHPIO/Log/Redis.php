<?php

class PHPIO_Log_Redis {
	var $save_dir = '';
	var $logs = array();
	var $redis = null;
	var $host = '127.0.0.1';
	var $port = 6379;
	var $auth = '';

	function append($value) {
		$this->logs[] = $value;
	}

	function count() {
		return count($this->logs);
	}

	function save() {
		if ( $this->count() == 0 ) return;

		$run_ids = explode('.', PHPIO::$run_id);
		$last_id = array_slice($run_ids, -1);
		
		$this->getRedis()->hSet('PHPIO_PROF', PHPIO::$run_id, serialize($this->logs));
		$this->getRedis()->zAdd(
			'PHPIO_LIST', 
			hexdec($last_id), 
			serialize(array(PHPIO::$run_id, $this->getURI($this->logs[0]['_SERVER'])))
		);

		if ( count($run_ids) >= 2 ) {
			$root_profile_id = $run_ids[0];
			$this->getRedis()->sAdd('PHPIO_REL_'.$root_prof_id, PHPIO::$run_id);
		}

		$curls = glob($this->save_dir.'/curl_*');
		foreach ( $curls as $curl ) {
			if ( filesize($curl) > 0 ) {
				$curl_id = basename($curl);
				$this->getRedis()->hSet('PHPIO_CURL', $curl_id, file_get_contents($curl));
				unlink($curl);
			}
		}
	}

	function getURI($info) {
		return isset($info['DOCUMENT_URI']) ? $info['DOCUMENT_URI'] : $info['SCRIPT_NAME'];
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

	function getRel($root_profile_id) {
		return $this->getRedis()->sMembers($root_profile_id);
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
