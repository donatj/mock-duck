<?php

namespace donatj\MockDuck;

use donatj\MockDuck\Exceptions\MockMethodNotFoundException;

/**
 * Executes given invokables until one is successful
 */
class MethodCallFallthrough implements InvokableMethodInterface {

	/** @var callable */
	private $invokables;

	public function __construct( callable ...$invokables ) {
		$this->invokables = $invokables;
	}

	public function __invoke( ...$args ) {
		foreach( $this->invokables as $invokable ) {
			try {
				return $invokable(...$args);
			} catch( MockMethodNotFoundException $ex ) {
				continue;
			}
		}

		throw new MockMethodNotFoundException(sprintf(
			"FallthroughMethod - None of the %s invokables succeed",
			count($this->invokables)
		));
	}

}
