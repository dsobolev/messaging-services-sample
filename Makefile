DOCKER_COMP = docker compose
PHP_CONT = $(DOCKER_COMP) exec php

start:
	@$(DOCKER_COMP) up

stop:
	@$(DOCKER_COMP) down

services:
	@$(DOCKER_COMP) ps

db-shell:
	@$(DOCKER_COMP) exec product-db sh

