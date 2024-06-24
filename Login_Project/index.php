<?php

require '../vendor/autoload.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$log = new Logger('name');
$log->pushHandler(new StreamHandler('../logs/app.log', Logger::WARNING));

$log->warning('Foo');
$log->error('Bar');

echo "Hello, World!";
