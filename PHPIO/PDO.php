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
        //'prepare',
        'query',
        //'quote',
        'rollBack',
        //'setAttribute',
     );
}