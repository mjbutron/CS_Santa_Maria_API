<?php

require __DIR__ . '/../vendor/autoload.php';
// Instantiate the app
$settings = require __DIR__ . '/../src/settings.php';
$app = new \Slim\App($settings);
$container = $app->getContainer();

// Register dependencies
require __DIR__ . '/../src/dependencies.php';
// Register middleware
require __DIR__ . '/../src/middleware.php';
// Register routes
require __DIR__ . '/../src/routes.php';
require __DIR__ . '/../src/slider.php';
require __DIR__ . '/../src/service.php';
require __DIR__ . '/../src/upload.php';
require __DIR__ . '/../src/user.php';
require __DIR__ . '/../src/workshop.php';
require __DIR__ . '/../src/course.php';
require __DIR__ . '/../src/homeinfo.php';

$app->run();

?>
