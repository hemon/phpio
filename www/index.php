<?php
/**
 * @author Jacob Oettinger
 * @author Joakim Nygård
 */

require 'config.php';
require 'library/FileHandler.php';

// TODO: Errorhandling:
// 		No files, outputdir not writabel

set_time_limit(0);

// Make sure we have a timezone for date functions.
if (ini_get('date.timezone') == '')
    date_default_timezone_set( Webgrind_Config::$defaultTimezone );


switch(get('op')){
	case 'file_list':
		echo json_encode(Webgrind_FileHandler::getInstance()->getTraceList());
		break;	
	case 'fileviewer':
		$file = get('file');
		$line = get('line');
	
		if($file && $file!=''){
			$message = '';
			if(!file_exists($file)){
				$message = $file.' does not exist.';
			} else if(!is_readable($file)){
				$message = $file.' is not readable.';
			} else if(is_dir($file)){
				$message = $file.' is a directory.';
			} 		
		} else {
			$message = 'No file to view';
		}
		require 'templates/fileviewer.phtml';
	
		break;
	case 'view':
		if ( file_exists( '/tmp/phpio/'.$_REQUEST['profile'] ) ) {
			$data = json_decode(file_get_contents($profile), true);
			require 'templates/view.phtml';
		}
		break;
	default:
		$profiles = glob('/tmp/phpio/*');
		end($profiles);
		$profile = current($profiles);
		$data = json_decode(file_get_contents($profile), true);
		
		require 'templates/view.phtml';
}


function get($param, $default=false){
	return (isset($_GET[$param])? $_GET[$param] : $default);
}
