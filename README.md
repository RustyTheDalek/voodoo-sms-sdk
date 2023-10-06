# Voodoo SMS PHP SDK

PHP SDK for communicating with the Voodoo SMS API.

## Getting Started

These instructions will get you up and running on your local machine and a development environment.

### Prerequisites

* PHP: >=8.2

### Installing

Simply pull in the package in with composer:

```
$ composer require goldspecdigital/voodoo-sms-sdk
```

### Examples

#### Send an SMS

```php
<?php

use GoldSpecDigital\VoodooSmsSdk\Client;

$client = new Client('username', 'password', 'CompanyName');

$response = $client->send('This is a test message', '07712345678');

var_dump($response);

/*
{
    "result": 200,
    "resultText": "200 OK",
    "reference_id": ["A3dads..."]
}
*/
```

#### Get the Delivery Status for an SMS

```php
<?php

use GoldSpecDigital\VoodooSmsSdk\Client;

$client = new Client('username', 'password', 'CompanyName');

$response = $client->getDeliveryStatus('A3dads...');

var_dump($response);

/*
{
    "result": "200 OK",
    "reference_id": "A3dads...",
    "message": "This is a test message",
    "delivery_status": "Delivered",
    "delivery_datetime": "2017-12-28 00:06:09"
}
*/
```

## Running the tests

To run the test you will need to have Voodoo SMS credentials stored in a `.env` file placed in the project root. An example file is provided for you with the keys required: `.env.example`. 

You can run the tests in an environment running PHP >=7.2 with PHP Unit:

```
$ vendor/bin/phpunit
```

### And coding style tests

This project follows PSR1 and PSR2 coding standards as well as enabling strict types on all PHP files.

Before making any commits, make sure your code passes the linter by running:

```
$ vendor/bin/phpcs
```

## Built With

* [Composer](https://getcomposer.org/) - Dependency management
* [Guzzle](http://docs.guzzlephp.org/) - The HTTP client to Communicate with the Voodoo SMS API

## Contributing

Feel free to issue a pull request, although any requests that fail PHPUnit or the linter will be automatically rejected.

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details
