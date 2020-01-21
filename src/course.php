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

    if($courses) {
      return $this->response->withJson($courses);
    }else{
      return $this->response->withJson(['cod' => '404', 'message' => 'Datos no disponibles.']);
    }

    $res = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
});

// GET: Get courses by page
$app->get('/api/coursesByPage/{page}', function(Request $request, Response $response, array $args){
  $page = $request->getAttribute('page');
  $resultPerPage = 2;
  $start = ($page - 1) * $resultPerPage;

  $sql = "SELECT COUNT(*) FROM course";
  $sqlPage = "SELECT * FROM course ORDER BY session_date ASC LIMIT $start, $resultPerPage";
  try{
    $res = $this->db->prepare($sql);
    $res->execute();
    $count = $res->fetchColumn();

    if($count) {
      $res = $this->db->prepare($sqlPage);
      $res->execute();
      $courses = $res->fetchAll(PDO::FETCH_OBJ);
      return $this->response->withJson(['allCourses' => $courses, 'total' => $count, 'actual' => $page, 'totalPages' => ceil($count/$resultPerPage)]);
    }else{
      return $this->response->withJson(['cod' => '404', 'message' => 'Datos no disponibles.']);
    }

    $res = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
});

//
// // POST: Add new service
// $app->post('/admin/api/service/new', function(Request $request, Response $response, array $args){
//    $title = $request->getParam('title');
//    $image_home = $request->getParam('image_home');
//    $subtitle_home = $request->getParam('subtitle_home');
//    $description_home = $request->getParam('description_home');
//    $home = $request->getParam('home');
//    $image = $request->getParam('image');
//    $subtitle = $request->getParam('subtitle');
//    $description = $request->getParam('description');
//    $user = $request->getParam('user');
//
//   $sql = "INSERT INTO service (title, image_home, subtitle_home, description_home, home, image, subtitle, description, create_date, update_date, user_id)
//   VALUES (:title, :image_home, :subtitle_home, :description_home, :home, :image, :subtitle, :description,  CURRENT_TIMESTAMP(),  CURRENT_TIMESTAMP(), :user)";
//
//   try{
//     $res = $this->db->prepare($sql);
//     $res->bindParam(':title', $title);
//     $res->bindParam(':image_home', $image_home);
//     $res->bindParam(':subtitle_home', $subtitle_home);
//     $res->bindParam(':description_home', $description_home);
//     $res->bindParam(':home', $home);
//     $res->bindParam(':image', $image);
//     $res->bindParam(':subtitle', $subtitle);
//     $res->bindParam(':description', $description);
//     $res->bindParam(':user', $user);
//     $res->execute();
//     return $this->response->withJson(['cod' => '200', 'message' => 'Nuevo servicio creado.']);
//     $res = null;
//   }catch(PDOException $e){
//     echo '{"error" : {"text":'.$e->getMessage().'}';
//   }
// });
//
// // PUT: Update service
// $app->put('/admin/api/service/update/{id}', function(Request $request, Response $response, array $args){
//    $id_service = $request->getAttribute('id');
//    $title = $request->getParam('title');
//    $image_home = $request->getParam('image_home');
//    $subtitle_home = $request->getParam('subtitle_home');
//    $description_home = $request->getParam('description_home');
//    $home = $request->getParam('home');
//    $image = $request->getParam('image');
//    $subtitle = $request->getParam('subtitle');
//    $description = $request->getParam('description');
//    $user = $request->getParam('user');
//
//   $sql= "UPDATE service SET
//           title = :title,
//           image_home = :image_home,
//           subtitle_home = :subtitle_home,
//           description_home = :description_home,
//           home = :home,
//           image = :image,
//           subtitle = :subtitle,
//           description = :description,
//           user_id = :user
//           WHERE id = $id_service";
//
//   try{
//     $res = $this->db->prepare($sql);
//     $res->bindParam(':title', $title);
//     $res->bindParam(':image_home', $image_home);
//     $res->bindParam(':subtitle_home', $subtitle_home);
//     $res->bindParam(':description_home', $description_home);
//     $res->bindParam(':home', $home);
//     $res->bindParam(':image', $image);
//     $res->bindParam(':subtitle', $subtitle);
//     $res->bindParam(':description', $description);
//     $res->bindParam(':user', $user);
//     $res->execute();
//     return $this->response->withJson(['cod' => '200', 'message' => 'Servicio actualizado.']);
//     $res = null;
//   }catch(PDOException $e){
//     echo '{"error" : {"text":'.$e->getMessage().'}';
//   }
// });

?>
