start:
	docker compose up

stop:
	docker compose down

services:
	docker compose ps

db-shell:
	docker compose exec product-db sh

