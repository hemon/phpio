<?php
class PHPIO_AOP_JoinPoint {
	var $jp;
	var $args;
	var $object;
    var $class;
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

        switch ( gettype($args[0]) ) {
            case 'string':
			    $this->func = $args[0];
                break;
            case 'array':
                // array($object, $method)
                $this->class  = $this->getClassName($args[0][0]);
                $this->object = $args[0][0];
                $this->method = $args[0][1];
                break;
            case 'object':
                //Closure Object
                $this->class = 'Closure';
                $this->object = $args[0];
                $this->method = '__invoke';
                break;
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
		return (is_object($this->object) ? get_class($this->object) : false);
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
