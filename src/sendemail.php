<?php
use Slim\Http\Request;
use Slim\Http\Response;
use \Firebase\JWT\JWT;

// Send email

// POST: Send email
$app->post('/api/sendEmail', function(Request $request, Response $response, array $args){

  $to = "info@sisloc.es"; // Email del centro
  $name = $request->getParam('name');
  $surname = $request->getParam('surname');
  $email = $request->getParam('email');
  $subject = $request->getParam('subject');
  $message = $request->getParam('message');
  $content = "Nombre: " . $name . "\nApellidos: " . $surname . "\nEmail: " . $email . "\n\nMensaje:\n" . $message . "\n\nEmail de contacto enviado desde la web del Centro Sanitario Santa María.";

  if(mail($to, $subject, $content)){
    return $this->response->withJson(['cod' => '200', 'message' => 'Email enviado']);
  }
  else {
    return $this->response->withStatus(503)->withHeader('Content-Type', 'application/json')
    ->withJson(['cod' => 503, 'message' => $e]);
  }
});
