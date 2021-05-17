<?php
use Slim\Http\Request;
use Slim\Http\Response;
use \Firebase\JWT\JWT;

// Routes Slider

// GET: Get all slider
$app->get('/api/allSlider', function(Request $request, Response $response, array $args){
  $sql = "SELECT * FROM slider ORDER BY order_slider ASC";
  try{
    $res = $this->db->prepare($sql);
    $res->execute();
    $sliders = $res->fetchAll(PDO::FETCH_OBJ);

    return $this->response->withJson(['cod' => '200', 'allSliders' => $sliders]);
    $res = null;

  }catch(PDOException $e){
    return $this->response->withStatus(503)->withHeader('Content-Type', 'application/json')
    ->withJson(['cod' => 503, 'message' => 'No es posible conectar con la base de datos.']);
  }
});

// POST: Add new slider
$app->post('/admin/api/slider/new', function(Request $request, Response $response, array $args){
   $title = $request->getParam('title');
   $description = $request->getParam('description');
   $image = $request->getParam('image');
   $order_slider = $request->getParam('order_slider');
   $user = $request->getParam('user');

  $sql = "INSERT INTO slider (title, description, image, order_slider, create_date, update_date, user_id) VALUES
          (:title, :description, :image, :order_slider, CURRENT_TIMESTAMP(), CURRENT_TIMESTAMP(), :user)";
  try{
    $res = $this->db->prepare($sql);
    $res->bindParam(':title', $title);
    $res->bindParam(':description', $description);
    $res->bindParam(':image', $image);
    $res->bindParam(':order_slider', $order_slider);
    $res->bindParam(':user', $user);
    $res->execute();
    return $this->response->withJson(['cod' => '200', 'message' => 'Nuevo Slider creado.']);
    $res = null;
  }catch(PDOException $e){
    return $this->response->withStatus(503)->withHeader('Content-Type', 'application/json')
    ->withJson(['cod' => 503, 'message' => 'No es posible conectar con la base de datos.']);
  }
});

// PUT: Update sliders
$app->put('/admin/api/slider/update/{id}', function(Request $request, Response $response, array $args){
   $id_slider = $request->getAttribute('id');
   $title = $request->getParam('title');
   $description = $request->getParam('description');
   $image = $request->getParam('image');
   $color_text = $request->getParam('color_text');
   $order_slider = $request->getParam('order_slider');
   $user = $request->getParam('user_id');

  $sql = "UPDATE slider SET
          title = :title,
          description = :description,
          image = :image,
          color_text = :color_text,
          order_slider = :order_slider,
          user_id = :user
        WHERE id = $id_slider";

  try{
    $res = $this->db->prepare($sql);
    $res->bindParam(':title', $title);
    $res->bindParam(':description', $description);
    $res->bindParam(':image', $image);
    $res->bindParam(':color_text', $color_text);
    $res->bindParam(':order_slider', $order_slider);
    $res->bindParam(':user', $user);
    $res->execute();
    return $this->response->withJson(['cod' => '200', 'message' => 'Se ha editado la cabecera.']);
    $res = null;
  }catch(PDOException $e){
    return $this->response->withStatus(503)->withHeader('Content-Type', 'application/json')
    ->withJson(['cod' => 503, 'message' => 'No es posible conectar con la base de datos.']);
  }
});
