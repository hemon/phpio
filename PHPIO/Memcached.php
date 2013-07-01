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
        'getServerByKey',
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
        $this->postCallback($this->args, $this->traces, $jp->getReturnedValue());
    }

    function addServers_post($jp) {
        foreach ( $this->args as $server ) {
            $this->link[] = $this->getLink($server);
        }
        $this->postCallback($this->args, $this->traces, $jp->getReturnedValue());
    }

    function connect_post($jp) {
        $this->link = $this->getLink($this->args);
        $this->postCallback($this->args, $this->traces, $jp->getReturnedValue());
    }

    function pconnect_post($jp) {
        $this->connect_post($jp);
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

        if ( $result ) {
            $traces[1]['status'] = $this->object->getResultCode();
        } else {
            $traces[1]['errno'] = $this->object->getResultCode();
            $traces[1]['error'] = $this->object->getResultMessage();
        }
        
        parent::postCallback($args, $traces, $result);
    }
}