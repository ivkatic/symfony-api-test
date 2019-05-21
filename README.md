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
Run `php bin/console server:run` to start local server. No need to create tables for caching in databse, as App handles that itself .

## Usage
### v1
#### Fetch score for specific keyword
`/api/v1/{endpoint}/{keyword}`
Example
``` 
GET http://127.0.0.1:8000/api/v1/score/php
RESULT JSON {"term":"php","score":"3.36"}
```

### v2
#### Fetch score for specific keyword
`/api/v2/{endpoint}/{keyword}`

Returns the data in JSON:API specification and for that needs Headers - Content-Type and Accept to be set properly.

Example
``` 
GET http://127.0.0.1:8000/api/v2/score/php Headers -> Content-Type: application/vnd.api+json, Accept: application/vnd.api+json
RESULT JSON:API 
{
    "data": {
        "type": "score",
        "id": "php",
        "attributes": {
            "score": "3.36"
        }
    },
    "jsonapi": {
        "version": "1.0"
    }
}
```

## Development
You can easily add new APIs to extend these methods. Classes Api and ApiV2 both hold array of services in variable. You can extend it by just adding array with 'uri' and 'auth' keys.
```
'github' => [
    'uri' => 'https://api.github.com',
    'auth' => 'Authorization: '.$_ENV['GITHUB_TOKEN'],
],
'twitter' => [
    'uri' => 'https://api.twitter.com',
    'auth' => 'Authorization: '.$_ENV['TWITTER_TOKEN'],
]
```
After that define new routes in ApiController/ApiV2Controller and/or set endpoint returns in Api/ApiV2 'client' method. 

## Minimum Requirements

- PHP 7.2+
- Composer to install

## License

MIT
