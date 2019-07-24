Neat HTTP components
=======================
[![Stable Version](https://poser.pugx.org/neat/http-guzzle-bridge/version)](https://packagist.org/packages/neat/http-guzzle-bridge)
[![Build Status](https://travis-ci.org/neat-php/http-guzzle-bridge.svg?branch=master)](https://travis-ci.org/neat-php/http-guzzle-bridge)



Getting started
---------------
To install this package, simply issue [composer](https://getcomposer.org) on the
command line:
```
composer require neat/http-guzzle-bridge
```

Then capture the request, do your thing and send a response:
```php
<?php

$receiver = new \Neat\Http\Guzzle\Receiver();
$request = $receiver->getRequest();

// ...

$transmitter = new \Neat\Http\Guzzle\Transmitter();
$response = $transmitter->html("Here's my response");
$transmitter->send($response);
```
