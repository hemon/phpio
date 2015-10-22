<?php

class PHPIO_Hook_UDF extends PHPIO_Hook_Func {
    const classname = 'UDF';
    var $link = '';
    var $hooks = array(
    );

    function __construct() {
        $this->hooks = explode(',', $_COOKIE[PHPIO_PROFILE]);
    }

    function postCallback($jp) {
        $this->trace['link'] = $this->link;
        $this->trace['cmd'] = (is_array($this->args[0]) ? implode(' ',$this->args[0]) : $this->args[0]);

        parent::postCallback($jp);
    }
}
