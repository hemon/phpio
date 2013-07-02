<?php 

$db[0] = new PDO('mysql:dbname=test;host=127.0.0.1', 'root', 'zzzizzz1');
$db[1] = new PDO('mysql:dbname=test;host=127.0.0.1', 'root', 'zzzizzz1');

$db[0]->exec('set names utf8');
$db[0]->query('set names utf8');

$db[1]->exec('set names utf8');
$db[1]->query('set names utf8');

