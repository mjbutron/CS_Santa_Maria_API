<?php
// Configuration

// DB instance
$container['db'] = function ($c) {
    $db = $c['settings']['db'];
    $pdo = new PDO("mysql:host=" . $db['host'] . ";dbname=" . $db['name'] . ";charset=utf8", $db['user'], $db['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
};

// Not Found handler
$container['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        return $response->withStatus(404)
            ->withHeader('Content-Type', 'application/json')
            ->withJson(['cod' => 404, 'message' => 'Página no encontrada']);
    };
};

?>
