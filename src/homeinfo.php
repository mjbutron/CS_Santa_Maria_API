<?php
use Slim\Http\Request;
use Slim\Http\Response;
use \Firebase\JWT\JWT;

// GET: Home Information
$app->get('/api/home/info', function(Request $request, Response $response, array $args){
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

// PUT: Update information
$app->put('/admin/api/home/info/update/{id}', function(Request $request, Response $response, array $args){
   $id = $request->getAttribute('id');
   $home_first_ph = $request->getParam('home_first_ph');
   $home_second_ph = $request->getParam('home_second_ph');
   $home_fcbk = $request->getParam('home_fcbk');
   $home_ytube = $request->getParam('home_ytube');
   $home_insta = $request->getParam('home_insta');

  $sql = "UPDATE options SET
          home_first_ph = :home_first_ph,
          home_second_ph = :home_second_ph,
          home_fcbk = :home_fcbk,
          home_ytube = :home_ytube,
          home_insta = :home_insta
        WHERE id = $id";

  try{
    $res = $this->db->prepare($sql);
    $res->bindParam(':home_first_ph', $home_first_ph);
    $res->bindParam(':home_second_ph', $home_second_ph);
    $res->bindParam(':home_fcbk', $home_fcbk);
    $res->bindParam(':home_ytube', $home_ytube);
    $res->bindParam(':home_insta', $home_insta);
    $res->execute();
    return $this->response->withJson(['cod' => '200', 'message' => 'Información actualizada.']);
    $res = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
});

?>
