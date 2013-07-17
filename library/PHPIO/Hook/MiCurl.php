<?php
/*
This module only for xiaomi.com
*/
class PHPIO_Hook_MiCurl extends PHPIO_Hook_Func {
	const classname = 'Curl';
	var $hooks = array('curl_init','curl_setopt','curl_setopt_array');
	var $stderr = array(); // curl error handels
	
	function curl_init_pre($jp) {
	}
	
	function curl_init_post($jp) {
		$ch = $this->result;
		curl_setopt($ch, CURLOPT_COOKIE, 'XDEBUG_PROFILE='.PHPIO::$run_id.';');
	}

	function curl_setopt_pre($jp) {
		$this->args[2] = $this->rewrite_curl_setopt($this->args[1], $this->args[2]);
		$jp->setArguments($this->args);
	}

	function curl_setopt_post($jp) {
	}

	function curl_setopt_array_pre($jp) {
		foreach ( $this->args[1] as $curlopt => &$value ) {
			$value = $this->rewrite_curl_setopt($curlopt, $value);
		}
		$jp->setArguments($this->args);
	}

	function curl_setopt_array_post($jp) {
	}

	function rewrite_curl_setopt($curlopt, $value) {
		switch ($curlopt) {
			case CURLOPT_COOKIE :
				// append profile flag to COOKIE
				if ( strpos($value,'XDEBUG_PROFILE') === false ) {
					$value .= ';XDEBUG_PROFILE='.PHPIO::$run_id.';';
				}
				break;
			case CURLOPT_POSTFIELDS :
				// append profile flag to x5 header[url]
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
				break;
		}
		return $value;
	}
}

