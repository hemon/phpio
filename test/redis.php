<?php 
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