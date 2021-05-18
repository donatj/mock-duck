# Mock Duck

[![Latest Stable Version](https://poser.pugx.org/donatj/mock-duck/version)](https://packagist.org/packages/donatj/mock-duck)
[![License](https://poser.pugx.org/donatj/mock-duck/license)](https://packagist.org/packages/donatj/mock-duck)


A Simple, Sane, Mock Builder for PHPUnit

Currently in a very early stage of development.

## Requirements

## Installing

Install the latest version with:

```bash
composer require 'donatj/mock-duck'
```

## Documentation

### Class: \donatj\MockDuck\Exceptions\MockBuilderRuntimeException

Exception thrown as a general runtime exception for the MockBuilder

### Class: \donatj\MockDuck\Exceptions\MockMethodNotFoundException

Exception thrown by a method mock on invoke when it does not match the spec
and wishes to indicate the parent should continue

### Class: \donatj\MockDuck\InvokableMethodInterface



### Class: \donatj\MockDuck\MethodFallthrough

Executes given invokables until one is successful



#### Undocumented Method: `MethodFallthrough->__construct(callable ...$invokables)`

---

#### Undocumented Method: `MethodFallthrough->__invoke($args)`

### Class: \donatj\MockDuck\MethodOrdered

Allows setting method invocations on subsequent calls

This is one of the only parts of MockDuck that is not functionally pure and
thus should be handled with special care



#### Undocumented Method: `MethodOrdered->__construct(callable ...$invokables)`

---

#### Undocumented Method: `MethodOrdered->__invoke($args)`

### Class: \donatj\MockDuck\MethodParameterMatcher

Matches a given set of method parameters to a method invoker



#### Undocumented Method: `MethodParameterMatcher->withEquality([ $equality = true])`

---

#### Method: MethodParameterMatcher->withMethodParameterMatch

```php
function withMethodParameterMatch(callable $invokable, $args) : self
```

##### Parameters:

- ***callable*** `$invokable` - The method invokable
- ***mixed*** `$args` - The arguments to match against. Accepts and matches against PHPUnit constraints

##### Returns:

- ***$this***

---

#### Undocumented Method: `MethodParameterMatcher->__invoke($args)`

### Class: \donatj\MockDuck\MethodReturns

#### Method: MethodReturns->__construct

```php
function __construct($value)
```

##### Parameters:

- ***mixed*** `$value` - The value to return

---

#### Undocumented Method: `MethodReturns->__invoke($args)`

### Class: \donatj\MockDuck\MockBuilder

#### Method: MockBuilder->__construct

```php
function __construct(string $className [, ?\PHPUnit\Framework\Assert $asserter = null])
```

MockBuilder constructor.

---

#### Undocumented Method: `MockBuilder->withMethodsExcluded(string ...$methods)`

---

#### Method: MockBuilder->withDisabledConstructor

```php
function withDisabledConstructor(bool $disable) : self
```

Enable or disable the original constructor

##### Returns:

- ***$this***

---

#### Method: MockBuilder->withMockMethod

```php
function withMockMethod(string $method, callable $invokable) : self
```

##### Returns:

- ***$this***

---

#### Method: MockBuilder->buildMockClass

```php
function buildMockClass() : string
```

Build the requested mock to the given spec

##### Returns:

- ***string*** - The fully qualified class name of the new mock object

---

#### Method: MockBuilder->buildMock

```php
function buildMock($constructorArgs) : object
```

Build the requested mock to the given spec and instantiate it

##### Parameters:

- ***mixed*** `$constructorArgs` - The arguments to pass to the constructor

##### Returns:

- ***object*** - The instance of the Mock