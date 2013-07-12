<?php
require '../config.php';

switch( $_REQUEST['op'] ){
	case 'fileviewer':
		$file = $_REQUEST['file'];
		$line = $_REQUEST['line'];
		$data = file_get_contents($file);
		if ( $data === false ) {
			echo 'Source file is not exists or is not readable.';
			break;
		}
		require 'templates/fileviewer.phtml';
		break;
	case 'profiles':
		$profiles = PHPIO::$log->getProfiles();
		echo json_encode($profiles);
		break;
	default:
		$profile_id = $_REQUEST['profile_id'];
		if ( empty($profile_id) ) {
			$profiles = PHPIO::$log->getProfiles(1);
			if ( is_array($profiles[0]) ) {
				list($profile_id, $uri) = $profiles[0];
			}
		}

		$data = PHPIO::$log->getProfile($profile_id);
		if ( $data === false ) {
			echo 'Profile is not exists or is not readable.';
			break;
		}
		require 'templates/profile.phtml';
		break;
}
