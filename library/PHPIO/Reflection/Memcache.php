<?php
class PHPIO_Reflection_Memcache {
    function add($key,$var,$flag,$expire){}
    function addServer($host,$port=11211,$persistent,$weight,$timeout,$retry_erval,$status,$failure_callback,$timeoutms){}
    function close(){}
    function connect($host,$port,$timeout){}
    function decrement($key,$value=1){}
    function delete($key,$timeout){}
    function flush(){}
    function get($key,&$flags){}
    function getExtendedStats($type,$slabid,$limit=100){}
    function getServerStatus($host,$port=11211){}
    function getStats($type,$slabid,$limit=100){}
    function getVersion(){}
    function increment($key,$value=1){}
    function pconnect($host,$port,$timeout){}
    function replace($key,mixed$var,$flag,$expire){}
    function set($key,mixed$var,$flag,$expire){}
    function setCompressThreshold($threshold,$min_savings){}
    function setServerParams($host,$port=11211,$timeout,$retry_erval=false,$status,$failure_callback){}
}

class PHPIO_Reflection_MemcachePool extends PHPIO_Reflection_Memcache {
}
