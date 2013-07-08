# PHPIO
PHPIO is prism for php. it's base on [aop-php](http://aop-php.github.com) extension, force on monitoring of IO operation, such like MySQL, Memcache, Redis, APC and so on.

* Common  
host:port, call trace, function args, response, error message.
* MySQL, PDO    
record raw SQL querystirng, affetced rows.
* Curl  
record raw http header(request & response), and report the full infomation of time,speed and size.
* Memcached, Redis  
record key for operation


![Screenshot](//raw.github.com/hemon/phpio/master/www/img/screenshot.png)

## Install aop extension
```
pecl install aop-beta
```
```
[aop]  
extension=aop.so 
```
if coredump ?  
1. change you aop extension version (the pecl version is stable than github)  
2. rebuild php

## prepend phpio.php
php.ini  
```
auto_prepend_file = /path/to/phpio/phpio.php  
```
php-fpm  
```
php_admin_value[auto_prepend_file] = /path/to/phpio/phpio.php
```

## start profile
run you php programs with param XDEBUG_PROFILE=1  
```
$_REQUEST['XDEBUG_PROFILE']=1  
$_COOKIE['XDEBUG_PROFILE']=1  
$_SERVER['XDEBUG_PROFILE']=1  
```

for example : 
http://www.example.com/index.php?XDEBUG_PROFILE=1 

recommand firfox addon [easy-xdebug](https://addons.mozilla.org/firefox/addon/easy-xdebug-with-moveable-/), it can auto append $_COOKIE['XDEBUG_PROFILE'] in requst: 


in cli mode, use export set parame to $_SERVER:
```
export XDEBUG_PROFILE=1
```

## view profile
last profile  
http://www.yousite.com/phpio/www/  
profile_id  
http://www.yousite.com/phpio/www/?profile=[profile_id]

