<?php
// Application middleware configuration

// Middleware for enabling CORS
$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
});

// Middleware enabling Authentication
$app->add(new \Tuupola\Middleware\JwtAuthentication([
    "path" => "/admin",
    "attribute" => "decoded_token_data",
    "secure" => false,
    "secret" => "*****",
    "algorithm" => ["HS256"],
    "error" => function ($response, $arguments) {
        $data["status"] = "error";
        $data["message"] = $arguments["message"];
        return $response->withStatus(401)
        ->withHeader('Content-Type', 'application/json')
        ->withJson(['error' => true, 'message' => 'Acceso denegado']);
    }
]));

?>
