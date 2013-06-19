<?php

class PHPIO_Curl2 extends PHPIO_Hook_Func {
	const classname = 'Curl2';
	var $hooks = array('curl_multi_add_handle','curl_multi_remove_handle');
	var $link_hooks = array();
	
	function preCallback($args, $traces) {
		$ch = $args[1];
		
		$traces[1]['cmd'] = $traces[1]['url'] = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
		
		$stderr = '/tmp/phpio_curl_'.PHPIO::$run_id.'_'.intval($ch);
		var_dump($stderr);
		//curl_setopt($ch, CURLOPT_URL, 'http://www.google.com');
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_STDERR, fopen($stderr, 'w'));
		
		parent::preCallback($args, $traces);
	}
	
	function postCallback($args, $traces, $result=null, $t=0) {
		$ch = $args[1];
		$stderr = '/tmp/phpio_curl_'.PHPIO::$run_id.'_'.intval($ch);
		var_dump($stderr);
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

	function hook() {
		phpio_add_pre('curl_multi_add_handle', array($this, 'preCallback'));
		phpio_add_pre('curl_multi_remove_handle', array($this, 'postCallback'));
	}
}