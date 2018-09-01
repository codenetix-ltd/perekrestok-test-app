build:
	docker-compose build

up:
	docker-compose up -d

api_code_check:
	docker exec -it perekrestok-events-api ./vendor/bin/phpcs --standard=./phpcs.xml

api_code_fix:
    docker exec -it perekrestok-events-api ./vendor/bin/phpcbf --standard=./phpcs.xml

api_test:
	docker exec -it perekrestok-events-api ./vendor/bin/phpunit tests/Feature

api_test_coverage:
	docker exec -it perekrestok-events-api ./vendor/bin/phpunit tests/Feature --coverage-html tests/_reports/coverage