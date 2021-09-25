<?php
use Slim\Http\Request;
use Slim\Http\Response;
use \Firebase\JWT\JWT;

// Send email

// POST: Send email
$app->post('/api/sendEmail', function(Request $request, Response $response, array $args){

  $to = $request->getParam('to');
  $subject = $request->getParam('subject');
  $message = $request->getParam('message');

  // $to = "destinatario@email.com, destinatario2@email.com, destinatario3@email.com";
  // $subject = "Asunto del email";
  // $message = "Este es mi primer envío de email con PHP";
  // $headers = "From: mi@cuentadeemail.com" . "\r\n" . "CC: destinatarioencopia@email.com";

mail($to, $subject, $message, $headers);

});
