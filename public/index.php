<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
//use Slim\Factory\AppFactory;

require '../vendor/autoload.php';
require '../src/config/db.php';

$app = new \Slim\App;

require_once('../src/routes/service.php');
require_once('../src/routes/slider.php');

// Add access control
$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
            ->withHeader('Access-Control-Allow-Origin', 'http://localhost:4200') // TODO cambiar por url origen
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE,');
});

$app->run();
