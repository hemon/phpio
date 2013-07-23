<?php
$m = new Memcached();
$m->setOption(Memcached::OPT_DISTRIBUTION,Memcached::DISTRIBUTION_CONSISTENT);
$m->addServer('10.237.36.113', 11211);
$m->addServer('10.237.36.115', 11211);
$m->addServer('10.237.36.129', 11211);

var_dump($m->set('foo2',1));
var_dump($m->get('foo2'));
var_dump($m->set('boo3',1));
var_dump($m->get('boo3'));
var_dump($m->set('1oo5',1));
var_dump($m->get('1oo5'));

$keys = array(
    'key1' => 'value1',
    'key2' => 'value2',
    'key3' => 'value3',
    'sdasf' => 'value1',
    'ksddy2' => 'value2',
    'k999ey3' => 'value3',
);
var_dump($m->setMulti($keys));
var_dump($m->getMulti(array_keys($keys)));

