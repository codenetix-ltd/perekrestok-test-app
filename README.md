# Общее описание

Данный моно репозиторий включает в себя 5 обособленных компонентов системы:
* *api* - API сервер реализованный на основе фреймворка Laravel 5.7
* *api_documentation* - сервер API документации на основе Swagger спецификации
* *client* - клиентской frontend приложение на базе фреймворка VueJS
* *proxy* - реверсивный прокси сервер на базе nginx для маршрутизации внешнего http траффика на *api*,*documentation* и *client* компоненты

## Требования
* git
* GNU make
* docker ^18.03-ce stable - [how to install](https://docs.docker.com/install/)
* docker-compose ^1.22  stable - [how to install](https://docs.docker.com/compose/install/)

## Установка

> убедитесь что порты 80/8080 не заняты!

```bash
$ git clone git@github.com:codenetix-ltd/perekrestok-test-app.git .
$ make build # сборка образов
$ make up # запуск
$ docker ps 
```
В случе успеха команда выдаст похожий результат:
![провежуточный результат](readmefiles/screenshot1.png)

Сервер приложения будет доступен по URL [http://localhost](), а документация по [http://localhost:8080]()

### Запуск дополнительных команд и утилит

* `$ make up_local` запуск локальной версии проекта с монтированием директорий проекта на хост машину
> после такого запуска будет необходимо вручную запустить `composer install` и `npm run build` в *api* и *client* соответствуеено
* `$ make api_code_check` - запуск codestyle linter. Описание стандарта кода находятся в файле `phpcs.xml`
Пример:
```
docker exec -it perekrestok-events-api ./vendor/bin/phpcs --standard=./phpcs.xml
............................................................ 60 / 74 (81%)
..............                                               74 / 74 (100%)
```

* `$ make api_code_fix` - фиксер

* `$ make phpunit` - тесты
```
docker exec -it perekrestok-events-api ./vendor/bin/phpunit tests/Feature
PHPUnit 7.3.2 by Sebastian Bergmann and contributors.

..........                                                        10 / 10 (100%)

Time: 4.25 seconds, Memory: 20.00MB

OK (10 tests, 37 assertions)

```
* `$ make api_test_coverage` - покрытие кода
```
docker exec -it perekrestok-events-api ./vendor/bin/phpunit tests/Feature --coverage-html tests/_reports/coverage
PHPUnit 7.3.2 by Sebastian Bergmann and contributors.

..........                                                        10 / 10 (100%)

Time: 10.12 seconds, Memory: 24.00MB

OK (10 tests, 37 assertions)
```