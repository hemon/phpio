<?php

$redis[0] = new Redis;
$redis[0]->connect('127.0.0.1');
$redis[0]->set('key', rand());
$redis[0]->get('key');

$redis[1] = new Redis;
$redis[1]->connect('127.0.0.1');
$redis[1]->set('key', rand());
//$redis[1]->get('key');

$redis[0]->set('key', rand());
$redis[1]->set('key', rand());

//$redis[0]->get('key');
//$redis[1]->get('key');
$method = 'hset';
$redis[0]->$method("hash","key","val");
call_user_func_array(array($redis[0], "hset"), array("hash","key","2"));
call_user_func_array(array($redis[0], "hget"), array("hash","key"));