<?php
aop_add_after('redis->get()',function($jp){var_dump('aop_add_after',$jp->getArguments());});
aop_add_after('redis2->get()',function($jp){var_dump('aop_add_after',$jp->getArguments());});

class redis2 {
    function connect($host){}
    function set($key,$val){}
    function get($key){}
}

class myredis {
    function __construct($redis) {
        $this->_redis = $redis;
    }

    public function __call($name,$args)
    {
        return call_user_func_array(array($this->_redis, $name), $args);
    }
}

$redis = new redis();
$redis->connect('127.0.0.1');
$redis->set('key1',rand());
$redis->get('key1');

$myredis = new myredis($redis);
$myredis->set('key2',rand());
$myredis->get('key2');

$redis2 = new redis2();
$redis2->connect('127.0.0.1');
$redis2->set('key3',rand());
$redis2->get('key3');

$myredis2 = new myredis($redis2);
$myredis2->set('key4',rand());
$myredis2->get('key4');

