<?php
class PHPIO_Reflection_Redis {
    const AFTER = '';
    const BEFORE = '';
    const OPT_SERIALIZER = 1;
    const OPT_PREFIX = 2;
    const SERIALIZER_NONE = 0;
    const SERIALIZER_PHP = 1;
    const SERIALIZER_IGBINARY = 2;
    const MULTI = '';
    const PIPELINE = '';
    const REDIS_NOT_FOUND = 0;
    const REDIS_STRING = 1;
    const REDIS_SET = 2;
    const REDIS_LIST = 3;
    const REDIS_ZSET = 4;
    const REDIS_HASH = 5;
    function __construct( ) {}
    function connect( $host, $port = 6379, $timeout = 0.0 ) {}
    function open( $host, $port = 6379, $timeout = 0.0 ) {}
    function pconnect( $host, $port = 6379, $timeout = 0.0 ) {}
    function popen( $host, $port = 6379, $timeout = 0.0 ) {}
    function close( ) {}
    function setOption( $name, $value ) {}
    function getOption( $name ) {}
    function ping( ) {}
    function get( $key ) {}
    function set( $key, $value, $timeout = 0.0 ) {}
    function setex( $key, $ttl, $value ) {}
    function setnx( $key, $value ) {}
    function del( $key1, $key2 = null, $key3 = null ) {}
    function delete( $key1, $key2 = null, $key3 = null ) {}
    function multi( ) {}
    function exec( ) {}
    function discard( ) {}
    function watch( $key ) {}
    function unwatch( ) {}
    function subscribe( $channels, $callback ) {}
    function psubscribe( $patterns, $callback ) {}
    function publish( $channel, $message ) {}
    function exists( $key ) {}
    function incr( $key ) {}
    function incrByFloat( $key, $increment ) {}
    function incrBy( $key, $value ) {}
    function decr( $key ) {}
    function decrBy( $key, $value ) {}
    function getMultiple( array $keys ) {}
    function lPush( $key, $value1, $value2 = null, $valueN = null ) {}
    function rPush( $key, $value1, $value2 = null, $valueN = null ) {}
    function lPushx( $key, $value ) {}
    function rPushx( $key, $value ) {}
    function lPop( $key ) {}
    function rPop( $key ) {}
    function blPop( array $keys ) {}
    function brPop( array $keys ) {}
    function lLen( $key ) {}
    function lSize( $key ) {}
    function lIndex( $key, $index ) {}
    function lGet( $key, $index ) {}
    function lSet( $key, $index, $value ) {}
    function lRange( $key, $start, $end ) {}
    function lGetRange( $key, $start, $end ) {}
    function lTrim( $key, $start, $stop ) {}
    function listTrim( $key, $start, $stop ) {}
    function lRem( $key, $value, $count ) {}
    function lRemove( $key, $value, $count ) {}
    function lInsert( $key, $position, $pivot, $value ) {}
    function sAdd( $key, $value1, $value2 = null, $valueN = null ) {}
    function sRem( $key, $member1, $member2 = null, $memberN = null ) {}
    function sRemove( $key, $member1, $member2 = null, $memberN = null ) {}
    function sMove( $srcKey, $dstKey, $member ) {}
    function sIsMember( $key, $value ) {}
    function sContains( $key, $value ) {}
    function sCard( $key ) {}
    function sPop( $key ) {}
    function sRandMember( $key ) {}
    function sInter( $key1, $key2, $keyN = null ) {}
    function sInterStore( $dstKey, $key1, $key2, $keyN = null ) {}
    function sUnion( $key1, $key2, $keyN = null ) {}
    function sUnionStore( $dstKey, $key1, $key2, $keyN = null ) {}
    function sDiff( $key1, $key2, $keyN = null ) {}
    function sDiffStore( $dstKey, $key1, $key2, $keyN = null ) {}
    function sMembers( $key ) {}
    function sGetMembers( $key ) {}
    function getSet( $key, $value ) {}
    function randomKey( ) {}
    function select( $dbindex ) {}
    function move( $key, $dbindex ) {}
    function rename( $srcKey, $dstKey ) {}
    function renameKey( $srcKey, $dstKey ) {}
    function renameNx( $srcKey, $dstKey ) {}
    function expire( $key, $ttl ) {}
    function pExpire( $key, $ttl ) {}
    function setTimeout( $key, $ttl ) {}
    function expireAt( $key, $timestamp ) {}
    function pExpireAt( $key, $timestamp ) {}
    function keys( $pattern ) {}
    function getKeys( $pattern ) {}
    function dbSize( ) {}
    function auth( $password ) {}
    function bgrewriteaof( ) {}
    function slaveof( $host = '127.0.0.1', $port = 6379 ) {}
    function object( $string = '', $key = '' ) {}
    function save( ) {}
    function bgsave( ) {}
    function lastSave( ) {}
    function type( $key ) {}
    function append( $key, $value ) {}
    function getRange( $key, $start, $end ) {}
    function substr( $key, $start, $end ) {}
    function setRange( $key, $offset, $value ) {}
    function strlen( $key ) {}
    function getBit( $key, $offset ) {}
    function setBit( $key, $offset, $value ) {}
    function bitCount( $key ) {}
    function bitOp( $operation, $retKey, $key1, $key2, $key3 = null ) {}
    function flushDB( ) {}
    function flushAll( ) {}
    function sort( $key, $option = null ) {}
    function info( $option = null ) {}
    function resetStat( ) {}
    function ttl( $key ) {}
    function pttl( $key ) {}
    function persist( $key ) {}
    function mset( array $array ) {}
    function mget( array $array ) {}
    function msetnx( array $array ) {}
    function rpoplpush( $srcKey, $dstKey ) {}
    function brpoplpush( $srcKey, $dstKey, $timeout ) {}
    function zAdd( $key, $score1, $value1, $score2 = null, $value2 = null, $scoreN = null, $valueN = null ) {}
    function zRange( $key, $start, $end, $withscores = null ) {}
    function zRem( $key, $member1, $member2 = null, $memberN = null ) {}
    function zDelete( $key, $member1, $member2 = null, $memberN = null ) {}
    function zRevRange( $key, $start, $end, $withscore = null ) {}
    function zRangeByScore( $key, $start, $end, array $options = array() ) {}
    function zRevRangeByScore( $key, $start, $end, array $options = array() ) {}
    function zCount( $key, $start, $end ) {}
    function zRemRangeByScore( $key, $start, $end ) {}
    function zDeleteRangeByScore( $key, $start, $end ) {}
    function zRemRangeByRank( $key, $start, $end ) {}
    function zDeleteRangeByRank( $key, $start, $end ) {}
    function zCard( $key ) {}
    function zSize( $key ) {}
    function zScore( $key, $member ) {}
    function zRank( $key, $member ) {}
    function zRevRank( $key, $member ) {}
    function zIncrBy( $key, $value, $member ) {}
    function zUnion($Output, $ZSetKeys, array $Weights = null, $aggregateFunction = 'SUM') {}
    function zInter($Output, $ZSetKeys, array $Weights = null, $aggregateFunction = 'SUM') {}
    function hSet( $key, $hashKey, $value ) {}
    function hSetNx( $key, $hashKey, $value ) {}
    function hGet($key, $hashKey) {}
    function hLen( $key ) {}
    function hDel( $key, $hashKey1, $hashKey2 = null, $hashKeyN = null ) {}
    function hKeys( $key ) {}
    function hVals( $key ) {}
    function hGetAll( $key ) {}
    function hExists( $key, $hashKey ) {}
    function hIncrBy( $key, $hashKey, $value ) {}
    function hIncrByFloat( $key, $field, $increment ) {}
    function hMset( $key, $hashKeys ) {}
    function hMGet( $key, $hashKeys ) {}
    function config( $operation, $key, $value ) {}
    function evaluate( $script, $args = array(), $numKeys = 0 ) {}
    function evalSha( $scriptSha, $args = array(), $numKeys = 0 ) {}
    function evaluateSha( $scriptSha, $args = array(), $numKeys = 0 ) {}
    function script( $command, $script ) {}
    function getLastError() {}
    function clearLastError() {}
    function _prefix( $value ) {}
    function _unserialize( $value ) {}
    function dump( $key ) {}
    function restore( $key, $ttl, $value ) {}
    function migrate( $host, $port, $key, $db, $timeout ) {}
    function time() {}
}

class PHPIO_Reflection_RedisArray {
    function __construct($name = '', array $hosts = NULL, array $opts = NULL) {}
    function _hosts() {}
    function _function() {}
    function _target($key) {}
    function _rehash() {}
}