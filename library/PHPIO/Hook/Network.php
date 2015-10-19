<?php

class PHPIO_Hook_Core extends PHPIO_Hook_Func {
    const classname = 'Core';
    var $link = 'localhost';
    var $hooks = array(
         'gethostbyaddr',
         'gethostbyname',
         'gethostbynamel',
         'fsockopen',
         'header'
    );

    function postCallback($jp) {
        $this->trace['link'] = $this->link;
        $this->trace['cmd'] = (is_array($this->args[0]) ? implode(' ',$this->args[0]) : $this->args[0]);

        parent::postCallback($jp);
    }
}
