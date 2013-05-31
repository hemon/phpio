<?php

class PHPIO_Curl extends PHPIO_Hook_Func {
	const classname = 'Curl';
	var $hooks = array('curl_exec');
	var $link_hooks = array('curl_exec');
	
	function preCallback($args, $traces) {
		$ch = $args[0];
		
		$traces[1]['url'] = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
		
		$stderr = '/tmp/phpio_curl_'.PHPIO::$run_id.'_'.intval($ch);

		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_STDERR, fopen($stderr, 'w'));
		
		parent::preCallback($args, $traces);
	}
	
	function postCallback($args, $traces, $result, $t) {
		$ch = $args[0];
		
		$stderr = '/tmp/phpio_curl_'.PHPIO::$run_id.'_'.intval($ch);
		$traces[1]['http'] = file_get_contents($stderr);
		$traces[1]['curl'] = curl_getinfo($ch);
		
		unlink($stderr);
		
		parent::postCallback($args, $traces, $result, $t);
	}
	
	function getLink($args) {
		$link = $args[0];
		$link = (is_resource($link) ? $link : $this->link);
		return $link;
	}
}