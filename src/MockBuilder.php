<?php

namespace donatj\MockDuck;

use donatj\MockDuck\Exceptions\MockBuilderRuntimeException;
use PHPUnit\Framework\Assert;

class MockBuilder {

	/**
	 * The object to perform our assertions against. Likely a PHPUnit TestCase
	 *
	 * @var \PHPUnit\Framework\Assert|null
	 */
	private $asserter;

	/** @var string */
	private $className;

	/**
	 * MockBuilder constructor.
	 */
	public function __construct( string $className, ?Assert $asserter = null ) {
		$this->className = $className;
		$this->asserter  = $asserter;
	}

	/** @var string[] */
	private $excludedMethods = [];

	public function withMethodsExcluded( string ...$methods ) : self {
		$new = clone $this;
		foreach( $methods as $method ) {
			$new->excludedMethods[] = $method;
		}

		return $new;
	}

	/** @var bool */
	private $disabledConstructor = true;

	/**
	 * Enable or disable the original constructor
	 *
	 * @return $this
	 */
	public function withDisabledConstructor( bool $disable ) : self {
		$new = clone $this;

		$new->disabledConstructor = $disable;

		return $new;
	}

	/** @var array<int, callable> */
	private $mockMethods = [];

	/**
	 * @return $this
	 */
	public function withMockMethod( string $method, callable $invokable ) : self {
		$new = clone $this;
		$new->mockMethods[$method] = $invokable;

		return $new;
	}

	private function buildMethodParameter( \ReflectionParameter $parameter ) : string {
		$return = '';

		$type = $parameter->getType();
		if( $type ) {
			$return .= $type->allowsNull() ? '?' : '';
			$return .= $type->getName();
		}

		if( $parameter->isVariadic() ) {
			$return .= ' ... ';
		}

		$return .= ' $' . $parameter->getName();

		if( $parameter->isOptional() ) {
			$return .= ' = ' . var_export($parameter->getDefaultValue(), true);
		}

		return $return;
	}

	public function buildMockClass() : string {
		$ref     = new \ReflectionClass($this->className);
		$methods = $ref->getMethods(\ReflectionMethod::IS_PUBLIC);

		$mockedMethods = "";
		foreach( $methods as $method ) {
			if( $this->disabledConstructor && $method->isConstructor() ) {
				$mockedMethods .= <<<'PHP'
public function __constructor() {}

PHP;

				continue;
			}

			$mockedParams     = '';
			$methodParameters = $method->getParameters();
			foreach( $methodParameters as $methodParameter ) {
				$mockedParams .= $this->buildMethodParameter($methodParameter) . ', ';
			}

			$mockedParams = rtrim($mockedParams, ', ');

			$mockedMethods .= <<<PHP
function {$method->getName()} ( {$mockedParams} ) { }

PHP;
		}

		$relationship = '';
		if($ref->isInterface()) {
			$relationship = 'implements \\' . $ref->getName();
		}elseif( !$ref->isTrait() ) {
			$relationship = 'extends \\' . $ref->getName();
		}

		$mockName = $this->makeUniqueClassName($ref);
		$fqcn     = "donatj\\MockDuck\\Mocks\\" . $mockName;

		$classCode = <<<PHP

namespace donatj\\MockDuck\\Mocks;

class {$mockName} {$relationship} {
{$mockedMethods}
}
PHP;

		eval($classCode);

		return $fqcn;
	}

	public function buildMock( ...$constructorArgs ) : object {
		$className = $this->buildMockClass();

		return new $className(...$constructorArgs);
	}

	private function makeUniqueClassName( \ReflectionClass $ref ) : string {
		$mockName  = null;
		$cleanName = str_replace('\\', '_', $ref->getName());
		for( $i = 0; $i <= 1000; $i++ ) {
			$testMockName = sprintf("Mock_%s_%d_%d", $cleanName, $i, microtime(true) * 10000);
			if( !class_exists("donatj\\MockDuck\\Mocks\\" . $testMockName) ) {
				$mockName = $testMockName;
				break;
			}
		}

		if( !$mockName ) {
			throw new MockBuilderRuntimeException("failed to find unique name for mock of {$ref->getName()}");
		}

		return $mockName;
	}

}
