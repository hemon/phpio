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
        $this->postCallback($this->args, $this->traces, $jp->getReturnedValue());
    }

    function pconnect_post($jp) {
        $this->connect_post($jp);
    }

    function connect_post($jp) {
        $this->link = $this->getLink($this->args);
        $this->postCallback($this->args, $this->traces, $jp->getReturnedValue());
    }

    function getLink($args) {
        if ( substr($args[0],0,4) == 'unix' ) {
            $link = $args[0];
        } else {
            $link = $args[0].":".$args[1];
        }
        return $link;
    }

    function postCallback($args, $traces, $result) {
        $traces[1]['link'] = (is_array($this->link) ? implode(';',$this->link) : $this->link);
        
        parent::postCallback($args, $traces, $result);
    }

    function getServerByKey($key) {
        
    }
}