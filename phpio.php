<?php

if (isset($_REQUEST['XDEBUG_PROFILE']) || 
	isset($_COOKIE['XDEBUG_PROFILE']) ||
	isset($_SERVER['XDEBUG_PROFILE']) ) {
	
	require 'config.php';
	PHPIO::hook();
}