<?php

class PHPIO_Hook_Curl extends PHPIO_Hook_Func {
	const classname = 'Curl';
	var $hooks = array(
		'curl_init',
		'curl_setopt',
		'curl_setopt_array',
		'curl_exec',
		'curl_multi_remove_handle',
	);
	var $stderr = array(); // curl error handels
	var $autoFollow = true;
	var $options = array();
	static $CURLOPT = array (
		58 => 'CURLOPT_AUTOREFERER',
		19914 => 'CURLOPT_BINARYTRANSFER',
		96 => 'CURLOPT_COOKIESESSION',
		172 => 'CURLOPT_CERTINFO',
		27 => 'CURLOPT_CRLF',
		91 => 'CURLOPT_DNS_USE_GLOBAL_CACHE',
		45 => 'CURLOPT_FAILONERROR',
		69 => 'CURLOPT_FILETIME',
		52 => 'CURLOPT_FOLLOWLOCATION',
		75 => 'CURLOPT_FORBID_REUSE',
		74 => 'CURLOPT_FRESH_CONNECT',
		106 => 'CURLOPT_FTP_USE_EPRT',
		85 => 'CURLOPT_FTP_USE_EPSV',
		110 => 'CURLOPT_FTP_CREATE_MISSING_DIRS',
		50 => 'CURLOPT_FTPAPPEND',
		48 => 'CURLOPT_FTPLISTONLY',
		42 => 'CURLOPT_HEADER',
		2 => 'CURLINFO_HEADER_OUT',
		80 => 'CURLOPT_HTTPGET',
		61 => 'CURLOPT_HTTPPROXYTUNNEL',
		51 => 'CURLOPT_NETRC',
		44 => 'CURLOPT_NOBODY',
		43 => 'CURLOPT_NOPROGRESS',
		99 => 'CURLOPT_NOSIGNAL',
		47 => 'CURLOPT_POST',
		54 => 'CURLOPT_PUT',
		19913 => 'CURLOPT_RETURNTRANSFER',
		64 => 'CURLOPT_SSL_VERIFYPEER',
		53 => 'CURLOPT_TRANSFERTEXT',
		105 => 'CURLOPT_UNRESTRICTED_AUTH',
		46 => 'CURLOPT_UPLOAD',
		41 => 'CURLOPT_VERBOSE',
		98 => 'CURLOPT_BUFFERSIZE',
		72 => 'CURLOPT_CLOSEPOLICY',
		78 => 'CURLOPT_CONNECTTIMEOUT',
		156 => 'CURLOPT_CONNECTTIMEOUT_MS',
		92 => 'CURLOPT_DNS_CACHE_TIMEOUT',
		129 => 'CURLOPT_FTPSSLAUTH',
		84 => 'CURLOPT_HTTP_VERSION',
		107 => 'CURLOPT_HTTPAUTH',
		14 => 'CURLOPT_INFILESIZE',
		19 => 'CURLOPT_LOW_SPEED_LIMIT',
		20 => 'CURLOPT_LOW_SPEED_TIME',
		71 => 'CURLOPT_MAXCONNECTS',
		68 => 'CURLOPT_MAXREDIRS',
		3 => 'CURLOPT_PORT',
		181 => 'CURLOPT_PROTOCOLS',
		111 => 'CURLOPT_PROXYAUTH',
		59 => 'CURLOPT_PROXYPORT',
		101 => 'CURLOPT_PROXYTYPE',
		182 => 'CURLOPT_REDIR_PROTOCOLS',
		21 => 'CURLOPT_RESUME_FROM',
		81 => 'CURLOPT_SSL_VERIFYHOST',
		32 => 'CURLOPT_SSLVERSION',
		33 => 'CURLOPT_TIMECONDITION',
		13 => 'CURLOPT_TIMEOUT',
		155 => 'CURLOPT_TIMEOUT_MS',
		34 => 'CURLOPT_TIMEVALUE',
		30146 => 'CURLOPT_MAX_RECV_SPEED_LARGE',
		30145 => 'CURLOPT_MAX_SEND_SPEED_LARGE',
		151 => 'CURLOPT_SSH_AUTH_TYPES',
		10065 => 'CURLOPT_CAINFO',
		10097 => 'CURLOPT_CAPATH',
		10022 => 'CURLOPT_COOKIE',
		10031 => 'CURLOPT_COOKIEFILE',
		10082 => 'CURLOPT_COOKIEJAR',
		10036 => 'CURLOPT_CUSTOMREQUEST',
		10077 => 'CURLOPT_EGDSOCKET',
		10102 => 'CURLOPT_ENCODING',
		10017 => 'CURLOPT_FTPPORT',
		10062 => 'CURLOPT_INTERFACE',
		10026 => 'CURLOPT_SSLKEYPASSWD',
		10063 => 'CURLOPT_KRB4LEVEL',
		10015 => 'CURLOPT_POSTFIELDS',
		10004 => 'CURLOPT_PROXY',
		10006 => 'CURLOPT_PROXYUSERPWD',
		10076 => 'CURLOPT_RANDOM_FILE',
		10007 => 'CURLOPT_RANGE',
		10016 => 'CURLOPT_REFERER',
		10162 => 'CURLOPT_SSH_HOST_PUBLIC_KEY_MD5',
		10152 => 'CURLOPT_SSH_PUBLIC_KEYFILE',
		10153 => 'CURLOPT_SSH_PRIVATE_KEYFILE',
		10083 => 'CURLOPT_SSL_CIPHER_LIST',
		10025 => 'CURLOPT_SSLCERT',
		10086 => 'CURLOPT_SSLCERTTYPE',
		10089 => 'CURLOPT_SSLENGINE',
		90 => 'CURLOPT_SSLENGINE_DEFAULT',
		10087 => 'CURLOPT_SSLKEY',
		10088 => 'CURLOPT_SSLKEYTYPE',
		10002 => 'CURLOPT_URL',
		10018 => 'CURLOPT_USERAGENT',
		10005 => 'CURLOPT_USERPWD',
		10104 => 'CURLOPT_HTTP200ALIASES',
		10023 => 'CURLOPT_HTTPHEADER',
		10039 => 'CURLOPT_POSTQUOTE',
		10028 => 'CURLOPT_QUOTE',
		10001 => 'CURLOPT_FILE',
		10009 => 'CURLOPT_INFILE',
		10037 => 'CURLOPT_STDERR',
		10029 => 'CURLOPT_WRITEHEADER',
		20079 => 'CURLOPT_HEADERFUNCTION',
		20056 => 'CURLOPT_PROGRESSFUNCTION',
		20012 => 'CURLOPT_READFUNCTION',
		20011 => 'CURLOPT_WRITEFUNCTION',
	);

