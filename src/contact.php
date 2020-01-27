<?php
use Slim\Http\Request;
use Slim\Http\Response;
use \Firebase\JWT\JWT;

// Router Contact
$app->get('/api/contact/info', function(Request $request, Response $response, array $args){
  $sql = "SELECT id, cnt_address, cnt_ph_appo, cnt_emails, cnt_ph_mwives, cnt_ph_physio, cnt_lat, cnt_lon
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
