<?php
// Handler configuration

// Not Found handler
$container['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        return $response->withStatus(404)
            ->withHeader('Content-Type', 'application/json')
            ->withJson(['cod' => 404, 'message' => 'Página no encontrada']);
    };
};

?>
