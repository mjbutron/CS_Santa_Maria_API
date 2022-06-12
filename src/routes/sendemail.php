<?php
use Slim\Http\Request;
use Slim\Http\Response;
use \Firebase\JWT\JWT;

// Send email

// POST: Send email
$app->post('/api/sendEmail', function(Request $request, Response $response, array $args){

  $settings = $this->get('settings'); // get settings array.
  $to = $settings['emails']['information'];
  $name = $request->getParam('name');
  $surname = $request->getParam('surname');
  $email = $request->getParam('email');
  $subject = $request->getParam('subject');
  $message = $request->getParam('message');

  $content = "
    <html>
      <head>
        <title>Title</title>
      </head>
      <body>
        <hr style='border-color: #0095A6;'>
        <div style='font-family: Amatic SC, cursive;'>" . $subject . "</div>
        <hr style='border-color: #04c1c3;'>
        <div style='font-size: 10px;'><b>Datos del usuario</b></div>
        <div style='font-size: 10px;'>Nombre: "  . $name . "</div>
        <div style='font-size: 10px;'>Apellidos: "  . $surname . "</div>
        <div style='font-size: 10px;'>Email: "  . $email . "</div>
        <div style='font-size: 10px; margin-top:10px;'><b>Mensaje</b></div>
        <div style='font-size: 10px;margin-bottom:10px;'>" . $message . "</div>
        <hr style='border-color: #0095A6;'>
        <div style='font-size: 8px;color:#638699'>*Email de contacto enviado desde la web del Centro Sanitario Santa María.</div>
      </body>
    </html>
    ";

  $headers = "MIME-Version: 1.0\r\n";
  $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

  if(mail($to, $subject, $content, $headers)){
    return $this->response->withJson(['cod' => '200', 'message' => 'Email enviado']);
  }
  else {
    return $this->response->withStatus(503)->withHeader('Content-Type', 'application/json')
    ->withJson(['cod' => 503, 'message' => $e]);
  }
});
