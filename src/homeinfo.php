<?php
use Slim\Http\Request;
use Slim\Http\Response;
use \Firebase\JWT\JWT;

// Home Information
$app->get('/admin/api/home/info', function(Request $request, Response $response, array $args){
  $sql = "SELECT id, home_first_ph, home_second_ph, home_fcbk, home_ytube, home_insta
  FROM options";
  try{
    $res = $this->db->prepare($sql);
    $res->execute();
    $info = $res->fetchAll(PDO::FETCH_OBJ);

    if($info) {
      return $this->response->withJson($info);
    }else{
      return $this->response->withJson(['cod' => '404', 'message' => 'Datos no disponibles.']);
    }

    $res = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
});

?>
