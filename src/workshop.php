<?php
use Slim\Http\Request;
use Slim\Http\Response;
use \Firebase\JWT\JWT;

// Routes Workshops

// GET: Get all workshops
$app->get('/api/allWorkshops', function(Request $request, Response $response, array $args){
  $sql = "SELECT * FROM workshop ORDER BY session_date ASC";
  try{
    $res = $this->db->prepare($sql);
    $res->execute();
    $workshops = $res->fetchAll(PDO::FETCH_OBJ);

    return $this->response->withJson(['cod' => '200', 'allWorkshops' => $workshops]);
    $res = null;

  }catch(PDOException $e){
    return $this->response->withStatus(503)->withHeader('Content-Type', 'application/json')
    ->withJson(['cod' => 503, 'message' => 'No es posible conectar con la base de datos.']);
  }
});

// GET: Get workshops by page
$app->get('/api/workshopsByPage/{page}', function(Request $request, Response $response, array $args){
  $page = $request->getAttribute('page');
  $resultPerPage = 5;
  $start = ($page - 1) * $resultPerPage;

  $sql = "SELECT COUNT(*) FROM workshop";
  $sqlPage = "SELECT * FROM workshop ORDER BY session_date ASC LIMIT $start, $resultPerPage";
  try{
    $res = $this->db->prepare($sql);
    $res->execute();
    $count = $res->fetchColumn();

    $res = $this->db->prepare($sqlPage);
    $res->execute();
    $workshops = $res->fetchAll(PDO::FETCH_OBJ);
    return $this->response->withJson(['cod' => '200', 'allWorkshops' => $workshops, 'total' => $count, 'actual' => $page, 'totalPages' => ceil($count/$resultPerPage)]);
    $res = null;

  }catch(PDOException $e){
    return $this->response->withStatus(503)->withHeader('Content-Type', 'application/json')
    ->withJson(['cod' => 503, 'message' => 'No es posible conectar con la base de datos.']);
  }
});

// POST: Add new workshops
$app->post('/admin/api/workshops/new', function(Request $request, Response $response, array $args){
  $home = $request->getParam('home');
  $title = $request->getParam('title');
  $description_home = $request->getParam('description_home');
  $description = $request->getParam('description');
  $image = $request->getParam('image');
  $subtitle = $request->getParam('subtitle');
  $price = $request->getParam('price');
  $address = $request->getParam('address');
  $session_date = $request->getParam('session_date');
  $session_start = $request->getParam('session_start');
  $session_end = $request->getParam('session_end');
  $hours = $request->getParam('hours');
  $places = $request->getParam('places');
  $free_places = $request->getParam('free_places');
  $new_workshop = $request->getParam('new_workshop');
  $impart = $request->getParam('impart');
  $user_id = $request->getParam('user_id');

  $sql = "INSERT INTO workshop (
            home,
            title,
            description_home,
            description,
            image,
            subtitle,
            price,
            address,
            session_date,
            session_start,
            session_end,
            hours,
            places,
            free_places,
            new_workshop,
            impart,
            user_id)
          VALUES (
            :home,
            :title,
            :description_home,
            :description,
            :image,
            :subtitle,
            :price,
            :address,
            :session_date,
            :session_start,
            :session_end,
            :hours,
            :places,
            :free_places,
            :new_workshop,
            :impart,
            :user_id)";

  try{
    $res = $this->db->prepare($sql);
    $res->bindParam(':home', $home);
    $res->bindParam(':title', $title);
    $res->bindParam(':description_home', $description_home);
    $res->bindParam(':description', $description);
    $res->bindParam(':image', $image);
    $res->bindParam(':subtitle', $subtitle);
    $res->bindParam(':price', $price);
    $res->bindParam(':address', $address);
    $res->bindParam(':session_date', $session_date);
    $res->bindParam(':session_start', $session_start);
    $res->bindParam(':session_end', $session_end);
    $res->bindParam(':hours', $hours);
    $res->bindParam(':places', $places);
    $res->bindParam(':free_places', $free_places);
    $res->bindParam(':new_workshop', $new_workshop);
    $res->bindParam(':impart', $impart);
    $res->bindParam(':user_id', $user_id);
    $res->execute();
    return $this->response->withJson(['cod' => '200', 'message' => 'Nuevo taller creado.']);
    $res = null;
  }catch(PDOException $e){
    return $this->response->withStatus(503)->withHeader('Content-Type', 'application/json')
    ->withJson(['cod' => 503, 'message' => 'No es posible conectar con la base de datos.']);
  }
});

