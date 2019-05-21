Symfony API example
======

------

**Simple API built on Symfony that fetches some data from Github.**

-------------

## Installation

Create a local copy of this repository via git:
``` ~$ git clone git@github.com:ivkatic/symfony-api-test.git  ```
Change to the directory and run composer:
```
~$ cd api-test
api-test$ composer install
```
Create .env.local and set DATABASE_URL and GITHUB_TOKEN vars
```
DATABASE_URL="mysql://<db_user>:<db_pass>@<db_host>:<db_port>/<db_name>"
GITHUB_TOKEN=<token>
```
Run `php bin/console server:run` to start local server

## Usage
### V1
#### Fetch score for specific keyword
``` 
GET http://127.0.0.1:8000/api/v1/score/php
RESULT JSON {"term":"php","score":"3.36"}
```

## Minimum Requirements

- PHP 7.0+
- Composer to install

## License

MIT
