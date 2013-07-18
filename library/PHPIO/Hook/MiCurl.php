<?php
/*
This module for inject xiaomi X5 protocol
*/
class PHPIO_Hook_MiCurl extends PHPIO_Hook_Curl {
	function CURLOPT_POSTFIELDS_rewrite($value) {
		if ( is_string($value) ) {
			parse_str($value, $params);
			if ( isset($params['data']) ) {
				$params['data'] = json_decode(base64_decode($params['data']),true);
				if ( is_array($params['data']) && isset($params['data']['header']['method']) ) {
					$params['data']['header']['method'] .= '?XDEBUG_PROFILE='.PHPIO::$run_id;
				}
				$params['data'] = base64_encode(json_encode($params['data']));
			}
			$value = http_build_query($params);
		}
		return $value;
	}
}