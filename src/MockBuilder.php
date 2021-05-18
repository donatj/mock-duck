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
		$new                       = clone $this;
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

	/**
	 * Build the requested mock to the given spec
	 *
	 * @return string The fully qualified class name of the new mock object
	 */
	public function buildMockClass() : string {
		try {
			$ref = new \ReflectionClass($this->className);
		} catch( \ReflectionException $re ) {
			throw new MockBuilderRuntimeException("failed to reflect {$this->className}", $re->getCode(), $re);
		}

		$methods = $ref->getMethods(\ReflectionMethod::IS_PUBLIC);

		$mockName    = $this->makeUniqueClassName($ref);
		$methodsName = "methods__{$mockName}";

		$mockedMethods = "";
		foreach( $methods as $method ) {
			if( $this->disabledConstructor && $method->isConstructor() ) {
				$mockedMethods .= <<<'PHP'
public function __constructor() {}

PHP;

				continue;
			}

			if( in_array($method->getName(), $this->excludedMethods) ) {
				continue;
			}

			$mockedParams     = '';
			$methodParameters = $method->getParameters();
			foreach( $methodParameters as $methodParameter ) {
				$mockedParams .= $this->buildMethodParameter($methodParameter) . ', ';
			}

			$mockedParams = rtrim($mockedParams, ', ');

			$methodNameVar = var_export($method->getName(), true);

			$mockedMethods .= <<<PHP
function {$method->getName()} ( {$mockedParams} ) {
	return (new Invoker( self::\${$methodsName}[$methodNameVar] ?? null, ...func_get_args() ))();
}

PHP;
		}

		$relationship = '';
		if( $ref->isInterface() ) {
			$relationship = 'implements \\' . $ref->getName();
		} elseif( !$ref->isTrait() ) {
			$relationship = 'extends \\' . $ref->getName();
		}

		$fqcn = "donatj\\MockDuck\\Mocks\\{$mockName}";

		$classCode = <<<PHP

namespace donatj\\MockDuck\\Mocks;

use donatj\\MockDuck\\Invoker;

class {$mockName} {$relationship} {
public static \${$methodsName};
{$mockedMethods}
}
PHP;

		eval($classCode);

		$fqcn::${$methodsName} = $this->mockMethods;

		return $fqcn;
	}

	/**
	 * Build the requested mock to the given spec and instantiate it
	 *
	 * @param mixed ...$constructorArgs The arguments to pass to the constructor
	 * @return object The instance of the Mock
	 */
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
