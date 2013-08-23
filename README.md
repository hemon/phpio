# PHPIO
PHPIO is prism for php. it's base on [aop-php](http://aop-php.github.com) extension. focus on monitoring IO operation, such like MySQL, Memcache, Redis, APC and so on.

* Common  
connection's host and port, call trace, function args, response and error message.
* MySQL, PDO    
record raw SQL query stirng and affected rows.
* Curl  
record raw http header(request & response), and reporting the full infomation of time,speed and size.
* Memcached  
record key for operation, the real server where key is mapped to.
* Redis
* Exception, Error  
recode Exception and All Error types.
* Call Graph  
call graph is svg format, click map node redirect to source.
* Request Flow  
add debug flag in curl sub_request, track web services call. 
* Source viewer  

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
http://path/to/phpio/www/  
```
