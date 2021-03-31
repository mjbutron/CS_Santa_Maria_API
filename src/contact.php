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

    return $this->response->withJson(['cod' => '200', 'contactInfo' => $info]);
    $res = null;

  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
});

// PUT: Update information
$app->put('/admin/api/contact/info/update/{id}', function(Request $request, Response $response, array $args){
   $id = $request->getAttribute('id');
   $cnt_address = $request->getParam('cnt_address');
   $cnt_ph_appo = $request->getParam('cnt_ph_appo');
   $cnt_emails = $request->getParam('cnt_emails');
   $cnt_ph_mwives = $request->getParam('cnt_ph_mwives');
   $cnt_ph_physio = $request->getParam('cnt_ph_physio');
   $cnt_lat = $request->getParam('cnt_lat');
   $cnt_lon = $request->getParam('cnt_lon');

  $sql = "UPDATE options SET
          cnt_address = :cnt_address,
          cnt_ph_appo = :cnt_ph_appo,
          cnt_emails = :cnt_emails,
          cnt_ph_mwives = :cnt_ph_mwives,
          cnt_ph_physio = :cnt_ph_physio,
          cnt_lat = :cnt_lat,
          cnt_lon = :cnt_lon
        WHERE id = $id";

  try{
    $res = $this->db->prepare($sql);
    $res->bindParam(':cnt_address', $cnt_address);
    $res->bindParam(':cnt_ph_appo', $cnt_ph_appo);
    $res->bindParam(':cnt_emails', $cnt_emails);
    $res->bindParam(':cnt_ph_mwives', $cnt_ph_mwives);
    $res->bindParam(':cnt_ph_physio', $cnt_ph_physio);
    $res->bindParam(':cnt_lat', $cnt_lat);
    $res->bindParam(':cnt_lon', $cnt_lon);
    $res->execute();
    return $this->response->withJson(['cod' => '200', 'message' => 'Información actualizada.']);
    $res = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
});

?>
