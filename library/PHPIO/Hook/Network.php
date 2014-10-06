<?php

class PHPIO_Hook_Network extends PHPIO_Hook_Func {
	const classname = 'Network';
	var $link = 'localhost';
	var $hooks = array(
		'gethostbyaddr',
        'gethostbyname',
        'gethostbynamel',
        'fsockopen',
	);

    function postCallback($jp) {
        $this->trace['link'] = $this->link;
        $this->trace['cmd'] = (is_array($this->args[0]) ? implode(' ',$this->args[0]) : $this->args[0]);
        
        parent::postCallback($jp);
    }
}
