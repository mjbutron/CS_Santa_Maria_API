<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;

// GET: Get all slider
$app->get('/api/allSlider', function(Request $request, Response $response){
  $sql = "SELECT * FROM slider";
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->query($sql);
    if ($resultado->rowCount() > 0){
      $sliders = $resultado->fetchAll(PDO::FETCH_OBJ);
      echo json_encode($sliders);
    }else {
      echo json_encode("Ups! No existen imagenes en la base de datos.");
    }
    $resultado = null;
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

  $sql = "INSERT INTO slider (title, description, image) VALUES
          (:title, :description, :image)";
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->prepare($sql);
    $resultado->bindParam(':title', $title);
    $resultado->bindParam(':description', $description);
    $resultado->bindParam(':image', $image);
    $resultado->execute();
    echo json_encode("Nuevo Slider creado.");
    $resultado = null;
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

  $sql = "UPDATE slider SET
          title = :title,
          description = :description,
          image = :image
        WHERE id = $id_slider";

  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->prepare($sql);
    $resultado->bindParam(':title', $title);
    $resultado->bindParam(':description', $description);
    $resultado->bindParam(':image', $image);
    $resultado->execute();
    echo json_encode("Slider actualizado.");
    $resultado = null;
    $db = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
});
