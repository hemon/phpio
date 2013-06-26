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
	case 'profiles':
		$profiles = phpio_profiles();
		echo json_encode($profiles);
	default:
		$profile = $_REQUEST['profile'];
		if ( empty($profile) ) {
			$profiles = phpio_profiles();
			list($profile, $uri) = each($profiles);
		}

		$file = STORE .'/'. $profile;
		list($is_ok, $data) = file_read($file);
		if ( $is_ok ) {
			$data = unserialize($data);
			require 'templates/profile.phtml';
		}
		break;
}
