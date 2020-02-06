<?php
use Slim\Http\Request;
use Slim\Http\Response;
use \Firebase\JWT\JWT;

// Footer Information
$app->get('/api/footer/info', function(Request $request, Response $response, array $args){
  $sql = "SELECT id, footer_address, footer_email, footer_ph, footer_schdl
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
$app->put('/admin/api/footer/info/update/{id}', function(Request $request, Response $response, array $args){
   $id = $request->getAttribute('id');
   $footer_address = $request->getParam('footer_address');
   $footer_email = $request->getParam('footer_email');
   $footer_ph = $request->getParam('footer_ph');
   $footer_schdl = $request->getParam('footer_schdl');

  $sql = "UPDATE options SET
          footer_address = :footer_address,
          footer_email = :footer_email,
          footer_ph = :footer_ph,
          footer_schdl = :footer_schdl
        WHERE id = $id";

  try{
    $res = $this->db->prepare($sql);
    $res->bindParam(':footer_address', $footer_address);
    $res->bindParam(':footer_email', $footer_email);
    $res->bindParam(':footer_ph', $footer_ph);
    $res->bindParam(':footer_schdl', $footer_schdl);
    $res->execute();
    return $this->response->withJson(['cod' => '200', 'message' => 'Información actualizada.']);
    $res = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
});

?>