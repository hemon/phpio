<?php

class PHPIO_Log_File {
	var $save_dir = '';
	var $logs = array();

	function append($value) {
		$this->logs[] = $value;
	}

	function count() {
		return count($this->logs);
	}

	function save() {
		if ( !file_exists($this->save_dir) ) {
			mkdir($this->save_dir);
		}

		if ( $this->count() > 0 ) {
			file_put_contents($this->save_dir.'/prof_'.PHPIO::$run_id, serialize($this->logs));
		}
	}

	function profiles($limit=10) {
		$profiles = array();
		$files = glob($this->save_dir.'/prof_*');
		foreach($files as $file) {
			$data = file_get_contents($file);
			if ( $data !== false ) {
				$data = unserialize($data);
				$profile_id = substr(basename($file),5);
				$info = $data[0]['_SERVER'];
				$profiles[ $profile_id ] = isset($info['DOCUMENT_URI']) ? $info['DOCUMENT_URI'] : $info['SCRIPT_NAME'];
			}
		}
		krsort($profiles);
		array_splice($profiles, $limit);
		return $profiles;
	}

	function fetch($profile_id) {
		$profile = (!is_file($profile_id) ? $this->save_dir.'/prof_'.$profile_id : $profile_id);
		$data = file_get_contents($profile);
		if ( substr($data,0,2) == 'a:' ) {
			$array = unserialize($data);
			if ( is_array($array) ) {
				return $array;
			}
		}
		return $data;
	}
}
