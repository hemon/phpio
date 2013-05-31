<?php 
if ( !function_exists('fc_add_pre') ) dl('funcall.so');

$logs = array();

function __pre_cb() {
	list ($args) = func_get_args();
}

function __post_cb() {
	list (, $result, $t) = func_get_args();
	global $logs;
	$traces = debug_backtrace();
	$traces[1]['result'] = $result;
	$traces[1]['t'] = $t;
	
	$object_id = spl_object_hash($traces[1]['object']);
	$logs['Redis'][$object_id][] = $traces[1];
}

$functions = array(
	'Redis::connect',
	'Redis::set',
	'Redis::get',
);

array_walk($functions, function($func){
	//fc_add_pre($func,'__pre_cb');
	fc_add_post($func,'__post_cb');
});

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


var_dump($logs);