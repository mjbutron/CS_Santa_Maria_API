<?php
use Slim\Http\Request;
use Slim\Http\Response;
use \Firebase\JWT\JWT;

// Routes AboutUs

// GET: Get all aboutus
$app->get('/api/allAboutUs', function(Request $request, Response $response, array $args){
  $sql = "SELECT * FROM aboutus ORDER BY create_date ASC";
  try{
    $res = $this->db->prepare($sql);
    $res->execute();
    $aboutus = $res->fetchAll(PDO::FETCH_OBJ);

    return $this->response->withJson(['cod' => '200', 'allAboutUs' => $aboutus]);
    $res = null;

  }catch(PDOException $e){
    return $this->response->withStatus(503)->withHeader('Content-Type', 'application/json')
    ->withJson(['cod' => 503, 'message' => 'No es posible conectar con la base de datos.']);
  }
});

// GET: Get aboutus by page
$app->get('/api/aboutUsByPage/{page}', function(Request $request, Response $response, array $args){
  $page = $request->getAttribute('page');
  $resultPerPage = 5;
  $start = ($page - 1) * $resultPerPage;

  $sql = "SELECT COUNT(*) FROM aboutus";
  $sqlPage = "SELECT * FROM aboutus ORDER BY create_date ASC LIMIT $start, $resultPerPage";
  try{
    $res = $this->db->prepare($sql);
    $res->execute();
    $count = $res->fetchColumn();

    $res = $this->db->prepare($sqlPage);
    $res->execute();
    $aboutus = $res->fetchAll(PDO::FETCH_OBJ);
    return $this->response->withJson(['cod' => '200', 'allAboutUs' => $aboutus, 'total' => $count, 'actual' => $page, 'totalPages' => ceil($count/$resultPerPage)]);
    $res = null;

  }catch(PDOException $e){
    return $this->response->withStatus(503)->withHeader('Content-Type', 'application/json')
    ->withJson(['cod' => 503, 'message' => 'No es posible conectar con la base de datos.']);
  }
});


// POST: Add new aboutus
$app->post('/admin/api/aboutus/new', function(Request $request, Response $response, array $args){
  $name = $request->getParam('name');
  $surname1 = $request->getParam('surname1');
  $surname2 = $request->getParam('surname2');
  $image = $request->getParam('image');
  $position = $request->getParam('position');
  $description = $request->getParam('description');
  $academic_degree = $request->getParam('academic_degree');
  $user_fcbk = $request->getParam('user_fcbk');
  $user_ytube = $request->getParam('user_ytube');
  $user_insta = $request->getParam('user_insta');

  $sql = "INSERT INTO aboutus (
            name,
            surname1,
            surname2,
            image,
            position,
            description,
            academic_degree,
            user_fcbk,
            user_ytube,
            user_insta)
          VALUES (
            :name,
            :surname1,
            :surname2,
            :image,
            :position,
            :description,
            :academic_degree,
            :user_fcbk,
            :user_ytube,
            :user_insta)";

  try{
    $res = $this->db->prepare($sql);
    $res->bindParam(':name', $name);
    $res->bindParam(':surname1', $surname1);
    $res->bindParam(':surname2', $surname2);
    $res->bindParam(':image', $image);
    $res->bindParam(':position', $position);
    $res->bindParam(':description', $description);
    $res->bindParam(':academic_degree', $academic_degree);
    $res->bindParam(':user_fcbk', $user_fcbk);
    $res->bindParam(':user_ytube', $user_ytube);
    $res->bindParam(':user_insta', $user_insta);
    $res->execute();
    return $this->response->withJson(['cod' => '200', 'message' => 'Nueva entrada creada.']);
    $res = null;
  }catch(PDOException $e){
    return $this->response->withStatus(503)->withHeader('Content-Type', 'application/json')
    ->withJson(['cod' => 503, 'message' => 'No es posible conectar con la base de datos.']);
  }
});

// PUT: Update aboutus
$app->put('/admin/api/aboutus/update/{id}', function(Request $request, Response $response, array $args){
   $id_aboutus = $request->getAttribute('id');
   $name = $request->getParam('name');
   $surname1 = $request->getParam('surname1');
   $surname2 = $request->getParam('surname2');
   $image = $request->getParam('image');
   $position = $request->getParam('position');
   $description = $request->getParam('description');
   $academic_degree = $request->getParam('academic_degree');
   $user_fcbk = $request->getParam('user_fcbk');
   $user_ytube = $request->getParam('user_ytube');
   $user_insta = $request->getParam('user_insta');

  $sql= "UPDATE aboutus SET
          name = :name,
          surname1 = :surname1,
          surname2 = :surname2,
          image = :image,
          position = :position,
          description = :description,
          academic_degree = :academic_degree,
          user_fcbk = :user_fcbk,
          user_ytube = :user_ytube,
          user_insta = :user_insta
          WHERE id = $id_aboutus";

  try{
    $res = $this->db->prepare($sql);
    $res->bindParam(':name', $name);
    $res->bindParam(':surname1', $surname1);
    $res->bindParam(':surname2', $surname2);
    $res->bindParam(':image', $image);
    $res->bindParam(':position', $position);
    $res->bindParam(':description', $description);
    $res->bindParam(':academic_degree', $academic_degree);
    $res->bindParam(':user_fcbk', $user_fcbk);
    $res->bindParam(':user_ytube', $user_ytube);
    $res->bindParam(':user_insta', $user_insta);
    $res->execute();
    return $this->response->withJson(['cod' => '200', 'message' => 'Se ha actualizado la entrada.']);
    $res = null;
  }catch(PDOException $e){
    return $this->response->withStatus(503)->withHeader('Content-Type', 'application/json')
    ->withJson(['cod' => 503, 'message' => 'No es posible conectar con la base de datos.']);
  }
});

// DELETE: Delete aboutus by ID
$app->delete('/admin/api/aboutus/delete/{id}', function(Request $request, Response $response, array $args){
  $id_aboutus = $request->getAttribute('id');
  $sql = "DELETE FROM aboutus WHERE id = $id_aboutus";
  try{
    $res = $this->db->prepare($sql);
    $res->bindParam(':id', $id_aboutus);
    $res->execute();
    return $this->response->withJson(['cod' => '200', 'message' => 'Entrada eliminada.']);

    $res = null;
  }catch(PDOException $e){
    return $this->response->withStatus(503)->withHeader('Content-Type', 'application/json')
    ->withJson(['cod' => 503, 'message' => 'No es posible conectar con la base de datos.']);
  }
});

?>
