<?php

namespace donatj\MockDuck;

use donatj\MockDuck\Exceptions\MockMethodNotFoundException;

/**
 * @internal
 */
class Invoker {

	/** @var callable|null */
	private $method;
	/** @var array */
	private $args;

	public function __construct( ?callable $method, ...$args ) {
		$this->method = $method;
		$this->args   = $args;
	}

	public function __invoke() {
		if(!$this->method) {
			throw new MockMethodNotFoundException("no method defined at execution");
		}

		return ($this->method)(...$this->args);
	}

}
