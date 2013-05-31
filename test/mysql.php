<?php 
if ( !function_exists('fc_add_pre') ) dl('funcall.so');

function __pre_cb($args) {
	//print_r($args);
	var_dump(func_get_args());
}

function __post_cb($args, $result, $t) {
	//$traces = debug_backtrace();
	//$func = $traces[1]['function'];
	//print_r(array($func, $args, $result, $t));
	var_dump(func_get_args());
}

$functions = array(
	'mysql_connect',
	'mysql_select_db',
	'mysql_query'
);

array_walk($functions, function($func){
	fc_add_pre($func,'phpio_mysql::__pre_cb');
	fc_add_post($func,'__post_cb');
});


mysql_connect('192.168.203.81','root','zzzizzz1');
mysql_select_db('test');
mysql_query('set names utf8');

mysql_connect('192.168.203.82','root','zzzizzz1');
mysql_select_db('test');
mysql_query('set names utf8');