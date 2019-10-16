<?php

use Slim\Http\Request;
use Slim\Http\Response;
use \Firebase\JWT\JWT;

$app->post('/login', function (Request $request, Response $response, array $args) {

    $input = $request->getParsedBody();
    $sql = "SELECT id, name, surname, email, telephone, password FROM user WHERE email= :email";
    $sth = $this->db->prepare($sql);
    $sth->bindParam("email", $input['email']);
    $sth->execute();
    $user = $sth->fetchObject();

    // verify email address.
    if(!$user) {
        return $this->response->withStatus(400)
        ->withHeader('Content-Type', 'application/json')
        ->withJson(['error' => true, 'message' => 'These credentials do not match our records.']);
    }

    // verify password.
    if (!password_verify($input['password'],$user->password)) {
      return $this->response->withStatus(400)
      ->withHeader('Content-Type', 'application/json')
      ->withJson(['error' => true, 'message' => 'These credentials do not match our records.']);
    }

    $settings = $this->get('settings'); // get settings array.

    $token = JWT::encode(['id' => $user->id, 'email' => $user->email], $settings['jwt']['secret'], "HS256");
    $user->password = "";
    return $this->response->withJson(['token' => $token, 'user' => $user]);

});
