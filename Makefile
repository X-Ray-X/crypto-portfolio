.PHONY: build up down clean prune php-artisan migrate migrate-rollback test test-coverage cs-fix php-cpd build-docs phpstan

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

composer-install:
	docker-compose -f docker-compose.yml exec php-fpm composer install

migrate:
	docker-compose -f docker-compose.yml exec php-fpm php artisan migrate

migrate-rollback:
	docker-compose -f docker-compose.yml exec php-fpm php artisan migrate:rollback

test:
	docker-compose -f docker-compose.yml exec php-fpm php vendor/bin/phpunit

test-coverage: # Inline fix for "No code coverage driver available" issue
	docker-compose -f docker-compose.yml exec php-fpm php -n -dzend_extension=xdebug -dxdebug.mode=coverage vendor/bin/phpunit --coverage-html ./coverage/html

cs-fix:
	docker-compose -f docker-compose.yml exec php-fpm php vendor/bin/php-cs-fixer fix .

php-cpd:
	docker-compose -f docker-compose.yml exec php-fpm php vendor/sebastian/phpcpd/phpcpd --fuzzy app

build-docs:
	docker-compose -f docker-compose.yml up openapi-builder

phpstan:
	docker-compose -f docker-compose.yml exec php-fpm php vendor/bin/phpstan analyse app bootstrap public resources routes tests --level 5