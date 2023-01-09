# PHP Class - Przelewy24 REST API
Simple PHP class to implement Przelewy24 payments using new REST API.

## Prerequisities
- PHP >= 7.4
- cURL extension
- no other dependencies...

## Installation

```shell
composer require piatkowski/przelewy24-php
````

## Example of usage

Create new instance of the P24 Client with authorization data

```php
$client = new \Przelewy24\Przelewy24( [
	'merchantId' => '...',
	'posId'      => '...',
	'crc'        => '...',
	'reportKey'  => '...', // API KEY
	'sandbox'    => true,
] );
```

Register new transaction. This is minimum required options. You can use pass more parameters (from P24 Docs)

```php
$transaction = $client->transaction( [
	'sessionId'   => '...',
	'amount'      => '...',
	'currency'    => 'PLN',
	'description' => '...',
	'email'       => '...',
	'urlReturn'   => '...',
	'urlStatus'   => '...'
] );
```

Get payment Url

```php
$transaction->getRedirectUrl();
```

## Todo
- get response from P24 after successful payment
- verify transaction
