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

    function _post__construct($jp) {
    }

    function _pre_Prepare($jp) {

    }
    function _post_Prepare($jp) {
        $result = $jp->getReturnedValue();
        if ( $result instanceof PDOStatement ) {
            $sth = $this->getObjectId($result);
            $pdo = $this->getObjectId($jp->getObject());
            PHPIO::$links[$sth] = $pdo;
        }
    }
}