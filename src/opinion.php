<?php
use Slim\Http\Request;
use Slim\Http\Response;
use \Firebase\JWT\JWT;

// Routes Opinions

// GET: Get all opinions
$app->get('/api/allOpinion', function(Request $request, Response $response, array $args){
  $sql = "SELECT * FROM opinion ORDER BY create_date DESC";
  try{
    $res = $this->db->prepare($sql);
    $res->execute();
    $opinions = $res->fetchAll(PDO::FETCH_OBJ);

    return $this->response->withJson(['cod' => '200', 'allOpinions' => $opinions]);
    $res = null;

  }catch(PDOException $e){
    return $this->response->withStatus(503)->withHeader('Content-Type', 'application/json')
    ->withJson(['cod' => 503, 'message' => 'No es posible conectar con la base de datos.']);
  }
});

// GET: Get opinions by page
$app->get('/api/opinionsByPage/{page}', function(Request $request, Response $response, array $args){
  $page = $request->getAttribute('page');
  $resultPerPage = 5;
  $start = ($page - 1) * $resultPerPage;

  $sql = "SELECT COUNT(*) FROM opinion";
  $sqlPage = "SELECT * FROM opinion ORDER BY create_date DESC LIMIT $start, $resultPerPage";
  try{
    $res = $this->db->prepare($sql);
    $res->execute();
    $count = $res->fetchColumn();

    $res = $this->db->prepare($sqlPage);
    $res->execute();
    $opinions = $res->fetchAll(PDO::FETCH_OBJ);
    return $this->response->withJson(['cod' => '200', 'allOpinions' => $opinions, 'total' => $count, 'actual' => $page, 'totalPages' => ceil($count/$resultPerPage)]);
    $res = null;

  }catch(PDOException $e){
    return $this->response->withStatus(503)->withHeader('Content-Type', 'application/json')
    ->withJson(['cod' => 503, 'message' => 'No es posible conectar con la base de datos.']);
  }
});


// POST: Add new opinion
$app->post('/admin/api/opinions/new', function(Request $request, Response $response, array $args){
  $home = $request->getParam('home');
  $image = $request->getParam('image');
  $name = $request->getParam('name');
  $commentary = $request->getParam('commentary');
  $rating = $request->getParam('rating');

  $sql = "INSERT INTO opinion (
            home,
            image,
            name,
            commentary,
            rating)
          VALUES (
            :home,
            :image,
            :name,
            :commentary,
            :rating)";

  try{
    $res = $this->db->prepare($sql);
    $res->bindParam(':home', $home);
    $res->bindParam(':image', $image);
    $res->bindParam(':name', $name);
    $res->bindParam(':commentary', $commentary);
    $res->bindParam(':rating', $rating);
    $res->execute();
    return $this->response->withJson(['cod' => '200', 'message' => 'Nueva opinión creada.']);
    $res = null;
  }catch(PDOException $e){
    return $this->response->withStatus(503)->withHeader('Content-Type', 'application/json')
    ->withJson(['cod' => 503, 'message' => 'No es posible conectar con la base de datos.']);
  }
});

// PUT: Update opinion
$app->put('/admin/api/opinions/update/{id}', function(Request $request, Response $response, array $args){
   $id_opinion = $request->getAttribute('id');
   $home = $request->getParam('home');
   $image = $request->getParam('image');
   $name = $request->getParam('name');
   $commentary = $request->getParam('commentary');
   $rating = $request->getParam('rating');

  $sql= "UPDATE opinion SET
          home = :home,
          image = :image,
          name = :name,
          commentary = :commentary,
          rating = :rating
          WHERE id = $id_opinion";

  try{
    $res = $this->db->prepare($sql);
    $res->bindParam(':home', $home);
    $res->bindParam(':image', $image);
    $res->bindParam(':name', $name);
    $res->bindParam(':commentary', $commentary);
    $res->bindParam(':rating', $rating);
    $res->execute();
    return $this->response->withJson(['cod' => '200', 'message' => 'Se ha actualizado la opinión.']);
    $res = null;
  }catch(PDOException $e){
    return $this->response->withStatus(503)->withHeader('Content-Type', 'application/json')
    ->withJson(['cod' => 503, 'message' => 'No es posible conectar con la base de datos.']);
  }
});

// DELETE: Delete opinion by ID
$app->delete('/admin/api/opinions/delete/{id}', function(Request $request, Response $response, array $args){
  $id_opinion = $request->getAttribute('id');
  $sql = "DELETE FROM opinion WHERE id = $id_opinion";
  try{
    $res = $this->db->prepare($sql);
    $res->bindParam(':id', $id_opinion);
    $res->execute();
    return $this->response->withJson(['cod' => '200', 'message' => 'Opinión eliminada.']);

    $res = null;
  }catch(PDOException $e){
    return $this->response->withStatus(503)->withHeader('Content-Type', 'application/json')
    ->withJson(['cod' => 503, 'message' => 'No es posible conectar con la base de datos.']);
  }
});

?>
