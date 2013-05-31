<?php 
if ( !function_exists('fc_add_pre') ) dl('funcall.so');

require '../PHPIO/PDO.php';
PHPIO_PDO::init();
PHPIO_PDO::hook();

$db[0] = new PDO('mysql:dbname=test;host=192.168.203.81', 'root', 'zzzizzz1');
$db[1] = new PDO('mysql:dbname=test;host=192.168.203.82', 'root', 'zzzizzz1');

$db[0]->exec('set names utf8');
$db[0]->query('select * from cats');

$db[1]->exec('set names utf8');
$db[1]->query('select * from cats');

var_dump(PHPIO_PDO::$logs);