<?php

class PHPIO_Mysql extends PHPIO_Hook_Func {
	const classname = 'Mysql';
	var $hooks = array(
		//'mysql_affected_rows',
		'mysql_connect',
		'mysql_create_db',
		'mysql_db_query',
		'mysql_drop_db',
		'mysql_get_host_info',
		'mysql_get_proto_info',
		'mysql_get_server_info',
		'mysql_info',
		'mysql_insert_id',
		'mysql_list_dbs',
		'mysql_list_fields',
		'mysql_list_processes',
		'mysql_list_tables',
		'mysql_pconnect',
		'mysql_ping',
		'mysql_query',
		'mysql_select_db',
		'mysql_set_charset',
		'mysql_stat',
		'mysql_thread_id',
		'mysql_unbuffered_query',
	);
	var $link_hooks = array(
		'mysql_connect',
		'mysql_pconnect'
	);
	
	function postCallback($args, $traces, $result) {
		if ( $result ) {
			$traces[1]['status'] = mysql_affected_rows();
		} else {
			$traces[1]['errno'] = mysql_errno();
			$traces[1]['error'] = mysql_error();
		}
		
		parent::postCallback($args, $traces, $result);
	}
}