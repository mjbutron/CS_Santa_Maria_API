<?php
use Slim\Http\Request;
use Slim\Http\Response;
use \Firebase\JWT\JWT;

// Routes Courses

// GET: Get all courses
$app->get('/api/courses', function(Request $request, Response $response, array $args){
  $sql = "SELECT * FROM course ORDER BY session_date ASC";
  try{
    $res = $this->db->prepare($sql);
    $res->execute();
    $courses = $res->fetchAll(PDO::FETCH_OBJ);

    return $this->response->withJson(['cod' => '200', 'allCourses' => $courses]);
    $res = null;

  }catch(PDOException $e){
    return $this->response->withStatus(503)->withHeader('Content-Type', 'application/json')
    ->withJson(['cod' => 503, 'message' => 'No es posible conectar con la base de datos.']);
  }
});

// GET: Get course by Id
$app->get('/api/course/{id}', function(Request $request, Response $response, array $args){
  $id_course = $request->getAttribute('id');
  $sql = "SELECT * FROM course WHERE id = $id_course";
  try{
    $res = $this->db->prepare($sql);
    $res->execute();
    $course = $res->fetchAll(PDO::FETCH_OBJ);

    return $this->response->withJson(['cod' => '200', 'course' => $course]);
    $res = null;

  }catch(PDOException $e){
    return $this->response->withStatus(503)->withHeader('Content-Type', 'application/json')
    ->withJson(['cod' => 503, 'message' => 'No es posible conectar con la base de datos.']);
  }
});

// GET: Get all active courses
$app->get('/api/activeCourses', function(Request $request, Response $response, array $args){
  $sql = "SELECT * FROM course WHERE active = 1 ORDER BY new_course DESC, session_date ASC";
  try{
    $res = $this->db->prepare($sql);
    $res->execute();
    $courses = $res->fetchAll(PDO::FETCH_OBJ);

    return $this->response->withJson(['cod' => '200', 'allCourses' => $courses]);
    $res = null;

  }catch(PDOException $e){
    return $this->response->withStatus(503)->withHeader('Content-Type', 'application/json')
    ->withJson(['cod' => 503, 'message' => 'No es posible conectar con la base de datos.']);
  }
});

// GET: Get courses by page
$app->get('/api/coursesByPage/{page}', function(Request $request, Response $response, array $args){
  $page = $request->getAttribute('page');
  $resultPerPage = 5;
  $start = ($page - 1) * $resultPerPage;

  $sql = "SELECT COUNT(*) FROM course";
  $sqlPage = "SELECT * FROM course ORDER BY session_date ASC LIMIT $start, $resultPerPage";
  try{
    $res = $this->db->prepare($sql);
    $res->execute();
    $count = $res->fetchColumn();

    $res = $this->db->prepare($sqlPage);
    $res->execute();
    $courses = $res->fetchAll(PDO::FETCH_OBJ);
    return $this->response->withJson(['cod' => '200', 'allCourses' => $courses, 'total' => $count, 'actual' => $page, 'totalPages' => ceil($count/$resultPerPage)]);
    $res = null;

  }catch(PDOException $e){
    return $this->response->withStatus(503)->withHeader('Content-Type', 'application/json')
    ->withJson(['cod' => 503, 'message' => 'No es posible conectar con la base de datos.']);
  }
});

