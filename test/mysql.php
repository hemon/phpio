<?php 
mysql_connect('127.0.0.1','root','zzzizzz1');
mysql_select_db('test');
mysql_query('set names utf8');

mysql_connect('127.0.0.1','root','zzzizzz1', true);
mysql_select_db('test');
mysql_query('set names utf8');