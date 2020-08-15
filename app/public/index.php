<?php

require_once(__DIR__ . '/../bootstrap.php');

$app = Slim\Factory\AppFactory::create();
$app->addErrorMiddleware($displayErrorDetails=true, $logErrors=true, $logErrorDetails=true);

DocumentController::registerRoutes($app);

$app->run();