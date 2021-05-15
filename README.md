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