DOCKER= docker compose --env-file docker.env
DOCKER_APP = $(DOCKER) exec app
SYMFONY= $(DOCKER_APP) bin/console

.PHONY: check
check: ecs_fix phpstan unit

.PHONY: init
init: up composer migration

.PHONY:
up:
	$(DOCKER) up -d --build

.PHONY: down
down:
	$(DOCKER) down

.PHONY: composer
composer:
	$(DOCKER_APP) composer install

.PHONY: clear
clear:
	$(SYMFONY) cache:clear

.PHONY: cli
cli:
	$(DOCKER_APP) bash

.PHONY: consumer_bot_platform
consumer_bot_platform:
	$(SYMFONY)  messenger:consume async async_now async_delay failed -vv

.PHONY: ecs
ecs:
	$(EXEC) ./vendor/bin/ecs

.PHONY: ecs_fix
ecs_fix:
	$(EXEC) vendor/bin/ecs --fix

.PHONY: phpstan
phpstan:
	$(EXEC) ./vendor/bin/phpstan analyse --memory-limit=-1

.PHONY: unit
unit:
	$(EXEC) ./vendor/bin/phpunit

.PHONY: migration_generate
migration_generate:
	$(SYMFONY) do:mi:generate

.PHONY: migration
migration:
	$(SYMFONY) do:mi:mi
