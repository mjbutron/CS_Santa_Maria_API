<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
//use Slim\Factory\AppFactory;

require '../vendor/autoload.php';
require '../src/config/db.php';

$app = new \Slim\App;

require_once('../src/routes/service.php');
require_once('../src/routes/slider.php');

$app->run();
