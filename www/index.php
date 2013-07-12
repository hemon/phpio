<?php
require '../config.php';

switch( $_REQUEST['op'] ){
	case 'fileviewer':
		$file = $_REQUEST['file'];
		$line = $_REQUEST['line'];
		$data = file_get_contents($file);
		if ( $data === false ) {
			echo 'source file is not exists or is not readable.';
			break;
		}
		require 'templates/fileviewer.phtml';
		break;
	case 'profiles':
		$profiles = PHPIO::$log->profiles();
		echo json_encode($profiles);
		break;
	default:
		$profile_id = $_REQUEST['profile_id'];
		if ( empty($profile_id) ) {
			$profiles = PHPIO::$log->profiles();
			list($profile_id, $uri) = each($profiles);
		}

		$data = PHPIO::$log->fetch($profile_id);
		if ( $data === false ) {
			echo 'profile is not exists or is not readable.';
			break;
		}
		require 'templates/profile.phtml';
		break;
}
