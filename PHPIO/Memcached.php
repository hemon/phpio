<?php

class PHPIO_Memcached extends PHPIO_Hook_Class {
	const classname = 'Memcached';
	var $hooks = array(
        'add',
        'addByKey',
        'addServer',
        'addServers',
        'append',
        'appendByKey',
        'cas',
        'casByKey',
        '__construct',
        'decrement',
        'delete',
        'deleteByKey',
        'fetch',
        'fetchAll',
        'flush',
        'get',
        'getByKey',
        'getDelayed',
        'getDelayedByKey',
        'getMulti',
        'getMultiByKey',
        'getOption',
        //'getResultCode',
        //'getResultMessage',
        //'getServerByKey',
        //'getServerList',
        'getStats',
        'getVersion',
        'increment',
        'prepend',
        'prependByKey',
        'replace',
        'replaceByKey',
        'set',
        'setByKey',
        'setMulti',
        'setMultiByKey',
        'setOption',
    );

    function addServer_post($jp) {
        $this->link[] = $this->getLink($this->args);
        $this->postCallback($jp);
    }

    function addServers_post($jp) {
        foreach ( $this->args as $server ) {
            $this->link[] = $this->getLink($server);
        }
        $this->postCallback($jp);
    }

    function connect_post($jp) {
        $this->link = $this->getLink($this->args);
        $this->postCallback($jp);
    }

    function pconnect_post($jp) {
        $this->connect_post($jp);
    }

    function getLink($args) {
        $link = $args[0].":".$args[1];
        return $link;
    }

    function postCallback($jp) {
        $this->trace['link'] = (is_array($this->link) ? implode(';',$this->link) : $this->link);
        $this->trace['cmd'] = (is_array($this->args[0]) ? implode(' ',$this->args[0]) : $this->args[0]);
        if ( $this->result !== false ) {
            $this->trace['status'] = $this->object->getResultCode();
        } else {
            $this->trace['errno'] = $this->object->getResultCode();
            $this->trace['error'] = $this->object->getResultMessage();
        }
        
        parent::postCallback($jp);
    }
}
