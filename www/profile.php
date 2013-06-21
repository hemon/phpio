<?php
require 'config.php';

switch( $_REQUEST['op'] ){
	case 'fileviewer':
		$file = $_REQUEST['file'];
		$line = $_REQUEST['line'];
		list($is_ok, $data) = file_read($file);
		if ( !$is_ok ) {
			echo $data;
			break;
		}
		require 'templates/fileviewer.phtml';
		break;
	case 'view':
		$file = STORE .'/'. $_REQUEST['profile'];
		list($is_ok, $data) = file_read($file);
		if ( $is_ok ) {
			echo $data;
		}
		break;
	default:
		$profiles = array();
		$files = glob(STORE.'/*');
		$limit = 20;
		foreach($files as $file) {
			list($is_ok, $data) = file_read($file);
			if ( $is_ok ) {
				$data = json_decode($data, true);
				$profiles[ basename($file) ] = array($data[0]['_SERVER']['REQUEST_URI']);
			}
		}
		krsort($profiles);
		array_splice($profiles, $limit);
		echo json_encode($profiles);
}