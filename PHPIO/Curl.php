<?php

class PHPIO_Curl extends PHPIO_Hook_Func {
	const classname = 'Curl';
	var $hooks = array('curl_exec','curl_multi_add_handle','curl_multi_remove_handle');
	var $stderr = array(); // curl error handels
	
	function curl_exec_pre($jp) {
		$ch = $this->args[0];
		$this->addErrorHandle($ch);
	}
	
	function curl_exec_post($jp) {
		$ch = $this->args[0];
		$this->removeErrorHandle($ch);
		
		parent::postCallback($jp);
	}
	// add verbose(debug) file handle before curl_multi_exec
	function curl_multi_add_handle_pre($jp) {
		$ch = $this->args[1];
		$this->addErrorHandle($ch);
	}
	// don't log curl_multi_add_handle
	function curl_multi_add_handle_post($jp) {
	}
	// when remove curl handle, curl was executed
	function curl_multi_remove_handle_post($jp) {
		$ch = $this->args[1];
		$this->removeErrorHandle($ch);
		$this->trace['result'] = $this->dump(curl_multi_getcontent($ch));

		parent::postCallback($jp);
	}

	function addErrorHandle($ch) {
		$ch_id = intval($ch);
		$stderr = $this->stderr[$ch_id] = '/tmp/phpio/curl_'.PHPIO::$run_id.'_'.$ch_id;

		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_STDERR, fopen($stderr, 'w'));
	}

	function removeErrorHandle($ch) {
		$ch_id = intval($ch);
		$stderr = $this->stderr[$ch_id];

		$this->trace['cmd'] = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
		$this->trace['curl'] = curl_getinfo($ch);
		$this->trace['header'] = $stderr;
	}
	
	function getLink($header) {
		if ( preg_match('/Connected to (.*?) \((.*?)\) port (\d+)/', $header, $matches) ) {
				return $matches[2].":".$matches[3];
		}
	}
}