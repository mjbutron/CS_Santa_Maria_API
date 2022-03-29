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

    return $this->response->withJson(['cod' => '200', 'allRoles' => $roles]);
    $res = null;

  }catch(PDOException $e){
    return $this->response->withStatus(503)->withHeader('Content-Type', 'application/json')
    ->withJson(['cod' => 503, 'message' => 'No es posible conectar con la base de datos.']);
  }
});

?>
