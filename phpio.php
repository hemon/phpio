<?php

if (isset($_REQUEST['XDEBUG_PROFILE']) || 
	isset($_COOKIE['XDEBUG_PROFILE']) ||
	isset($_SERVER['XDEBUG_PROFILE']) ) {
	
	require __DIR__.'/boot.php';

	PHPIO::hook();
}