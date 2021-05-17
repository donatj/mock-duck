<?php

namespace donatj\MockDuck;

use donatj\MockDuck\Exceptions\MockMethodNotFoundException;
use PHPUnit\Framework\Constraint\Constraint;

/**
 * Allows invoking a given method invokable when the arguments match expected
 */
class MockParameterMatchingMethod {

	private $equality = true;

	public function withEquality( $equality = true ) : self {
		$new           = clone $this;
		$new->equality = false;

		return $new;
	}

	private $parameterMatches = [];

	/**
	 * @param callable $invokable The method invokable
	 * @param mixed    ...$args   The arguments to match against. Accepts and matches against PHPUnit constraints
	 * @return $this
	 */
	public function withMethodParameterMatch( callable $invokable, ...$args ) : self {
		$new                     = clone $this;
		$new->parameterMatches[] = [ $invokable, $args ];

		return $new;
	}

	public function __invoke( ...$args ) {
		foreach( $this->parameterMatches as $parameterMatch ) {
			[ $invokable, $argMatches ] = $parameterMatch;

			foreach( $argMatches as $i => $argMatch ) {
				if( !array_key_exists($i, $args) ) {
					continue 2;
				}

				$value = $args[$i];

				if( $argMatch instanceof Constraint
					&& $argMatch->evaluate($value, '', true) ) {
					continue;
				}

				if( $argMatch === $value ) {
					continue;
				}

				if( $this->equality && $argMatch == $value ) {
					continue;
				}

				continue 2;
			}

			return $invokable(...$args);
		}

		throw new MockMethodNotFoundException('no matching mock method was found');
	}

}
