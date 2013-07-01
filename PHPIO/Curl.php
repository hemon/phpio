<?php

class PHPIO_Curl extends PHPIO_Hook_Func {
	const classname = 'Curl';
	var $hooks = array('curl_exec');
	var $link_hooks = array('curl_exec');
	
	function preCallback($args, $traces) {
		$ch = $args[0];
		
		$traces[1]['cmd'] = $traces[1]['url'] = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
		
		$stderr = '/tmp/phpio_curl_'.PHPIO::$run_id.'_'.intval($ch);

		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_STDERR, fopen($stderr, 'w'));
		
		parent::preCallback($args, $traces);
	}
	
	function postCallback($args, $traces, $result) {
		$ch = $args[0];
		
		$stderr = '/tmp/phpio_curl_'.PHPIO::$run_id.'_'.intval($ch);
		$header = file_get_contents($stderr);
		unlink($stderr);

		$traces[1]['header'] = $header;
		$traces[1]['curl'] = curl_getinfo($ch);
		$traces[1]['link'] = $this->getLink($header);
		
		parent::postCallback($args, $traces, $result);
	}
	
	function getLink($header) {
		if (is_array($header)) {
			return parent::getLink($header);
		} else {
			if ( preg_match('/Connected to (.*?) \((.*?)\) port (\d+)/', $header, $matches) ) {
				return $matches[2].":".$matches[3];
			}
		}
	}
}