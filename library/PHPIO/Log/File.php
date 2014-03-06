<?php

class PHPIO_Log_File extends PHPIO_Log {
	var $processor = array('saveArgnames','saveProfile');

	function saveProfile() {
		file_put_contents($this->save_dir.'/prof_'.PHPIO::$run_id, $this->serialize($this->logs));
	}

	function getProfiles($limit=10) {
		$profiles = array();
		$files = glob($this->save_dir.'/prof_*');
		foreach($files as $file) {
			$data = file_get_contents($file);
			if ( $data !== false ) {
				$data = $this->unserialize($data);
				$profile_id = substr(basename($file),5);
				$profiles[$profile_id] = array($profile_id, $this->getURI($data[0]['_SERVER']));
			}
		}
		krsort($profiles);
		array_splice($profiles, $limit);
		return $profiles;
	}

	function getProfile($profile_id) {
		$profile = $this->save_dir.'/prof_'.$profile_id;
		if ( !is_file($profile) ) return false;

		$data = file_get_contents($profile);
		return $this->unserialize($data);
	}

	function getFlow($profile_id) {
		$root_profile_id = $this->getRootProfileId($profile_id);
		$profiles = glob($this->save_dir."/prof_{$root_profile_id}*");
		foreach($profiles as &$profile) {
			$profile = substr(basename($profile),5);
		}
		return $profiles;
	}

	function getCurlHeader($curl_id){
		return file_get_contents($curl_id);
	}

	function getSource($file) {
		return file_get_contents($file);
	}

	function flush() {
		phpio_rmdir($this->save_dir);
	}
}
