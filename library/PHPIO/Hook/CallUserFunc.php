<?php

class PHPIO_Hook_CallUserFunc extends PHPIO_Hook_Func {
	const classname = 'CallUserFunc';
	var $hooks = array(
        'call_user_func_array',
    );

    function _preCallback($jp){
		$jp = new PHPIO_AOP_JoinPoint($jp);
		$hook = $this->getHookClass($jp);
		if ( $this->isHook($hook, $jp->getMethodName()) ) {
			PHPIO::$hooks[$hook]->_preCallback($jp, $traces);
		}
    }

    function _postCallback($jp, $traces=array()){
		$jp = new PHPIO_AOP_JoinPoint($jp);
		$hook = $this->getHookClass($jp);
		$phpio_hook = "PHPIO_$hook";
		if ( $this->isHook($hook, $jp->getMethodName()) ) {
            $traces = debug_backtrace();
            $traces[1]['class'] = $hook;
            $traces[1]['function'] = $jp->getMethodName();
            $traces[1]['args'] = $jp->getArguments();

			PHPIO::$hooks[$hook]->_postCallback($jp, $traces);
		}
    }

    function getHookClass($jp) {
		$hook = $jp->getClassName();
		if ( !$hook ) {
			list($hook, ) = explode('_', $jp->func, 2);
			$hook = ucfirst($hook);
		}
		return $hook;
    }

    function isHook($hook, $method) {
		if ( class_exists("PHPIO_Hook_$hook", false) ) {
			foreach ( PHPIO::$hooks[$hook]->hooks as $hook_method ) {
				if ( $hook_method === '*' ) {
					return true;
				}
				if (strcasecmp($method, $hook_method) == 0) {
					return true;
				}
			}
		}
		return false;
    }
}
