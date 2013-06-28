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

    function postCallback($args, $traces, $result) {
        if ( $result ) {
            $traces[1]['status'] = $this->object->getResultCode();
        } else {
            $traces[1]['errno'] = $this->object->getResultCode();
            $traces[1]['error'] = $this->object->getResultMessage();
        }
        
        parent::postCallback($args, $traces, $result);
    }
}