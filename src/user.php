<?php
use Slim\Http\Request;
use Slim\Http\Response;
use \Firebase\JWT\JWT;

// Routes User

// POST: Get user profile
$app->post('/admin/api/userProfile', function(Request $request, Response $response, array $args){
  $sql = "SELECT * FROM user WHERE email= :email";
  try{
    $input = $request->getParsedBody();
    $res = $this->db->prepare($sql);
    $res->bindParam("email", $input['email']);
    $res->execute();
    $user = $res->fetchObject();

    return $this->response->withJson(['cod' => '200', 'user' => $user]);
    $res = null;

  }catch(PDOException $e){
    return $this->response->withStatus(503)->withHeader('Content-Type', 'application/json')
    ->withJson(['cod' => 503, 'message' => 'No es posible conectar con la base de datos.']);
  }
});

// PUT: Update user profile
$app->put('/admin/api/userProfile/update/{id}', function(Request $request, Response $response, array $args){
   $id = $request->getAttribute('id');
   $name = $request->getParam('name');
   $surname = $request->getParam('surname');
   $telephone = $request->getParam('telephone');
   $address = $request->getParam('address');
   $city = $request->getParam('city');
   $province = $request->getParam('province');
   $zipcode = $request->getParam('zipcode');
   $user_fcbk = $request->getParam('user_fcbk');
   $user_ytube = $request->getParam('user_ytube');
   $user_insta = $request->getParam('user_insta');
   $image = $request->getParam('image');

  $sql = "UPDATE user SET
          name = :name,
          surname = :surname,
          telephone = :telephone,
          address = :address,
          city = :city,
          province = :province,
          zipcode = :zipcode,
          user_fcbk = :user_fcbk,
          user_ytube = :user_ytube,
          user_insta = :user_insta,
          image = :image
        WHERE id = $id";

  try{
    $res = $this->db->prepare($sql);
    $res->bindParam(':name', $name);
    $res->bindParam(':surname', $surname);
    $res->bindParam(':telephone', $telephone);
    $res->bindParam(':address', $address);
    $res->bindParam(':city', $city);
    $res->bindParam(':province', $province);
    $res->bindParam(':zipcode', $zipcode);
    $res->bindParam(':user_fcbk', $user_fcbk);
    $res->bindParam(':user_ytube', $user_ytube);
    $res->bindParam(':user_insta', $user_insta);
    $res->bindParam(':image', $image);
    $res->execute();
    return $this->response->withJson(['cod' => '200', 'message' => 'Información actualizada.']);
    $res = null;
  }catch(PDOException $e){
    return $this->response->withStatus(503)->withHeader('Content-Type', 'application/json')
    ->withJson(['cod' => 503, 'message' => 'No es posible conectar con la base de datos.']);
  }
});

// POST: Check current password
$app->post('/admin/api/checkPassword', function(Request $request, Response $response, array $args){
  $input = $request->getParsedBody();
  $sql = "SELECT id, password FROM user WHERE id= :id";
  try{
    $input = $request->getParsedBody();
    $res = $this->db->prepare($sql);
    $res->bindParam("id", $input['id']);
    $res->execute();
    $user = $res->fetchObject();

    // verify password.
    if (!password_verify($input['password'],$user->password)) {
      return $this->response->withJson(['cod' => '200', 'check' => 0]);
    }

    return $this->response->withJson(['cod' => '200', 'check' => 1]);

    $res = null;
  }catch(PDOException $e){
    return $this->response->withStatus(503)->withHeader('Content-Type', 'application/json')
    ->withJson(['cod' => 503, 'message' => 'No es posible conectar con la base de datos.']);
  }
});

// PUT: Update password
$app->put('/admin/api/userProfile/updatePass/{id}', function(Request $request, Response $response, array $args){
   $id = $request->getAttribute('id');
   $password = $request->getParam('password');
   $password = password_hash($password, PASSWORD_DEFAULT);

  $sql = "UPDATE user SET
          password = :password,
          change_pass = 1
        WHERE id = $id";

  try{
    $res = $this->db->prepare($sql);
    $res->bindParam(':password', $password);

    $res->execute();
    return $this->response->withJson(['cod' => '200', 'message' => 'Información actualizada.']);
    $res = null;
  }catch(PDOException $e){
    return $this->response->withStatus(503)->withHeader('Content-Type', 'application/json')
    ->withJson(['cod' => 503, 'message' => 'No es posible conectar con la base de datos.']);
  }
});

// POST: Get user data
$app->post('/admin/api/userData', function(Request $request, Response $response, array $args){
  $sql = "SELECT u.id, u.active, u.name, u.surname, u.email, u.telephone,
                  u.address, u.city, u.province, u.zipcode,
                  u.password, u.change_pass, u.image, r.rol_name
          FROM user u
          INNER JOIN rol r
          ON u.rol_id = r.id
          WHERE u.email= :email";
  try{
    $input = $request->getParsedBody();
    $res = $this->db->prepare($sql);
    $res->bindParam("email", $input['email']);
    $res->execute();
    $userData = $res->fetchObject();

    return $this->response->withJson(['cod' => '200', 'userData' => $userData]);
    $res = null;

  }catch(PDOException $e){
    return $this->response->withStatus(503)->withHeader('Content-Type', 'application/json')
    ->withJson(['cod' => 503, 'message' => 'No es posible conectar con la base de datos.']);
  }
});
