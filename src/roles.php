<?php
use Slim\Http\Request;
use Slim\Http\Response;
use \Firebase\JWT\JWT;

// Routes Roles

// GET: Get all roles
$app->get('/api/allRoles', function(Request $request, Response $response, array $args){
  $sql = "SELECT * FROM rol ORDER BY id ASC";
  try{
    $res = $this->db->prepare($sql);
    $res->execute();
    $roles = $res->fetchAll(PDO::FETCH_OBJ);

    if($roles) {
      return $this->response->withJson($roles);
    }else{
      return $this->response->withJson(['cod' => '404', 'message' => 'Datos no disponibles.']);
    }

    $res = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
});

?>
