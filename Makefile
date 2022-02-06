.PHONY: help up down clean prune php-artisan migrate migrate-rollback

build:
	docker-compose -f docker-compose.yml build

up:
	docker-compose -f docker-compose.yml up -d

down:
	docker-compose -f docker-compose.yml down

clean:
	docker-compose down --remove-orphans --rmi all

prune:
	docker system prune -af

php-artisan:
	docker-compose -f docker-compose.yml exec php-fpm /bin/sh

migrate:
	$(docker-compose -f docker-compose.yml exec php-fpm /bin/sh) php artisan migrate

migrate-rollback:
	$(docker-compose -f docker-compose.yml exec php-fpm /bin/sh) php artisan migrate:rollback
