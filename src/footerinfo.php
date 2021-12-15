<?php
use Slim\Http\Request;
use Slim\Http\Response;
use \Firebase\JWT\JWT;

// Routes Footer

// GET: Get footer information
$app->get('/api/footer/info', function(Request $request, Response $response, array $args){
  $sql = "SELECT id, footer_address, footer_email, footer_ph, footer_schdl
  FROM options";
  try{
    $res = $this->db->prepare($sql);
    $res->execute();
    $info = $res->fetchAll(PDO::FETCH_OBJ);

    return $this->response->withJson(['cod' => '200', 'footerInfo' => $info]);
    $res = null;

  }catch(PDOException $e){
    return $this->response->withStatus(503)->withHeader('Content-Type', 'application/json')
    ->withJson(['cod' => 503, 'message' => 'No es posible conectar con la base de datos.']);
  }
});

// PUT: Update information
$app->put('/admin/api/footer/info/update/{id}', function(Request $request, Response $response, array $args){
   $id = $request->getAttribute('id');
   $footer_address = $request->getParam('footer_address');
   $footer_email = $request->getParam('footer_email');
   $footer_ph = $request->getParam('footer_ph');
   $footer_schdl = $request->getParam('footer_schdl');
   $user_id = $request->getParam('user_id');

  $sql = "UPDATE options SET
          footer_address = :footer_address,
          footer_email = :footer_email,
          footer_ph = :footer_ph,
          footer_schdl = :footer_schdl,
          user_id = :user_id
        WHERE id = $id";

  try{
    $res = $this->db->prepare($sql);
    $res->bindParam(':footer_address', $footer_address);
    $res->bindParam(':footer_email', $footer_email);
    $res->bindParam(':footer_ph', $footer_ph);
    $res->bindParam(':footer_schdl', $footer_schdl);
    $res->bindParam(':user_id', $user_id);
    $res->execute();
    return $this->response->withJson(['cod' => '200', 'message' => 'Información actualizada.']);
    $res = null;
  }catch(PDOException $e){
    return $this->response->withStatus(503)->withHeader('Content-Type', 'application/json')
    ->withJson(['cod' => 503, 'message' => 'No es posible conectar con la base de datos.']);
  }
});

?>
