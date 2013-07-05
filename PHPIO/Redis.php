<?php

class PHPIO_Redis extends PHPIO_Hook_Class {
	const classname = 'Redis';
	var $hooks = array(
        //'__construct',
        'connect',
        'pconnect',
        'close',
        'ping',
        'get',
        'set',
        'setex',
        'setnx',
        'getSet',
        'randomKey',
        'renameKey',
        'renameNx',
        'getMultiple',
        'exists',
        'delete',
        'incr',
        'incrBy',
        'decr',
        'decrBy',
        'type',
        'append',
        'getRange',
        'setRange',
        'getBit',
        'setBit',
        'strlen',
        'getKeys',
        'sort',
        'sortAsc',
        'sortAscAlpha',
        'sortDesc',
        'sortDescAlpha',
        'lPush',
        'rPush',
        'lPushx',
        'rPushx',
        'lPop',
        'rPop',
        'blPop',
        'brPop',
        'lSize',
        'lRemove',
        'listTrim',
        'lGet',
        'lGetRange',
        'lSet',
        'lInsert',
        'sAdd',
        'sSize',
        'sRemove',
        'sMove',
        'sPop',
        'sRandMember',
        'sContains',
        'sMembers',
        'sInter',
        'sInterStore',
        'sUnion',
        'sUnionStore',
        'sDiff',
        'sDiffStore',
        'setTimeout',
        'save',
        'bgSave',
        'lastSave',
        'flushDB',
        'flushAll',
        'dbSize',
        'auth',
        'ttl',
        'persist',
        'info',
        'resetStat',
        'select',
        'move',
        'bgrewriteaof',
        'slaveof',
        'object',
        'mset',
        'msetnx',
        'rpoplpush',
        'brpoplpush',
        'zAdd',
        'zDelete',
        'zRange',
        'zReverseRange',
        'zRangeByScore',
        'zRevRangeByScore',
        'zCount',
        'zDeleteRangeByScore',
        'zDeleteRangeByRank',
        'zCard',
        'zScore',
        'zRank',
        'zRevRank',
        'zInter',
        'zUnion',
        'zIncrBy',
        'expireAt',
        'hGet',
        'hSet',
        'hSetNx',
        'hDel',
        'hLen',
        'hKeys',
        'hVals',
        'hGetAll',
        'hExists',
        'hIncrBy',
        'hMset',
        'hMget',
        'multi',
        'discard',
        'exec',
        'pipeline',
        'watch',
        'unwatch',
        'publish',
        'subscribe',
        'unsubscribe',
        'getOption',
        'setOption',
        'open',
        'popen',
        'lLen',
        'sGetMembers',
        'mget',
        'expire',
        'zunionstore',
        'zinterstore',
        'zRemove',
        'zRem',
        'zRemoveRangeByScore',
        'zRemRangeByScore',
        'zRemRangeByRank',
        'zSize',
        'substr',
        'rename',
        'del',
        'keys',
        'lrem',
        'ltrim',
        'lindex',
        'lrange',
        'scard',
        'srem',
        'sismember',
        'zrevrange',
    );

    function connect_post($jp) {
        $this->link = $this->getLink($this->args);
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
        $this->trace['link'] = $this->link;
        $this->trace['cmd'] = (is_array($this->args[0]) ? implode(' ',$this->args[0]) : $this->args[0]);
        
        parent::postCallback($jp);
    }
}
