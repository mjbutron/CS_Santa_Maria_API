<?php
use Slim\Http\Request;
use Slim\Http\Response;
use \Firebase\JWT\JWT;

// Routes Services

// GET: Get all services
$app->get('/api/allServices', function(Request $request, Response $response, array $args){
  $sql = "SELECT * FROM service";
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


// POST: Add new service
$app->post('/admin/api/service/new', function(Request $request, Response $response, array $args){
   $title = $request->getParam('title');
   $image_home = $request->getParam('image_home');
   $subtitle_home = $request->getParam('subtitle_home');
   $description_home = $request->getParam('description_home');
   $home = $request->getParam('home');
   $image = $request->getParam('image');
   $subtitle = $request->getParam('subtitle');
   $description = $request->getParam('description');
   $user = $request->getParam('user');

  $sql = "INSERT INTO service (title, image_home, subtitle_home, description_home, home, image, subtitle, description, create_date, update_date, user_id)
  VALUES (:title, :image_home, :subtitle_home, :description_home, :home, :image, :subtitle, :description,  CURRENT_TIMESTAMP(),  CURRENT_TIMESTAMP(), :user)";

  try{
    $res = $this->db->prepare($sql);
    $res->bindParam(':title', $title);
    $res->bindParam(':image_home', $image_home);
    $res->bindParam(':subtitle_home', $subtitle_home);
    $res->bindParam(':description_home', $description_home);
    $res->bindParam(':home', $home);
    $res->bindParam(':image', $image);
    $res->bindParam(':subtitle', $subtitle);
    $res->bindParam(':description', $description);
    $res->bindParam(':user', $user);
    $res->execute();
    return $this->response->withJson(['cod' => '200', 'message' => 'Nuevo servicio creado.']);
    $res = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
});

// PUT: Update service
$app->put('/admin/api/service/update/{id}', function(Request $request, Response $response, array $args){
   $id_service = $request->getAttribute('id');
   $title = $request->getParam('title');
   $image_home = $request->getParam('image_home');
   $subtitle_home = $request->getParam('subtitle_home');
   $description_home = $request->getParam('description_home');
   $home = $request->getParam('home');
   $image = $request->getParam('image');
   $subtitle = $request->getParam('subtitle');
   $description = $request->getParam('description');
   $user = $request->getParam('user');

  $sql= "UPDATE service SET
          title = :title,
          image_home = :image_home,
          subtitle_home = :subtitle_home,
          description_home = :description_home,
          home = :home,
          image = :image,
          subtitle = :subtitle,
          description = :description,
          user_id = :user
          WHERE id = $id_service";

  try{
    $res = $this->db->prepare($sql);
    $res->bindParam(':title', $title);
    $res->bindParam(':image_home', $image_home);
    $res->bindParam(':subtitle_home', $subtitle_home);
    $res->bindParam(':description_home', $description_home);
    $res->bindParam(':home', $home);
    $res->bindParam(':image', $image);
    $res->bindParam(':subtitle', $subtitle);
    $res->bindParam(':description', $description);
    $res->bindParam(':user', $user);
    $res->execute();
    return $this->response->withJson(['cod' => '200', 'message' => 'Servicio actualizado.']);
    $res = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
});

?>
