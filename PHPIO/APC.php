<?php

class PHPIO_APC extends PHPIO_Hook_Func {
	const classname = 'APC';
	var $link = 'localhost';
	var $hooks = array(
		'apc_add',
		'apc_cas',
		'apc_clear_cache',
		'apc_dec',
		'apc_delete',
		'apc_exists',
		'apc_fetch',
		'apc_inc',
		'apc_store',
	);

    function postCallback($jp) {
        $this->trace['link'] = $this->link;
        $this->trace['cmd'] = (is_array($this->args[0]) ? implode(' ',$this->args[0]) : $this->args[0]);
        
        parent::postCallback($jp);
    }
}
