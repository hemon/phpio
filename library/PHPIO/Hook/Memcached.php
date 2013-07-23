<?php

class PHPIO_Hook_Memcached extends PHPIO_Hook {
	const classname = 'Memcached';
    var $currentKey = '';
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
        //'fetch',
        //'fetchAll',
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

    var $key_pos = array(
        'add' => 0,
        'addByKey' => 0,
        'append' => 0,
        'appendByKey' => 0,
        'cas' => 1,
        'casByKey' => 1,
        'decrement' => 0,
        'delete' => 0,
        'deleteByKey' => 0,
        'get' => 0,
        'getByKey' => 0,
        'getDelayed' => 0,
        'getDelayedByKey' => 0,
        'getMulti' => 0,
        'getMultiByKey' => 0,
        'increment' => 0,
        'prepend' => 0,
        'prependByKey' => 0,
        'replace' => 0,
        'replaceByKey' => 0,
        'set' => 0,
        'setByKey' => 0,
        'setMulti' => 0,
        'setMultiByKey' => 0,
    );

    function postCallback($jp) {
        $method = $jp->getMethodName();
        $this->currentKey = $this->getCurrentKey($method);
        $this->serverList = $this->object->getServerList();
        // those are Client Operations
        if ( in_array($method, array('__construct','setOption','addServer','addServers')) ) {
            $this->trace['link'] = '';
        } else {
            $this->trace['link'] = $this->getLink($this->serverList, $this->currentKey);
        }
        $this->trace['cmd'] = $this->getCmd();

        if ( $this->result !== false ) {
            $this->trace['status'] = $this->object->getResultCode();
        } else {
            $this->trace['errno'] = $this->object->getResultCode();
            $this->trace['error'] = $this->object->getResultMessage();
        }
        
        parent::postCallback($jp);
    }

    function getCmd() {
        if ( !empty($this->currentKey) ) {
            return (is_array($this->currentKey) ? implode(' ', $this->currentKey) : $this->currentKey);
        } elseif ( !empty($this->args[0]) ) {
            return $this->args[0];
        }
    }

    function getCurrentKey($method) {
        $current_key = '';
        if ( isset($this->key_pos[$method]) ) {
            $key_pos = $this->key_pos[$method];
            $current_key = $this->args[$key_pos];
            // setMulti $items is a key/value pairs
            if ( $method === 'setMulti' ) {
                $current_key = array_keys($current_key);
            }
        }
        return $current_key;
    }

    function getLink($serverList, $currentKey) {
        switch ( count($serverList) ) {
            case 0:
                return '';
            case 1:
                return $serverList[0]['host'].':'.$serverList[0]['port'];
            default:
                if ( empty($currentKey) ) {
                    return '';
                } else {
                    return is_array($currentKey) ? 
                        $this->getServerByKeys($currentKey) : 
                        $this->getServerByKey($currentKey);
                }
        }
    }

    function getServerByKeys($key) {
        if ( !is_array($key) ) {
            $key = array($key);
        }

        $links = array();
        foreach($key as $k) {
            $server = $this->getServerByKey($k);
            $links[$server][] = $k;
        }
        return $links;
    }

    function getServerByKey($key) {
        $server = $this->object->getServerByKey($key);
        return $server['host'].':'.$server['port'];
    }
}
