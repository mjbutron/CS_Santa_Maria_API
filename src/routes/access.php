<?php
use Slim\Http\Request;
use Slim\Http\Response;
use \Firebase\JWT\JWT;

// Routes access

// POST: Login
$app->post('/login', function (Request $request, Response $response, array $args) {
    $input = $request->getParsedBody();
    $sql = "SELECT u.id, u.active, u.name, u.surname, u.email, u.telephone, u.password, u.change_pass, u.image, r.rol_name
            FROM user u
            INNER JOIN rol r
            ON u.rol_id = r.id
            WHERE u.email= :email";
    try{
      $sth = $this->db->prepare($sql);
      $sth->bindParam("email", $input['email']);
      $sth->execute();
      $user = $sth->fetchObject();

      // verify email address.
      if(!$user) {
          return $this->response->withStatus(200)
          ->withHeader('Content-Type', 'application/json')
          ->withJson(['cod' => 200, 'error' => true, 'message' => '¡Nombre de usuario o contraseña incorrectos!']);
      }

      // verify password.
      if (!password_verify($input['password'],$user->password)) {
        return $this->response->withStatus(200)
        ->withHeader('Content-Type', 'application/json')
        ->withJson(['cod' => 200, 'error' => true, 'message' => '¡Nombre de usuario o contraseña incorrectos!']);
      }

      // verify if user is active
      if(0 == $user->active) {
          return $this->response->withStatus(200)
          ->withHeader('Content-Type', 'application/json')
          ->withJson(['cod' => 200, 'error' => true, 'message' => 'Usuario bloqueado. Contacte con el administrador.']);
      }

      $settings = $this->get('settings'); // get settings array.

      $token = JWT::encode(['id' => $user->id, 'email' => $user->email], $settings['jwt']['secret'], "HS256");
      $user->password = "";
      return $this->response->withJson(['cod' => 200, 'error' => false, 'token' => $token, 'user' => $user]);
    }catch(PDOException $e){
      return $this->response->withStatus(503)->withHeader('Content-Type', 'application/json')
      ->withJson(['cod' => 503, 'error' => true, 'message' => 'No es posible conectar con la base de datos.']);
    }
});

// PUT: Logout
$app->put('/admin/logout', function(Request $request, Response $response, array $args){
   $input = $request->getParsedBody();
   $today = date("Y-m-d");

  $sql = "UPDATE user SET
          last_login = :lastday
        WHERE email = :email";

  try{
    $res = $this->db->prepare($sql);
    $res->bindParam("lastday", $today);
    $res->bindParam("email", $input['email']);
    $res->execute();

    return $this->response->withJson(['cod' => '200', 'error' => false, 'message' => 'Desconectado correctamente.']);
    $res = null;
  }catch(PDOException $e){
    return $this->response->withStatus(503)->withHeader('Content-Type', 'application/json')
    ->withJson(['cod' => 503, 'error' => true, 'message' => 'No es posible conectar con la base de datos.']);
  }
});
