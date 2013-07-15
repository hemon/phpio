<?php

$max = isset($_REQUEST['i']) ? $_REQUEST['i'] : 1;
for($i = 1; $i < 5; $i++) {
	if ( rand(0,$max) < 1 ) curl();
}

function curl() {
	global $max;
	// create a new cURL resource
	$ch = curl_init();

	// set URL and other appropriate options
	curl_setopt($ch, CURLOPT_URL, "http://localhost/phpio/test/flow.php?i=".++$max);
	curl_setopt($ch, CURLOPT_HEADER, 0);

	// grab URL and pass it to the browser
	curl_exec($ch);

	// close cURL resource, and free up system resources
	curl_close($ch);
}
