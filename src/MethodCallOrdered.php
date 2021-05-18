<?php

namespace donatj\MockDuck;

use donatj\MockDuck\Exceptions\MockMethodNotFoundException;

/**
 * Allows setting method invocations on subsequent calls
 *
 * This is one of the only parts of MockDuck that is not functionally pure and
 * thus should be handled with special care
 */
class MethodCallOrdered implements InvokableMethodInterface {

	/** @var callable */
	private $invokables;

	public function __construct( callable ...$invokables ) {
		$this->invokables = $invokables;
	}

	private $invokeIndex = 0;

	public function __invoke( ...$args ) {
		if( !isset($this->invokables[$this->invokeIndex]) ) {
			throw new MockMethodNotFoundException(sprintf(
				"SubsequentMockMethod - Index %d outside max of %d",
				$this->invokeIndex,
				count($this->invokables)
			));
		}

		$value = ($this->invokables[$this->invokeIndex])(...$args);
		$this->invokeIndex++;

		return $value;
	}

}
