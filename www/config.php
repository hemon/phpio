<?php

set_time_limit(0);

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
