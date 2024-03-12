.PHONY: it
it: fix stan test ## Run the commonly used targets

.PHONY: help
help: ## Displays this list of targets with descriptions
	@grep -E '^[a-zA-Z0-9_-]+:.*?## .*$$' $(firstword $(MAKEFILE_LIST)) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}'

.PHONY: setup
setup: vendor ## Set up the project

.PHONY: fix
fix: rector php-cs-fixer

.PHONY: rector
rector: vendor
	vendor/bin/rector process

.PHONY: php-cs-fixer
php-cs-fixer:
	vendor/bin/php-cs-fixer fix

.PHONY: stan
stan: ## Run static analysis
	vendor/bin/phpstan

.PHONY: test
test: ## Run PHPUnit tests
	vendor/bin/phpunit

vendor: composer.json ## Install dependencies through composer
	composer update
	composer normalize
