<?php

class PHPIO_Memcache extends PHPIO_Hook_Class {
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
        $this->link[] = $this->getLink($this->args);
        $this->postCallback($jp);
    }

    function pconnect_post($jp) {
        $this->connect_post($jp);
    }

    function connect_post($jp) {
        $this->link = $this->getLink($this->args);
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
        $this->trace['link'] = (is_array($this->link) ? implode(';',$this->link) : $this->link);
        
        parent::postCallback($jp);
    }

    function getServerByKey($key) {

    }
}