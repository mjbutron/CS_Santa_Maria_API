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
// Register routes
require __DIR__ . '/../src/access.php';
require __DIR__ . '/../src/slider.php';
require __DIR__ . '/../src/service.php';
require __DIR__ . '/../src/upload.php';
require __DIR__ . '/../src/user.php';
require __DIR__ . '/../src/workshop.php';
require __DIR__ . '/../src/course.php';
require __DIR__ . '/../src/homeinfo.php';
require __DIR__ . '/../src/footerinfo.php';
require __DIR__ . '/../src/contact.php';
require __DIR__ . '/../src/opinion.php';
require __DIR__ . '/../src/aboutus.php';
require __DIR__ . '/../src/roles.php';
require __DIR__ . '/../src/users.php';
require __DIR__ . '/../src/notification.php';
require __DIR__ . '/../src/sendemail.php';

$app->run();

?>
