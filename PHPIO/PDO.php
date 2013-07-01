<?php

class PHPIO_PDO extends PHPIO_Hook_Class {
    const classname = 'PDO';
    var $hooks = array(
        'beginTransaction',
        'commit',
        '__construct',
        //'errorCode',
        //'errorInfo',
        'exec',
        //'getAttribute',
        //'getAvailableDrivers',
        //'inTransaction',
        'lastInsertId',
        'prepare',
        'query',
        //'quote',
        'rollBack',
        //'setAttribute',
    );

    var $links = array();

    function __construct_post($jp) {
        $dsn = $this->args[0];
        $object_id = $this->getObjectId($this->object);
        $this->links[ $object_id ] = $this->fmtLink($dsn,$object_id);
        $this->postCallback($this->args, $this->traces, $jp->getReturnedValue());
    }

    function prepare_pre($jp) {
    }

    function prepare_post($jp) {
        $result = $jp->getReturnedValue();
        if ( $result instanceof PDOStatement ) {
            $sth = $this->getObjectId($result);
            $link = $this->getLink($this->object);
            PHPIO::$links[$sth] = $link;
        }
    }

    function postCallback($args, $traces, $result) {
        $traces[1]['link'] = $this->getLink($this->object);
        if ( $result === false ) {
            list(,$errno, $error) = $this->object->errorInfo();
            $traces[1]['errno'] = $errno;
            $traces[1]['error'] = $error;
        }
        parent::postCallback($args, $traces, $result);
    }

    function getLink($object) {
        $object_id = $this->getObjectId($object);
        return $this->links[ $object_id ];
    }

    function fmtLink($dsn, $object_id) {
        /*
        pgsql:host=localhost;port=5432;dbname=testdb;user=bruce;password=mypass
        oci:dbname=//localhost:1521/mydb
        odbc:DRIVER={IBM DB2 ODBC DRIVER};HOSTNAME=localhost;PORT=50000;DATABASE=SAMPLE;PROTOCOL=TCPIP;UID=db2inst1;PWD=ibmdb2;
        cubrid:host=localhost;port=33000;dbname=demodb
        dblib:host=localhost;dbname=testdb
        ibm:DRIVER={IBM DB2 ODBC DRIVER};DATABASE=testdb;HOSTNAME=11.22.33.444;PORT=56789;PROTOCOL=TCPIP;
        informix:host=host.domain.com; service=9800; database=common_db; server=ids_server; protocol=onsoctcp; EnableScrollableCursors=1
        mysql:host=localhost;port=3307;dbname=testdb
        mysql:unix_socket=/tmp/mysql.sock;dbname=testdb
        sqlite:/opt/databases/mydb.sq3
        sqlite2:/opt/databases/mydb.sq2
        sqlsrv:Server=localhost,1521;Database=testdb
        */

        list(, $object_num) = explode('#', $object_id);

        list($driver, $params) = explode(':', $dsn, 2);

        $options = array();
        foreach( explode(';', $params) as $param) {
            $param = trim($param);
            list($key, $val) = explode('=', $param);
            $options[$key] = $val;
        }

        if ( empty($options) ) {
            $options['host'] = $params;
        }

        switch ($driver) {
            case 'mysql':
                if ( isset($options['host']) ) {
                    $default = array('port'=>3306);
                    $options = $options + $default;
                    return $options['host'].':'.$options['port'].'/'.$options['dbname'].'#'.$object_num;
                } else {
                    return 'unix://'.$options['unix_socket'].'#'.$object_num;
                }
        }
    }
}