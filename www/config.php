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

function phpio_profiles($limit=10) {
	$profiles = array();
	$files = glob(STORE.'/prof_*');
	foreach($files as $file) {
		list($is_ok, $data) = file_read($file);
		if ( $is_ok ) {
			$data = unserialize($data);
			$profile_id = substr(basename($file),5);
			$profiles[ $profile_id ] = $data[0]['_SERVER']['SCRIPT_NAME'];
		}
	}
	krsort($profiles);
	array_splice($profiles, $limit);
	return $profiles;
}

function curl_link($header) {
	if ( preg_match('/Connected to (.*?) \((.*?)\) port (\d+)/', $header, $matches) ) {
			return $matches[2].":".$matches[3];
	}
}

function http_status($code) {
	$http_status = array(
		100 => 'Continue',
		101 => 'Switching Protocols',
		102 => 'Processing',
		200 => 'OK',
		201 => 'Created',
		202 => 'Accepted',
		203 => 'Non-Authoriative Information',
		204 => 'No Content',
		205 => 'Reset Content',
		206 => 'Partial Content',
		207 => 'Multi-Status',
		300 => 'Multiple Choices',
		301 => 'Moved Permanently',
		302 => 'Found',
		303 => 'See Other',
		304 => 'Not Modified',
		305 => 'Use Proxy',
		306 => '(Unused)',
		307 => 'Temporary Redirect',
		400 => 'Bad Request',
		401 => 'Unauthorized',
		402 => 'Payment Granted',
		403 => 'Forbidden',
		404 => 'File Not Found',
		405 => 'Method Not Allowed',
		406 => 'Not Acceptable',
		407 => 'Proxy Authentication Required',
		408 => 'Request Time-out',
		409 => 'Conflict',
		410 => 'Gone',
		411 => 'Length Required',
		412 => 'Precondition Failed',
		413 => 'Request Entity Too Large',
		414 => 'Request-URI Too Large',
		415 => 'Unsupported Media Type',
		416 => 'Requested range not satisfiable',
		417 => 'Expectation Failed',
		422 => 'Unprocessable Entity',
		423 => 'Locked',
		424 => 'Failed Dependency',
		500 => 'Internal Server Error',
		501 => 'Not Implemented',
		502 => 'Bad Gateway',
		503 => 'Service Unavailable',
		504 => 'Gateway Timeout',
		505 => 'HTTP Version Not Supported',
		507 => 'Insufficient Storage',
	);
	return isset($http_status[$code]) ? $http_status[$code] : '';
}
