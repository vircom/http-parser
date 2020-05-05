# vircom/http-parser
-----

[![Build Status](https://travis-ci.org/vircom/http-parser.svg?branch=master)](https://travis-ci.org/vircom/http-parser)
[![Coverage Status](https://coveralls.io/repos/github/vircom/http-parser/badge.svg)](https://coveralls.io/github/vircom/http-parser)
[![Latest Stable Version](https://poser.pugx.org/vircom/http-parser/v/stable.png)](https://packagist.org/packages/vircom/http-parser)
[![Total Downloads](https://poser.pugx.org/vircom/http-parser/downloads.png)](https://packagist.org/vircom/http-parser)
[![License](https://poser.pugx.org/vircom/http-parser/license.png)](https://packagist.org/packages/vircom/http-parser)

This package provides an implementation to parse raw HTTP request and responses.


# Prerequisites

- PHP 7.4+

# Installation

## Install by composer

To install vircom/http-parser with Composer, run the following command:

```sh
$ composer require vircom/http-parser
```

You can see this library on [Packagist](https://packagist.org/packages/vircom/http-parser).

Composer installs autoloader at `./vendor/autoloader.php`. If you use vircom/http-parser in your php script, add:

```php
require_once 'vendor/autoload.php';
```

# Usage:

## Parsing request

```php
use VirCom\HttpParser\HttpParserFactory;

$request = "POST /cgi-bin/process.cgi HTTP/0.9\r\n"
 . "User-Agent: Mozilla/4.0 (compatible; MSIE5.01; Windows NT)\r\n"
 . "\r\n"
 . "exampleName1=exampleValue1&exampleName2=exampleValue2";

$parser = (new HttpParserFactory())->createRequestParser();
$result = $parser->parse($request);
```

## Parsing response

```php
<?php
require_once('vendor/autoload.php');

use VirCom\HttpParser\HttpParserFactory;

$response = "HTTP/1.1 200 OK\r\n"
 . "Content-Type: application/json\r\n"
 . "\r\n"
 . "\r\n["
 . "  {\r\n"
 . "    \"id\": 10,\r\n"
 . "    \"name\": \"testName\",\r\n"
 . "    \"color\": \"testColor\"\r\n"
 . "    \"price\": \"testPrice\"\r\n"
 . "  }\r\n"
 . "]";

$parser = (new HttpParserFactory())->createResponseParser();
$result = $parser->parse($response);
```

# Documentation

## Request

- `getStartLine()->getHttpMethod()` - returns HTTP request method
- `getStartLine()->getTargetRequest()` - returns HTTP request target path
- `getStartLine()->getHttpVersion()` - returns HTTP request protocol version
- `getHeaders()` - returns HTTP request headers collection
- `getHeaders()[n]->getName()` - returns HTTP request header name
- `getHeaders()[n]->getValues()` - returns HTTP request header values
- `getBody()` - returns HTTP request body content

## Response
- `getStartLine()->getHttpVersion()` - returns HTTP response protocol version
- `getStartLine()->getStatusCode()` - returns HTTP response status code
- `getStartLine()->getStatusText()` - returns HTTP response status text
- `getHeaders()` - returns HTTP request headers collection
- `getHeaders()[n]->getName()` - returns HTTP request header name
- `getHeaders()[n]->getValues()` - returns HTTP request header values
- `getBody()` - returns HTTP request body content

# About


## Submitting bugs and feature requests

Bugs and feature request are tracked on [GitHub](https://github.com/vircom/http-parser/issues)

## License

Monolog is licensed under the MIT License - see the `LICENSE` file for details