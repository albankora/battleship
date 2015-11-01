<?php
include __DIR__ . '/../vendor/autoload.php';
include __DIR__ . '/../configs/app.php';
include __DIR__ . '/../configs/dependencies.php';
include __DIR__ . '/../configs/routes.php';
$app = \Core\App::getInstance();
$app->run();