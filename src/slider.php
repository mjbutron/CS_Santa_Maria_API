<?php
use Slim\Http\Request;
use Slim\Http\Response;
use \Firebase\JWT\JWT;

// Routes Slider

// GET: Get all slider
$app->get('/api/allSlider', function(Request $request, Response $response, array $args){
  $sql = "SELECT * FROM slider";
  try{
    $res = $this->db->prepare($sql);
    $res->execute();
    $sliders = $res->fetchAll(PDO::FETCH_OBJ);

    if($sliders) {
      return $this->response->withJson($sliders);
    }else{
      return $this->response->withJson(['cod' => '404', 'message' => 'Datos no disponibles.']);
    }

    $res = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
});

// POST: Add new slider
$app->post('/admin/api/slider/new', function(Request $request, Response $response, array $args){
   $title = $request->getParam('title');
   $description = $request->getParam('description');
   $image = $request->getParam('image');
   $user = $request->getParam('user');

  $sql = "INSERT INTO slider (title, description, image, create_date, update_date, user_id) VALUES
          (:title, :description, :image, CURRENT_TIMESTAMP(), CURRENT_TIMESTAMP(), :user)";
  try{
    $res = $this->db->prepare($sql);
    $res->bindParam(':title', $title);
    $res->bindParam(':description', $description);
    $res->bindParam(':image', $image);
    $res->bindParam(':user', $user);
    $res->execute();
    return $this->response->withJson(['cod' => '200', 'message' => 'Nuevo Slider creado.']);
    $res = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
});

// PUT: Update sliders
$app->put('/admin/api/slider/update/{id}', function(Request $request, Response $response, array $args){
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
    $res = $this->db->prepare($sql);
    $res->bindParam(':title', $title);
    $res->bindParam(':description', $description);
    $res->bindParam(':image', $image);
    $res->bindParam(':user', $user);
    $res->execute();
    return $this->response->withJson(['cod' => '200', 'message' => 'Slider actualizado.']);
    $res = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
});
