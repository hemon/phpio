<?php

if ( !defined('PHPIO_ROOT') ) require '../config.php';

switch( $_REQUEST['op'] ){
	case 'fileviewer':
		$file = $_REQUEST['file'];
		$line = $_REQUEST['line'];
		$data = PHPIO::$log->getSource($file);
		if ( $data === false ) {
			echo 'Source file is not exists or is not readable.';
			break;
		}
		require 'templates/fileviewer.phtml';
		break;
	case 'profiles':
		$profiles = PHPIO::$log->getProfiles(20);
		echo json_encode($profiles);
		break;
	default:
		$profile_id = $_REQUEST['profile_id'];
		if ( empty($profile_id) ) {
			$profiles = PHPIO::$log->getProfiles(1);
			if ( is_array($profiles) ) {
				list($profile_id, $uri) = array_shift($profiles);
			}
		}
		$flow = PHPIO::$log->getFlow($profile_id);
		$data = PHPIO::$log->getProfile($profile_id);
		if ( $data === false ) {
			echo 'Profile is not exists or is not readable.';
			break;
		}
		require 'templates/profile.phtml';
		break;
}
