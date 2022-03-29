<?php

require __DIR__ . '/../vendor/autoload.php';
// Instantiate the app with settings
$settings = require __DIR__ . '/../src/configuration/settings.php';
$app = new \Slim\App($settings);
$container = $app->getContainer();

// Register data base configuration
require __DIR__ . '/../src/configuration/db.php';
// Register handler configuration
require __DIR__ . '/../src/handlers/handlers.php';
// Register middleware configuration
require __DIR__ . '/../src/middleware/middleware.php';
// Register services
require __DIR__ . '/../src/services/upload.php';
// Register routes
require __DIR__ . '/../src/routes/routes_config.php';

$app->run();

?>
