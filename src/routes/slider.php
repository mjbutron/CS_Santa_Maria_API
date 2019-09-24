<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// Routes Slider

// GET: Get all slider
$app->get('/api/allSlider', function(Request $request, Response $response){
  $sql = "SELECT * FROM slider";
  try{
    $db = new db();
    $db = $db->conectDB();
    $result = $db->query($sql);
    if ($result->rowCount() > 0){
      $sliders = $result->fetchAll(PDO::FETCH_OBJ);
      echo json_encode($sliders);
    }else {
      echo json_encode("Ups! No existen imagenes en la base de datos.");
    }
    $result = null;
    $db = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
});

// POST: Add new slider
$app->post('/api/slider/new', function(Request $request, Response $response){
   $title = $request->getParam('title');
   $description = $request->getParam('description');
   $image = $request->getParam('image');
   $user = $request->getParam('user');

  $sql = "INSERT INTO slider (title, description, image, create_date, update_date, user_id) VALUES
          (:title, :description, :image, CURRENT_TIMESTAMP(), CURRENT_TIMESTAMP(), :user)";
  try{
    $db = new db();
    $db = $db->conectDB();
    $result = $db->prepare($sql);
    $result->bindParam(':title', $title);
    $result->bindParam(':description', $description);
    $result->bindParam(':image', $image);
    $result->bindParam(':user', $user);
    $result->execute();
    echo json_encode("Nuevo Slider creado.");
    $result = null;
    $db = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
});

// PUT: Update sliders
$app->put('/api/slider/update/{id}', function(Request $request, Response $response){
   $id_slider = $request->getAttribute('id');
   $title = $request->getParam('title');
   $description = $request->getParam('description');
   $image = $request->getParam('image');
   $user = $request->getParam('user');

  $sql = "UPDATE slider SET
          title = :title,
          description = :description,
          image = :image,
          user_id = :user
        WHERE id = $id_slider";

  try{
    $db = new db();
    $db = $db->conectDB();
    $result = $db->prepare($sql);
    $result->bindParam(':title', $title);
    $result->bindParam(':description', $description);
    $result->bindParam(':image', $image);
    $result->bindParam(':user', $user);
    $result->execute();
    echo json_encode("Slider actualizado.");
    $result = null;
    $db = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
});
