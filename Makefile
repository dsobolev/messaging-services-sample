DOCKER_COMP = docker compose

PRODUCT_PHP_CONT = $(DOCKER_COMP) exec product-app
PRODUCT_APP_PHP = $(PRODUCT_PHP_CONT) php
PRODUCT_CONSOLE  = $(PRODUCT_APP_PHP) bin/console

.PHONY: product-sf start

start:
	@$(DOCKER_COMP) up --detach

stop:
	@$(DOCKER_COMP) down --remove-orphans

ps:
	@$(DOCKER_COMP) ps

product-install:
	make product-sf c="doctrine:migrations:migrate"
	make product-sf c="doctrine:fixtures:load"

product-shell-db:
	@$(DOCKER_COMP) exec product-db sh

product-shell-app:
	@$(PRODUCT_PHP_CONT) sh

## List all Symfony commands or pass the parameter "c=" to run a given command, example: make sf c=about
## Example: make sf c="doctrine:migrations:migrate"
## NOTE! For FQCN use triple \\\ -> make product-sf c="d:m:execute DoctrineMigrations\\\Version20241221003643  --down"
product-sf:
	@$(eval c ?=)
	@$(PRODUCT_CONSOLE) $(c)

orders-install:
	cd orders-svc && composer install
