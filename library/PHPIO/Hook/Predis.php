<?php

class PHPIO_Hook_Predis extends PHPIO_Hook {
	const classname = 'Predis\Client';
	var $hooks = array(
		'initializeConnection',
        '__call',
    );

    function initializeConnection_post($jp) {
		$object_id = $this->getObjectId($this->object);
        $this->links[$object_id] = $this->getLink($this->args);
        $this->postCallback($jp);
	}

	function getLink($args) {
		$host = $args[0]['host'] .":". $args[0]['port'];
        return $host;
    }

    function postCallback($jp) {
        if ( $jp->getMethodName() !== 'initializeConnection' ) {
            $object_id = $this->getObjectId($this->object);
            if ( isset($this->links[$object_id]) ) {
                $this->trace['link'] = $this->links[$object_id];
            }

            if ( isset($this->args[0]) ) {
                $this->trace['cmd'] = $this->args[0] .' '. implode(" ", $this->args[1]);
            }
        }
        
        parent::postCallback($jp);
    }
}
