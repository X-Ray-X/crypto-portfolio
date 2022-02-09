.PHONY: build up down clean prune php-artisan migrate migrate-rollback phpunit cs-fix php-cpd

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

composer-install:
	$(docker-compose -f docker-compose.yml exec php-fpm /bin/sh) composer install

php-artisan:
	docker-compose -f docker-compose.yml exec php-fpm /bin/sh

migrate:
	$(docker-compose -f docker-compose.yml exec php-fpm /bin/sh) php artisan migrate

migrate-rollback:
	$(docker-compose -f docker-compose.yml exec php-fpm /bin/sh) php artisan migrate:rollback

phpunit:
	$(docker-compose -f docker-compose.yml exec php-fpm /bin/sh) php vendor/bin/phpunit

cs-fix:
	$(docker-compose -f docker-compose.yml exec php-fpm /bin/sh) php vendor/bin/php-cs-fixer fix .

php-cpd:
	$(docker-compose -f docker-compose.yml exec php-fpm /bin/sh) php vendor/sebastian/phpcpd/phpcpd --fuzzy app

build-docs:
	docker-compose -f docker-compose.yml up openapi-builder