<?php

class PHPIO_Hook_Core extends PHPIO_Hook_Func {
    const classname = 'Core';
    var $link = 'localhost';
    var $hooks = array(
         'gethostbyaddr',
         'gethostbyname',
		 'gethostbynamel',
		 'file_get_contents',
		 'file_put_contents',
		 'fsockopen',
		 'pfsockopen',
		 'header',
		 'popen',
		 'fopen',
		 'fwrite',
		 'fread',
		 'fgets',
		 'socket_create',
		 'socket_connect',
		 'socket_write',
		 'socket_recv',
		 'stream_socket_client',
		 'stream_socket_sendto',
		 'stream_socket_recvfrom',
		 'stream_get_line'
    );

    function postCallback($jp) {
        $this->trace['link'] = $this->link;
        $this->trace['cmd'] = (is_array($this->args[0]) ? implode(' ',$this->args[0]) : $this->args[0]);

        parent::postCallback($jp);
    }
}
