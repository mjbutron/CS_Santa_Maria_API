<?php
use Slim\Http\Request;
use Slim\Http\Response;
use \Firebase\JWT\JWT;

// Routes User

// POST: Get user profile
$app->post('/admin/api/userProfile', function(Request $request, Response $response, array $args){
  $input = $request->getParsedBody();
  $sql = "SELECT * FROM user WHERE email= :email";
  try{
    $input = $request->getParsedBody();
    $res = $this->db->prepare($sql);
    $res->bindParam("email", $input['email']);
    $res->execute();
    $user = $res->fetchObject();

    if($user) {
      return $this->response->withJson($user);
    }else{
      return $this->response->withJson(['cod' => '404', 'message' => 'El usuario no existe.']);
    }

    $res = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
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
   $aboutme = $request->getParam('aboutme');
   $userFcbk = $request->getParam('userFcbk');
   $userYtube = $request->getParam('userYtube');
   $userInsta = $request->getParam('userInsta');

  $sql = "UPDATE user SET
          name = :name,
          surname = :surname,
          telephone = :telephone,
          address = :address,
          city = :city,
          province = :province,
          zipcode = :zipcode,
          aboutme = :aboutme,
          user_fcbk = :userFcbk,
          user_ytube = :userYtube,
          user_insta = :userInsta
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
    $res->bindParam(':aboutme', $aboutme);
    $res->bindParam(':userFcbk', $userFcbk);
    $res->bindParam(':userYtube', $userYtube);
    $res->bindParam(':userInsta', $userInsta);
    $res->execute();
    return $this->response->withJson(['cod' => '200', 'message' => 'Información actualizada.']);
    $res = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
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
      return $this->response->withJson(['check' => 0]);
    }

    return $this->response->withJson(['check' => 1]);

    $res = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
});

// PUT: Update password
$app->put('/admin/api/userProfile/updatePass/{id}', function(Request $request, Response $response, array $args){
   $id = $request->getAttribute('id');
   $password = $request->getParam('password');
   $password = password_hash($password, PASSWORD_DEFAULT);

  $sql = "UPDATE user SET
          password = :password
        WHERE id = $id";

  try{
    $res = $this->db->prepare($sql);
    $res->bindParam(':password', $password);

    $res->execute();
    return $this->response->withJson(['cod' => '200', 'message' => 'Información actualizada.']);
    $res = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
});
