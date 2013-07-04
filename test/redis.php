<?php 
//aop_add_after('*()',function($jp){var_dump($jp);});

$redis[0] = new Redis;
$redis[0]->connect('127.0.0.1');
$redis[0]->set('key', rand());
$redis[0]->get('key');

$redis[1] = new Redis;
$redis[1]->connect('127.0.0.1');
$redis[1]->set('key', rand());
$redis[1]->get('key');

$redis[0]->set('key', rand());
$redis[1]->set('key', rand());
$redis[0]->get('key');
$redis[1]->get('key');


call_user_func(array($redis[0],'get'), 'key');
call_user_func_array(array($redis[0],'get'), array('key'));
