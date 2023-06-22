# Laravel API Boilerplate

## Environments

The following environments could be used during development.

### Docker
Install [Docker](https://docs.docker.com/install/) and [Docker Compose](https://docs.docker.com/compose/install/).

The `docker-compose.yml` relies on a few `.env` variables. So it might be best to first copy the `.env.example` to `.env`.

To run the Docker environment:
```
docker-compose up
```
Or detached if you want to keep working in the same terminal:
```
docker-compose up -d
```

To get into the container:
```
docker exec -it php /bin/bash
```

Commands can be prefixed with `docker exec php` to run then from outside the container.

**TODO**: Test `docker/docker-ssh`.

### Homestead

**TODO**: add Homestead instructions.

## Installation

The following can be executed to install the Laravel application:
```
composer install

yarn

cp .env.example .env

php artisan key:generate

php artisan jwt:secret
```
`composer install` and `yarn` could be run on your local machine for a possible faster installation, make sure their dependencies are available.

### Database connections

In order for database connections in Docker to work, `DB_HOST` has to be set to `mysql` which is the name of the MYSQL Docker container. Set `DB_HOST_TESTING` to `mysql_testing` if you wish to run tests with a database connection.

## Running tests

Tests can be executed by using the following command:
```
php artisan test
```

Homestead has installed phpunit globally and allows you to run the tests by simply executing `phpunit`.

## Running code sniffer
This project has to be developed according to the PSR-1 and PSR-2 coding standards as well as some customized rules. These rules can be found in `.phpcs.xml`.

The code can be checked by running the following command:
```
composer lint
```


## Previewing Emails
1. Enable `mailhog` service in `docker-compose.yml`
2. Set env variables:
```
MAIL_HOST=mailhog
MAIL_PORT=1025
```
3. Open Mailhog dashboard in http://localhost:8025/
# wordsmith
# wordsmith
# wordsmith
# wordsmith
# wordsmith
