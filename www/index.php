<?php
require '../config.php';

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
	case 'profiles':
		$profiles = phpio_profiles();
		echo json_encode($profiles);
		break;
	default:
		$profile_id = $_REQUEST['profile_id'];
		if ( empty($profile_id) ) {
			$profiles = phpio_profiles();
			list($profile_id, $uri) = each($profiles);
		}

		$file = STORE .'/prof_'. $profile_id;
		list($is_ok, $data) = file_read($file);
		if ( $is_ok ) {
			$data = unserialize($data);
			require 'templates/profile.phtml';
			//echo '<pre>',print_r($data);
		}
		break;
}
