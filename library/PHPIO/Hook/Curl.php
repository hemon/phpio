<?php

class PHPIO_Hook_Curl extends PHPIO_Hook_Func {
	const classname = 'Curl';
	var $hooks = array('curl_exec','curl_multi_add_handle','curl_multi_remove_handle');
	var $stderr = array(); // curl error handels
	var $followProfileFlag = false;
	
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
		$stderr = $this->stderr[$ch_id] = PHPIO::$log->save_dir.'/curl_'.PHPIO::$run_id.'_'.$ch_id;

		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_STDERR, fopen($stderr, 'w'));
		
		if ($this->followProfileFlag) $this->followProfileFlag($ch);
	}

	function followProfileFlag($ch) {
		$url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
		$glue = (( strpos($url,'?') !== false ) ? '&' : '?');
		$url .= $glue.'XDEBUG_PROFILE='.PHPIO::$run_id;
		curl_setopt($ch, CURLOPT_URL, $url);
	}

	function removeErrorHandle($ch) {
		$ch_id = intval($ch);
		$stderr = $this->stderr[$ch_id];

		$this->trace['cmd'] = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
		$this->trace['curl'] = curl_getinfo($ch);
		$this->trace['curl']['http_status'] = $this->httpStatus($this->trace['curl']['http_code']);
		$this->trace['header'] = $stderr;
	}
	
	static function getLink($header) {
		if ( preg_match('/Connected to (.*?) \((.*?)\) port (\d+)/', $header, $matches) ) {
			return $matches[2].":".$matches[3];
		}
	}

	static function httpStatus($http_code) {
		$HTTP_STATUS = array(
			100 => 'Continue',
			101 => 'Switching Protocols',
			102 => 'Processing',
			200 => 'OK',
			201 => 'Created',
			202 => 'Accepted',
			203 => 'Non-Authoriative Information',
			204 => 'No Content',
			205 => 'Reset Content',
			206 => 'Partial Content',
			207 => 'Multi-Status',
			300 => 'Multiple Choices',
			301 => 'Moved Permanently',
			302 => 'Found',
			303 => 'See Other',
			304 => 'Not Modified',
			305 => 'Use Proxy',
			306 => '(Unused)',
			307 => 'Temporary Redirect',
			400 => 'Bad Request',
			401 => 'Unauthorized',
			402 => 'Payment Granted',
			403 => 'Forbidden',
			404 => 'File Not Found',
			405 => 'Method Not Allowed',
			406 => 'Not Acceptable',
			407 => 'Proxy Authentication Required',
			408 => 'Request Time-out',
			409 => 'Conflict',
			410 => 'Gone',
			411 => 'Length Required',
			412 => 'Precondition Failed',
			413 => 'Request Entity Too Large',
			414 => 'Request-URI Too Large',
			415 => 'Unsupported Media Type',
			416 => 'Requested range not satisfiable',
			417 => 'Expectation Failed',
			422 => 'Unprocessable Entity',
			423 => 'Locked',
			424 => 'Failed Dependency',
			500 => 'Internal Server Error',
			501 => 'Not Implemented',
			502 => 'Bad Gateway',
			503 => 'Service Unavailable',
			504 => 'Gateway Timeout',
			505 => 'HTTP Version Not Supported',
			507 => 'Insufficient Storage',
		);
		return isset($HTTP_STATUS[$http_code]) ? $HTTP_STATUS[$http_code] : '';
	}
}

