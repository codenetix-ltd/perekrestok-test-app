build:
	docker-compose build

up:
	docker-compose up -d

up_local:
	docker-compose -f docker-compose.yml -f share-volumes.yml up -d

api_test:
	docker exec -it perekrestok-events-api ./vendor/bin/phpunit tests/Feature

api_test_coverage:
	docker exec -it perekrestok-events-api ./vendor/bin/phpunit tests/Feature --coverage-html tests/_reports/coverage

api_code_check:
    docker exec -it perekrestok-events-api ./vendor/bin/phpcs --standard=./phpcs.xml

api_code_fix:
    docker exec -it perekrestok-events-api ./vendor/bin/phpcbf --standard=./phpcs.xml
