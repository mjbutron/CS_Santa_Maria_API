<?php
use Slim\Http\Request;
use Slim\Http\Response;
use \Firebase\JWT\JWT;

// Routes User

// GET: Get user
$app->get('/admin/api/userAccount', function(Request $request, Response $response, array $args){
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
