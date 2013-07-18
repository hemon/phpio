<?php

class PHPIO_Hook_Redis extends PHPIO_Hook {
	const classname = 'Redis';
	var $hooks = array(
        '*',
    );

    function connect_post($jp) {
        $object_id = $this->getObjectId($this->object);
        $this->links[$object_id] = $this->getLink($this->args);
        $this->postCallback($jp);
    }

    function pconnect_post($jp) {
        $this->connect_post($jp);
    }

    function open_post($jp) {
        $this->connect_post($jp);
    }

    function popen_post($jp) {
        $this->connect_post($jp);
    }

    function getLink($args) {
        $host = $args[0];
        // not unix sock
        if ( strpos($host,'/') === false ) {
            $port = (isset($args[1]) ? $args[1] : 6379);
            $host = $host .":". $port;
        }
        return $host;
    }

    function postCallback($jp) {
        if ( $jp->getMethodName() !== '__construct' ) {
            $object_id = $this->getObjectId($this->object);
            if ( isset($this->links[$object_id]) ) {
                $this->trace['link'] = $this->links[$object_id];
            }

            if ( isset($this->args[0]) ) {
                $this->trace['cmd'] = (is_array($this->args[0]) ? implode(' ',$this->args[0]) : $this->args[0]);
            }
        }
        
        parent::postCallback($jp);
    }
}
