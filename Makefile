SRC_FILES = $(shell find example src -type f -name '*.php')

.PHONY: fix
fix: cbf
	vendor/bin/php-cs-fixer fix

.PHONY: test
test: cs
	vendor/bin/phpunit

.PHONY: cs
cs:
	vendor/bin/phpcs

.PHONY: cbf
cbf:
	vendor/bin/phpcbf