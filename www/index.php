<?php

if ( !defined('PHPIO_ROOT') ) require '../boot.php';

switch( $_REQUEST['op'] ){
	case 'fileviewer':
		$file = $_REQUEST['file'];
		$line = $_REQUEST['line'];
		$data = PHPIO::$log->getSource($file);
		$file = isset($_REQUEST['filename']) ? $_REQUEST['filename'] : $file;
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
	case 'dot':
		$profile_id = $_REQUEST['profile_id'];
		if ( !empty($profile_id) ) {
			$data = PHPIO::$log->getProfile($profile_id);
			$dot = new PHPIO_Dot($data);
			$dot = $dot->output();
			require 'templates/dot.phtml';
		}
		break;
	case 'flush':
		PHPIO::$log->flush();
        header("Location: {$_SERVER['DOCUMENT_URI']}");
        break;
	default:
		$profile_id = $_REQUEST['profile_id'];
		if ( empty($profile_id) ) {
			$profiles = PHPIO::$log->getProfiles(1);
			if ( is_array($profiles) ) {
				list($profile_id, $uri) = array_shift($profiles);
			}
		}
		$data = PHPIO::$log->getProfile($profile_id);
		if ( is_array($data) ) {
			$flow = PHPIO::$log->getFlow($profile_id);
			if ( count($flow) > 1 ) {
				$flow_uris = PHPIO::$log->getProfileUri($flow);
			}
		}
		require 'templates/profile.phtml';
		break;
}
