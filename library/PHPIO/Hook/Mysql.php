<?php

class PHPIO_Hook_Mysql extends PHPIO_Hook {
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

    function mysql_insert_id_post($jp) {
        $this->trace['cmd'] = 'SELECT LAST_INSERT_ID()';
        $this->postCallback($jp);
    }

    function mysql_pconnect_post($jp) {
        $this->mysql_connect_post($jp);
    }

    function mysql_connect_post($jp) {
        $this->link = $this->getLink($this->args);
        $this->postCallback($jp);
    }

    function getLink($args) {
    	$link = $args[0];
    	if ( strpos($link, ':') === false ) {
    		$link .= ':3306';
    	}
        return $link;
    }
	
	function postCallback($jp) {
		$this->trace['link'] = $this->link;

		if ( $this->result === false ) {
			$this->trace['errno'] = mysql_errno();
			$this->trace['error'] = mysql_error();
		}

		parent::postCallback($jp);
	}

	function mysql_query_post($jp) {
		if ( is_resource($this->result) || $this->result === true ) {
			$this->trace['rowcount'] = mysql_affected_rows();
		}

		$this->postCallback($jp);
	}
}
