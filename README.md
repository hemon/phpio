# PHPIO
Cross request trace for PHP,like google's dapper,twitter's zipkin.  
It's base on [AOP-PHP](http://aop-php.github.com) extension. 
PHPIO is focus on trace IO operation, such like Curl, MySQL, Memcache, Redis, APC.   
It's easily write your own Hooker monitor function/class operation in 10 line code.

* Common  
connection's host and port, call trace, function args, response and error message.
* MySQL, PDO    
record raw SQL query stirng and affected rows.
* Curl  
record raw http header(request & response), and reporting the full infomation of time,speed and size.
* Memcached, Redis  
record key for operation, the real server where key is mapped to.
* Exception, Error  
record errno and errmsg for Exception and Error.
* Call Graph  
call graph is svg format, click map node redirect to source.
* Source viewer  
you can see all your source code which been executed.
* Cross Request Tracking   
A unique request_id is auto inject in Cookie for track http request from frontpage to webservice api,
just use a Redis logger can collecting logs from all your php web servers.


[DEMO](http://hemon.github.io/phpio/demo/index.html)

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

## get phpio   
download tarball  
```
wget https://github.com/hemon/phpio/tarball/master -O phpio.tar.gz  
tar xvf phpio.tar.gz  
```
or git clone   
```
git clone https://hemon@github.com/hemon/phpio
```

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

recommand firfox addon [easy-xdebug](https://addons.mozilla.org/firefox/addon/easy-xdebug-with-moveable-/), it can auto append user defined cookie param in request(default is XDEBUG_PROFILE=1): 


in php-cli mode, use `export` command to set param for $_SERVER 
```
export XDEBUG_PROFILE=1
```

## view profile
make phpio/www is accessible:  
```
http://localhost/phpio/www/  
```