// PUT: Update workshops
$app->put('/admin/api/workshops/update/{id}', function(Request $request, Response $response, array $args){
   $id_workshop = $request->getAttribute('id');
   $active = $request->getParam('active');
   $home = $request->getParam('home');
   $title = $request->getParam('title');
   $description_home = $request->getParam('description_home');
   $description = $request->getParam('description');
   $image = $request->getParam('image');
   $subtitle = $request->getParam('subtitle');
   $price = $request->getParam('price');
   $address = $request->getParam('address');
   $session_date = $request->getParam('session_date');
   $session_start = $request->getParam('session_start');
   $session_end = $request->getParam('session_end');
   $hours = $request->getParam('hours');
   $places = $request->getParam('places');
   $free_places = $request->getParam('free_places');
   $new_workshop = $request->getParam('new_workshop');
   $impart = $request->getParam('impart');
   $user_id = $request->getParam('user_id');

  $sql= "UPDATE workshop SET
          active = :active,
          home = :home,
          title = :title,
          description_home = :description_home,
          description = :description,
          image = :image,
          subtitle = :subtitle,
          price = :price,
          address = :address,
          session_date = :session_date,
          session_start = :session_start,
          session_end = :session_end,
          hours = :hours,
          places = :places,
          free_places = :free_places,
          new_workshop = :new_workshop,
          impart = :impart,
          user_id = :user_id
          WHERE id = $id_workshop";

  try{
    $res = $this->db->prepare($sql);
    $res->bindParam(':active', $active);
    $res->bindParam(':home', $home);
    $res->bindParam(':title', $title);
    $res->bindParam(':description_home', $description_home);
    $res->bindParam(':description', $description);
    $res->bindParam(':image', $image);
    $res->bindParam(':subtitle', $subtitle);
    $res->bindParam(':price', $price);
    $res->bindParam(':address', $address);
    $res->bindParam(':session_date', $session_date);
    $res->bindParam(':session_start', $session_start);
    $res->bindParam(':session_end', $session_end);
    $res->bindParam(':hours', $hours);
    $res->bindParam(':places', $places);
    $res->bindParam(':free_places', $free_places);
    $res->bindParam(':new_workshop', $new_workshop);
    $res->bindParam(':impart', $impart);
    $res->bindParam(':user_id', $user_id);
    $res->execute();
    return $this->response->withJson(['cod' => '200', 'message' => 'Se ha actualizado el taller.']);
    $res = null;
  }catch(PDOException $e){
    return $this->response->withStatus(503)->withHeader('Content-Type', 'application/json')
    ->withJson(['cod' => 503, 'message' => 'No es posible conectar con la base de datos.']);
  }
});

// DELETE: Delete workshop by ID
$app->delete('/admin/api/workshops/delete/{id}', function(Request $request, Response $response, array $args){
  $id_workshop = $request->getAttribute('id');
  $sql = "DELETE FROM workshop WHERE id = $id_workshop";
  try{
    $res = $this->db->prepare($sql);
    $res->bindParam(':id', $id_workshop);
    $res->execute();
    return $this->response->withJson(['cod' => '200', 'message' => 'Taller eliminado.']);

    $res = null;
  }catch(PDOException $e){
    return $this->response->withStatus(503)->withHeader('Content-Type', 'application/json')
    ->withJson(['cod' => 503, 'message' => 'No es posible conectar con la base de datos.']);
  }
});

?>
