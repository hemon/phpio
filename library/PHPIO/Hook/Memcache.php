<?php

class PHPIO_Hook_Memcache extends PHPIO_Hook {
	const classname = 'Memcache';
	var $hooks = array(
        'add',
        'addServer',
        'close',
        'connect',
        'decrement',
        'delete',
        'flush',
        'get',
        'getExtendedStats',
        'getServerStatus',
        'getStats',
        'getVersion',
        'increment',
        'pconnect',
        'replace',
        'set',
        'setCompressThreshold',
        'setServerParams',
    );

    function addServer_post($jp) {
        $link = $this->getLink($this->args);
        if ( !in_array($link, $this->links) ) {
            $this->links[] = $link;
        }
        $this->postCallback($jp);
    }

    function pconnect_post($jp) {
        $this->connect_post($jp);
    }

    function connect_post($jp) {
        $this->links[] = $this->getLink($this->args);
        $this->postCallback($jp);
    }

    function getLink($args) {
        if ( substr($args[0],0,4) == 'unix' ) {
            $link = $args[0];
        } else {
            $port = isset($args[1]) ? $args[1] : 11211;
            $link = $args[0].":".$args[1];
        }
        return $link;
    }

    function postCallback($jp) {
        $this->trace['link'] = implode(';',$this->links);
        if (isset($this->args[0])) $this->trace['cmd'] = (is_array($this->args[0]) ? implode(' ',$this->args[0]) : $this->args[0]);        
        parent::postCallback($jp);
    }

    function getServerByKey($key) {

    }
}
