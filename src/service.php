<?php
use Slim\Http\Request;
use Slim\Http\Response;
use \Firebase\JWT\JWT;

// Routes Services

// GET: Get all services
$app->get('/api/allServices', function(Request $request, Response $response, array $args){
  $sql = "SELECT * FROM service ORDER BY create_date DESC";
  try{
    $res = $this->db->prepare($sql);
    $res->execute();
    $services = $res->fetchAll(PDO::FETCH_OBJ);

    if($services) {
      return $this->response->withJson($services);
    }else{
      return $this->response->withJson(['cod' => '404', 'message' => 'Datos no disponibles.']);
    }

    $res = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
});

// GET: Get services by page
$app->get('/api/servicesByPage/{page}', function(Request $request, Response $response, array $args){
  $page = $request->getAttribute('page');
  $resultPerPage = 5;
  $start = ($page - 1) * $resultPerPage;

  $sql = "SELECT COUNT(*) FROM service";
  $sqlPage = "SELECT * FROM service ORDER BY create_date ASC LIMIT $start, $resultPerPage";
  try{
    $res = $this->db->prepare($sql);
    $res->execute();
    $count = $res->fetchColumn();

    if($count) {
      $res = $this->db->prepare($sqlPage);
      $res->execute();
      $workshops = $res->fetchAll(PDO::FETCH_OBJ);
      return $this->response->withJson(['allServices' => $workshops, 'total' => $count, 'actual' => $page, 'totalPages' => ceil($count/$resultPerPage)]);
    }else{
      return $this->response->withJson(['cod' => '404', 'message' => 'Datos no disponibles.']);
    }

    $res = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
});

// POST: Add new service
$app->post('/admin/api/services/new', function(Request $request, Response $response, array $args){
   $title = $request->getParam('title');
   $image = $request->getParam('image');
   $subtitle = $request->getParam('subtitle');
   $description = $request->getParam('description');
   $user_id = $request->getParam('user_id');



  $sql = "INSERT INTO service (
            title,
            image,
            subtitle,
            description,
            user_id)
          VALUES (
            :title,
            :image,
            :subtitle,
            :description,
            :user_id)";

  try{
    $res = $this->db->prepare($sql);
    $res->bindParam(':title', $title);
    $res->bindParam(':image', $image);
    $res->bindParam(':subtitle', $subtitle);
    $res->bindParam(':description', $description);
    $res->bindParam(':user_id', $user_id);
    $res->execute();
    return $this->response->withJson(['cod' => '200', 'message' => 'Nuevo servicio creado.']);
    $res = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
});

// PUT: Update service by ID
$app->put('/admin/api/services/update/{id}', function(Request $request, Response $response, array $args){
   $id_service = $request->getAttribute('id');
   $title = $request->getParam('title');
   $image = $request->getParam('image');
   $subtitle = $request->getParam('subtitle');
   $description = $request->getParam('description');
   $user_id = $request->getParam('user_id');

  $sql= "UPDATE service SET
          title = :title,
          image = :image,
          subtitle = :subtitle,
          description = :description,
          user_id = :user_id
          WHERE id = $id_service";

  try{
    $res = $this->db->prepare($sql);
    $res->bindParam(':title', $title);
    $res->bindParam(':image', $image);
    $res->bindParam(':subtitle', $subtitle);
    $res->bindParam(':description', $description);
    $res->bindParam(':user_id', $user_id);
    $res->execute();
    return $this->response->withJson(['cod' => '200', 'message' => 'Servicio actualizado.']);
    $res = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
});

// DELETE: Delete service by ID
$app->delete('/admin/api/services/delete/{id}', function(Request $request, Response $response, array $args){
  $id_service = $request->getAttribute('id');
  $sql = "DELETE FROM service WHERE id = $id_service";
  try{
    $res = $this->db->prepare($sql);
    $res->bindParam(':id', $id_service);
    $res->execute();
    return $this->response->withJson(['cod' => '200', 'message' => 'Servicio eliminado.']);

    $res = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
});

?>
