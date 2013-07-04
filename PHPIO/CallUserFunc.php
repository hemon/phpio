<?php

class PHPIO_CallUserFunc extends PHPIO_Hook_Func {
	const classname = 'CallUserFunc';
	var $hooks = array(
        'call_user_func_array',
    );

    function _preCallback($jp, $traces=array()){
		$jp = new PHPIO_JoinPoint($jp);
		$hook = $this->getHookClass($jp);
		$phpio_hook = "PHPIO_$hook";
		if ( class_exists($phpio_hook) ) {
			$traces = debug_backtrace();
			$traces[1]['class'] = $hook;
			$traces[1]['function'] = $jp->getMethodName();
			$traces[1]['args'] = $jp->getArguments();
			PHPIO::$hooks[$hook]->_preCallback($jp, $traces);
		}
    }

    function _postCallback($jp){
		$jp = new PHPIO_JoinPoint($jp);
		$hook = $this->getHookClass($jp);
		$phpio_hook = "PHPIO_$hook";
		if ( class_exists($phpio_hook) ) {
			PHPIO::$hooks[$hook]->_postCallback($jp);
		}
    }

    function getHookClass($jp) {
		if ( !empty($jp->func) ) {
			list($hook, ) = explode('_', $jp->func, 2);
			$hook = ucfirst($hook);
		} else {
			$hook = $jp->getClassName();
		}
		return $hook;
    }
}

class PHPIO_JoinPoint {
	var $jp;
	var $args;
	var $object;
	var $method;
	var $func;
	var $kindOfAdvice;
	var $kindOfAdviceExchange = array(
		AOP_KIND_BEFORE_FUNCTION => AOP_KIND_BEFORE_METHOD,
		AOP_KIND_AROUND_FUNCTION => AOP_KIND_AROUND_METHOD,
		AOP_KIND_AFTER_FUNCTION => AOP_KIND_AFTER_METHOD,
	);

	function __construct(AopJoinPoint $jp) {
		$this->jp = $jp;
		$args = $this->jp->getArguments();
		$this->args = $args[1];
		$this->kindOfAdvice = $this->jp->getKindOfAdvice();

		if ( is_array($args) ) {
			$this->object = $args[0][0];
			$this->method = $args[0][1];
		} else {
			$this->func = $args[0];
		}
	}

	function getArguments() {
		return $this->args;
	}

	function getObject() {
		return $this->object;
	}

	function getMethodName(){
		return $this->method;
	}

	function getFunctionName(){
		return $this->func;
	}

	function getClassName(){
		return get_class($this->object);
	}

	function getReturnedValue(){
		if ( $this->kindOfAdvice != AOP_KIND_BEFORE_FUNCTION ) {
			return $this->jp->getReturnedValue();
		} else {
			trigger_error('Fatal error: getReturnedValue is not available when the advice was added with aop_add_before');
		}
	}

	function getKindOfAdvice(){
		if ( $this->func ) {
			return $this->kindOfAdvice;
		} else {
			return $this->kindOfAdviceExchange[$this->kindOfAdvice];
		}
	}
}