	static $HTTP_STATUS = array(
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

	function curl_init_post($jp) {
		$ch = $this->result;
		$ch_id = intval($ch);
		$this->options[$ch_id] = array();

		if ( $this->autoFollow ) {
			curl_setopt($ch, CURLOPT_COOKIE, 'XDEBUG_PROFILE='.PHPIO::$run_id.';');
		}

		$this->stderr[$ch_id] = PHPIO::$log->save_dir.'/curl_'.PHPIO::$run_id.'_'.$ch_id;
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_STDERR, fopen($this->stderr[$ch_id], 'w'));
	}
	
	function curl_exec_post($jp) {
		$ch = $this->args[0];
		$this->getinfo($ch);
		
		parent::postCallback($jp);
	}
	// when remove curl handle, curl was executed
	function curl_multi_remove_handle_pre($jp) {
		$ch = $this->args[1];

		$this->getinfo($ch);
		$this->trace['result'] = $this->dump(curl_multi_getcontent($ch));
	}

	function curl_setopt_pre($jp) {
		$this->rewriteOption($this->args[1], $this->args[2]);
		$jp->setArguments($this->args);
	}

	function curl_setopt_post($jp) {
		if ( $this->result === true ) {
			$ch = $this->args[0];
			$ch_id = intval($ch);
			$option = $this->args[1];
			$value = $this->args[2];
			$this->options[$ch_id][$option] = $value;
		}
	}

	function curl_setopt_array_pre($jp) {
		$ch = $this->args[0];
		foreach ( $this->args[1] as $curlopt => &$value ) {
			$this->rewriteOption($curlopt, $value);
		}
		
		$jp->setArguments($this->args);
	}

	function curl_setopt_array_post($jp) {
		if ( $this->result === true ) {
			$ch = $this->args[0];
			$ch_id = intval($ch);
			$this->options[$ch_id] = $this->args[1];
		}
	}

	function getinfo($ch) {
		$ch_id = intval($ch);
		$this->trace['options'] = $this->getOptions($this->options[$ch_id]);
		$this->trace['cmd'] = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
		$this->trace['curl'] = curl_getinfo($ch);
		$this->trace['curl']['http_status'] = $this->httpStatus($this->trace['curl']['http_code']);
		$this->trace['header'] = $this->stderr[$ch_id];

		$error = curl_error($ch);
		if ( !empty($error) ) {
			$this->trace['error'] = $error;
			// in [curl_multi_exec], only [curl_multi_info_read] can get last error number
			$this->trace['errno'] = curl_errno($ch);
		}
	}

	function getOptions($args) {
		$newArgs = array();
		foreach ($args as $name => $value) {
			$newName = (isset(self::$CURLOPT[$name]) ? self::$CURLOPT[$name] : $name);
			$newArgs[$newName] = $value;
		}
		return $newArgs;
	}

	function rewriteOption($curlopt, &$value) {
		if ( isset(self::$CURLOPT[$curlopt]) ) {
			$callback_rewrite = self::$CURLOPT[$curlopt].'_rewrite';
			if ( method_exists($this, $callback_rewrite) ) {
				$value = $this->$callback_rewrite($value);
			}
		}
	}

	static function httpStatus($http_code) {
		return isset(self::$HTTP_STATUS[$http_code]) ? self::$HTTP_STATUS[$http_code] : '';
	}
	
	static function getLink($header) {
		if ( preg_match('/Connected to (.*?) \((.*?)\) port (\d+)/', $header, $matches) ) {
			return $matches[2].":".$matches[3];
		}
	}

	function CURLOPT_COOKIE_rewrite($value) {
		if ( strpos($value,PHPIO_PROFILE) === false ) {
			$value .= ';'.PHPIO_PROFILE.'='.PHPIO::$run_id.';';
		}
		return $value;
	}
}
