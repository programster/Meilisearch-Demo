<?php

require_once(__DIR__ . '/defines.php');
require_once(__DIR__ . '/vendor/autoload.php');

$autoloader = new \iRAP\Autoloader\Autoloader([
    __DIR__,
    __DIR__ . '/libs',
    __DIR__ . '/controllers',
    __DIR__ . '/models',
    __DIR__ . '/views',
]);

$dotenv = new Symfony\Component\Dotenv\Dotenv();
$dotenv->load(__DIR__ . '/.env');
