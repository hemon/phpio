<?php

set_time_limit(0);
error_reporting(E_ALL ^ E_NOTICE);

define('STORE', '/tmp/phpio/');

function file_read($file) {
	if($file && $file!=''){
		$error = '';
		if(!file_exists($file)){
			$error = $file.' does not exist.';
		} else if(!is_readable($file)){
			$error = $file.' is not readable.';
		} else if(is_dir($file)){
			$error = $file.' is a directory.';
		}
	} else {
		$error = 'No file to view';
	}
	
	return empty($error) ? array(true,file_get_contents($file)) : array(false, $error);
}

function phpio_profiles($limit=20) {
	$profiles = array();
	$files = glob(STORE.'/*');
	foreach($files as $file) {
		list($is_ok, $data) = file_read($file);
		if ( $is_ok ) {
			$data = json_decode($data, true);
			$profiles[ basename($file) ] = array($data[0]['_SERVER']['REQUEST_URI']);
		}
	}
	krsort($profiles);
	array_splice($profiles, $limit);
	return $profiles;
}
