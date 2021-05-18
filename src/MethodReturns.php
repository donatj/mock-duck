<?php

namespace donatj\MockDuck;

class MethodReturns implements InvokableMethodInterface {

	private $value;

	/**
	 * @param mixed $value The value to return
	 */
	public function __construct( $value ) {
		$this->value = $value;
	}

	public function __invoke( ...$args ) {
		return $this->value;
	}

}
