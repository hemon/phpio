<?php

foreach(glob('*.php') as $test){
	if ( $test === 'all.php' ) continue;

	include($test);
}
