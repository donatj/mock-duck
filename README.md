# Mock Duck

[![Latest Stable Version](https://poser.pugx.org/donatj/mockduck/version)](https://packagist.org/packages/donatj/mockduck)
[![License](https://poser.pugx.org/donatj/mockduck/license)](https://packagist.org/packages/donatj/mockduck)


A Simple, Sane, Mock Builder for PHPUnit

Currently in a very early stage of development.

## Requirements

## Installing

Install the latest version with:

```bash
composer require 'donatj/mockduck'
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

#### Undocumented Method: `MockBuilder->buildMockClass()`

---

#### Undocumented Method: `MockBuilder->buildMock($constructorArgs)`