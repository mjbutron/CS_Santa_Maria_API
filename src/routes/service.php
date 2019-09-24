<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// Routes Services

// GET: Get all services
$app->get('/api/allServices', function(Request $request, Response $response){
  $sql = "SELECT * FROM service";
  try{
    $db = new db();
    $db = $db->conectDB();
    $result = $db->query($sql);
    if ($result->rowCount() > 0){
      $services = $result->fetchAll(PDO::FETCH_OBJ);
      echo json_encode($services);
    }else {
      echo json_encode("¡Ups! No existen servicios para mostrar.");
    }
    $result = null;
    $db = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
});

// POST: Add new service
$app->post('/api/service/new', function(Request $request, Response $response){
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
    $db = new db();
    $db = $db->conectDB();
    $result = $db->prepare($sql);
    $result->bindParam(':title', $title);
    $result->bindParam(':image_home', $image_home);
    $result->bindParam(':subtitle_home', $subtitle_home);
    $result->bindParam(':description_home', $description_home);
    $result->bindParam(':home', $home);
    $result->bindParam(':image', $image);
    $result->bindParam(':subtitle', $subtitle);
    $result->bindParam(':description', $description);
    $result->bindParam(':user', $user);
    $result->execute();
    echo json_encode("Nuevo servicio creado.");
    $result = null;
    $db = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
});

// PUT: Update service
$app->put('/api/service/update/{id}', function(Request $request, Response $response){
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
    $db = new db();
    $db = $db->conectDB();
    $result = $db->prepare($sql);
    $result->bindParam(':title', $title);
    $result->bindParam(':image_home', $image_home);
    $result->bindParam(':subtitle_home', $subtitle_home);
    $result->bindParam(':description_home', $description_home);
    $result->bindParam(':home', $home);
    $result->bindParam(':image', $image);
    $result->bindParam(':subtitle', $subtitle);
    $result->bindParam(':description', $description);
    $result->bindParam(':user', $user);
    $result->execute();
    echo json_encode("Servicio actualizado.");
    $result = null;
    $db = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
});
