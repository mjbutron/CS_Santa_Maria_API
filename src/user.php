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

  $sql = "UPDATE user SET
          name = :name,
          surname = :surname,
          telephone = :telephone,
          address = :address,
          city = :city,
          province = :province,
          zipcode = :zipcode,
          aboutme = :aboutme
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
    $res->execute();
    return $this->response->withJson(['cod' => '200', 'message' => 'Información actualizada.']);
    $res = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
});