// POST: Add new course
$app->post('/admin/api/courses/new', function(Request $request, Response $response, array $args){
  $title = $request->getParam('title');
  $description = $request->getParam('description');
  $image = $request->getParam('image');
  $new_course = $request->getParam('new_course');
  $offer = $request->getParam('offer');
  $address = $request->getParam('address');
  $session_date = $request->getParam('session_date');
  $session_start = $request->getParam('session_start');
  $session_end = $request->getParam('session_end');
  $sessions = $request->getParam('sessions');
  $hours = $request->getParam('hours');
  $impart = $request->getParam('impart');
  $places = $request->getParam('places');
  $free_places = $request->getParam('free_places');
  $price = $request->getParam('price');
  $user_id = $request->getParam('user_id');

  $sql = "INSERT INTO course (
            title,
            description,
            image,
            new_course,
            offer,
            address,
            session_date,
            session_start,
            session_end,
            sessions,
            hours,
            impart,
            places,
            free_places,
            price,
            user_id)
          VALUES (
            :title,
            :description,
            :image,
            :new_course,
            :offer,
            :address,
            :session_date,
            :session_start,
            :session_end,
            :sessions,
            :hours,
            :impart,
            :places,
            :free_places,
            :price,
            :user_id)";

  try{
    $res = $this->db->prepare($sql);
    $res->bindParam(':title', $title);
    $res->bindParam(':description', $description);
    $res->bindParam(':image', $image);
    $res->bindParam(':new_course', $new_course);
    $res->bindParam(':offer', $offer);
    $res->bindParam(':address', $address);
    $res->bindParam(':session_date', $session_date);
    $res->bindParam(':session_start', $session_start);
    $res->bindParam(':session_end', $session_end);
    $res->bindParam(':sessions', $sessions);
    $res->bindParam(':hours', $hours);
    $res->bindParam(':impart', $impart);
    $res->bindParam(':places', $places);
    $res->bindParam(':free_places', $free_places);
    $res->bindParam(':price', $price);
    $res->bindParam(':user_id', $user_id);
    $res->execute();
    return $this->response->withJson(['cod' => '200', 'message' => 'Nuevo curso creado.']);
    $res = null;
  }catch(PDOException $e){
    return $this->response->withStatus(503)->withHeader('Content-Type', 'application/json')
    ->withJson(['cod' => 503, 'message' => 'No es posible conectar con la base de datos.']);
  }
});

// PUT: Update course
$app->put('/admin/api/courses/update/{id}', function(Request $request, Response $response, array $args){
   $id_course = $request->getAttribute('id');
   $active = $request->getParam('active');
   $title = $request->getParam('title');
   $description = $request->getParam('description');
   $image = $request->getParam('image');
   $new_course = $request->getParam('new_course');
   $offer = $request->getParam('offer');
   $address = $request->getParam('address');
   $session_date = $request->getParam('session_date');
   $session_start = $request->getParam('session_start');
   $session_end = $request->getParam('session_end');
   $sessions = $request->getParam('sessions');
   $hours = $request->getParam('hours');
   $impart = $request->getParam('impart');
   $places = $request->getParam('places');
   $free_places = $request->getParam('free_places');
   $price = $request->getParam('price');
   $user_id = $request->getParam('user_id');

  $sql= "UPDATE course SET
          active = :active,
          title = :title,
          description = :description,
          image = :image,
          new_course = :new_course,
          offer = :offer,
          address = :address,
          session_date = :session_date,
          session_start = :session_start,
          session_end = :session_end,
          sessions = :sessions,
          hours = :hours,
          impart = :impart,
          places = :places,
          free_places = :free_places,
          price = :price,
          user_id = :user_id
          WHERE id = $id_course";

  try{
    $res = $this->db->prepare($sql);
    $res->bindParam(':active', $active);
    $res->bindParam(':title', $title);
    $res->bindParam(':description', $description);
    $res->bindParam(':image', $image);
    $res->bindParam(':new_course', $new_course);
    $res->bindParam(':offer', $offer);
    $res->bindParam(':address', $address);
    $res->bindParam(':session_date', $session_date);
    $res->bindParam(':session_start', $session_start);
    $res->bindParam(':session_end', $session_end);
    $res->bindParam(':sessions', $sessions);
    $res->bindParam(':hours', $hours);
    $res->bindParam(':impart', $impart);
    $res->bindParam(':places', $places);
    $res->bindParam(':free_places', $free_places);
    $res->bindParam(':price', $price);
    $res->bindParam(':user_id', $user_id);
    $res->execute();
    return $this->response->withJson(['cod' => '200', 'message' => 'Se ha actualizado el curso.']);
    $res = null;
  }catch(PDOException $e){
    return $this->response->withStatus(503)->withHeader('Content-Type', 'application/json')
    ->withJson(['cod' => 503, 'message' => 'No es posible conectar con la base de datos.']);
  }
});

// DELETE: Delete course by ID
$app->delete('/admin/api/courses/delete/{id}', function(Request $request, Response $response, array $args){
  $id_course = $request->getAttribute('id');
  $sql = "DELETE FROM course WHERE id = $id_course";
  try{
    $res = $this->db->prepare($sql);
    $res->bindParam(':id', $id_course);
    $res->execute();
    return $this->response->withJson(['cod' => '200', 'message' => 'Curso eliminado.']);

    $res = null;
  }catch(PDOException $e){
    return $this->response->withStatus(503)->withHeader('Content-Type', 'application/json')
    ->withJson(['cod' => 503, 'message' => 'No es posible conectar con la base de datos.']);
  }
});

?>
