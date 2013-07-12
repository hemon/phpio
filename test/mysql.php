<?php 
mysql_connect('127.0.0.1','root','');
mysql_select_db('test');
mysql_query('show databases');

mysql_connect('127.0.0.1','root','', true);
mysql_select_db('test');
mysql_query('set names utf8');
