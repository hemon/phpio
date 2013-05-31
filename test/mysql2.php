<?php 
if ( !function_exists('fc_add_pre') ) dl('funcall.so');

require '../PHPIO/Mysql.php';
PHPIO_Mysql::init();
PHPIO_Mysql::hook();

$mysql[0] = mysql_connect('192.168.203.81','root','zzzizzz1');
mysql_select_db('test');
mysql_query('set names utf8');

$mysql[1] = mysql_connect('192.168.203.82','root','zzzizzz1');
mysql_select_db('test');
mysql_query('set names utf8');

mysql_select_db('test', $mysql[0]);

var_dump(PHPIO_Mysql::$logs);