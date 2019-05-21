Symfony API example
======

------

**Simple API built on Symfony that fetches some data from Github.**

-------------

## Installation

Create a local copy of this repository via git:
``` ~$ git clone git@github.com:ivkatic/WpNonce.git  ```
Change to the directory and run composer:
```
~$ cd WpNonces
WpNonces$ composer install
```

## Usage
### Create a nonce with specific action and User ID
``` 
$nonce = new WpNonce;
$newNonce = $nonce->createNonce('my-nonce', 999);
```

### Verify Nonce
``` 
$nonce = new WpNonce;
$result = $nonce->verifyNonce('42fb9b0c15', 'my-nonce', 999);
```
False if the nonce is invalid, 1 if the nonce is valid and generated between 0-12 hours ago, 2 if the nonce is valid and generated between 12-24 hours ago.

### Display Nonce Field
Displays hidden form fields with nonce value and referer value if set to true. User ID is required.
``` 
$nonce = new WpNonce;
$nonceField = $nonce->displayField( 'my-nonce', '_wpnonce', $referer = true , $echo = false, $uid = 999 );
```

### Retrieve URL with nonce added
Returns escaped URL with nonce action added.
``` 
$nonce = new WpNonce;
$nonceUrl = $nonce->retrieveUrl( 'http://my-url.com/wp-admin/', 'my-nonce', '_wpnonce', $uid = 999 );
```

### Check if admin referer
Makes sure that a user was referred from another admin page.
``` 
$nonce = new WpNonce;
$adminReferer = $nonce->checkAdminReferer( $action = 'my-nonce', $query_arg = '_wpnonce', $uid = 999 );
```

## Minimum Requirements

- PHP 5.6+
- Composer to install


## Tests
To run the tests:

1. Switch into the WpNonces directory
2. run `composer install` if you didn't already,
3. run `phpunit`

## License

MIT
