<?php 
if ( !function_exists('fc_add_pre') ) dl('funcall.so');

require '../PHPIO/Redis.php';
PHPIO_Redis::init();
PHPIO_Redis::hook();

$redis[0] = new Redis;
$redis[0]->connect('192.168.203.81');
$redis[0]->set('key', rand());
$redis[0]->get('key');

$redis[1] = new Redis;
$redis[1]->connect('192.168.203.82');
$redis[1]->set('key', rand());
$redis[1]->get('key');

$redis[0]->set('key', rand());
$redis[1]->set('key', rand());
$redis[0]->get('key');
$redis[1]->get('key');

var_dump(PHPIO_Redis::$logs);