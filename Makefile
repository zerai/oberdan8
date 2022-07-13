.PHONY: dependency-install dependency-purge coding-standards static-code-analysis static-code-analysis-baseline core-coverage core-architecture-check

help:
	@awk 'BEGIN {FS = ":.*##"; printf "Use: make \033[36m<target>\033[0m\n"} /^[a-zA-Z_-]+:.*?##/ { printf "  \033[36m%-20s\033[0m %s\n", $$1, $$2 } /^##@/ { printf "\n\033[1m%s\033[0m\n", substr($$0, 5) } ' $(MAKEFILE_LIST)

.PHONY: init
init:  ## Initialize dev environment
	- docker-compose run encore yarn install
	- make up

.PHONY: up
up:  ## Start docker-compose
	- make down
	- docker-compose up -d

.PHONY: down
down:  ## Stop docker-compose
	- docker-compose down -v --remove-orphans

.PHONY: status
status:  ## Show containers status
	- docker-compose ps

.PHONY: bash
bash:  ## Open a bash terminal inside php container
	- docker-compose exec app bash

.PHONY: db-prepare
db-prepare:  ## Execute dev and test database migrations and load dev fixtures
	- docker-compose exec app bin/console doctrine:migrations:migrate -n
	- docker-compose exec app bin/console doctrine:migrations:migrate -n -e test
	- docker-compose exec app bin/console doctrine:fixtures:load -n --group dev

.PHONY: dependency-install
dependency-install:  ## Install all dependency with composer
	- docker-compose exec app composer install
	- docker-compose exec app bin/phpunit install
	- docker-compose exec app composer bin all install


.PHONY: dependency-purge
dependency-purge:  ## Remove all dependency
	rm -fR vendor
	rm -fR tools/*/vendor
	rm -fR bin/.phpunit


.PHONY: coding-standards
coding-standards: ## Fixes code style issues with easy-coding-standard
	- docker-compose exec app vendor/bin/ecs check --fix --verbose

.PHONY: static-code-analysis
static-code-analysis: ## Runs a static code analysis with phpstan/phpstan and vimeo/psalm
	- docker-compose exec app vendor/bin/phpstan --configuration=phpstan.neon --memory-limit=-1
	- docker-compose exec app vendor/bin/psalm --config=psalm.xml --diff --show-info=false --stats --threads=4


.PHONY: static-code-analysis-baseline
static-code-analysis-baseline: vendor ## Generates a baseline for static code analysis with phpstan/phpstan and vimeo/psalm
	- docker-compose exec app vendor/bin/phpstan --configuration=phpstan.neon --generate-baseline --memory-limit=-1  || true
	- docker-compose exec app vendor/bin/psalm --config=psalm.xml --set-baseline=psalm-baseline.xml

.PHONY: core-tests
core-tests: ## Runs unit and integration tests For Core code with phpunit/phpunit
	- docker-compose exec app bin/phpunit --configuration core/booking/tests/Unit/phpunit.xml --coverage-text
	- docker-compose exec app bin/phpunit --configuration core/booking/tests/Integration/phpunit.xml


.PHONY: core-coverage
core-coverage: ## Collects Core code coverage from running unit tests with phpunit/phpunit
	bin/phpunit --configuration core/booking/tests/Unit/phpunit.xml --coverage-html var/coverage/core

.PHONY: core-architecture-check
core-architecture-check:  ## Check Core code architecture roules with deptrac
	vendor/bin/deptrac analyse core/depfile-booking.yaml
	vendor/bin/deptrac analyse core/depfile-booking-iso.yaml

.PHONY: lint-yaml
lint-yaml: ## Run symfony linter for yaml files.
	- docker-compose exec app bin/console lint:yaml config/
	- docker-compose exec app bin/console lint:yaml src/
	- docker-compose exec app bin/console lint:yaml core/booking/src/


.PHONY: pre-commit
pre-commit:  ## Check Core code architecture rules with deptrac
	make lint-yaml
	make coding-standards
	make static-code-analysis
	make core-architecture-check
	make core-tests

.PHONY: alias-docker-print
alias-docker-print:  ## Show alias for common commands executed in php docker container
	echo alias dcs='docker-compose exec app make coding-standards' \
		alias dsa="docker-compose exec app make static-code-analysis" \
		alias dpc="docker-compose exec app make pre-commit"
	echo ''
	echo unalias dcs \
		unalias dsa \
		unalias dpc
