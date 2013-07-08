<?php 
mysql_connect('127.0.0.1','root','Seattle01!');
mysql_select_db('test');
mysql_query('show databases');

mysql_connect('127.0.0.1','root','Seattle01!', true);
mysql_select_db('test');
mysql_query('set names utf8');
