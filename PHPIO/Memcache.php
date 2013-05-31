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
}