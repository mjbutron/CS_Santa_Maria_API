<?php
use Slim\Http\Request;
use Slim\Http\Response;
use \Firebase\JWT\JWT;

// Routes Users

// POST: Get all users
$app->get('/api/allUsers', function(Request $request, Response $response, array $args){
  $sql = "SELECT * FROM user ORDER BY surname ASC";
  try{
    $res = $this->db->prepare($sql);
    $res->execute();
    $users = $res->fetchAll(PDO::FETCH_OBJ);

    if($users) {
      return $this->response->withJson($users);
    }else{
      return $this->response->withJson(['cod' => '404', 'message' => 'Datos no disponibles.']);
    }

    $res = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
});

// GET: Get users by page
$app->get('/api/usersByPage/{page}', function(Request $request, Response $response, array $args){
  $page = $request->getAttribute('page');
  $resultPerPage = 5;
  $start = ($page - 1) * $resultPerPage;

  $sql = "SELECT COUNT(*) FROM user";
  $sqlPage = "SELECT u.id, u.active, u.name, u.surname, u.email, u.telephone,
                     u.address, u.city, u.province, u.zipcode, u.aboutme, u.password,
                     u.user_fcbk, u.user_ytube, u.user_insta, u.image, u.last_login,
                     u.create_date, u.update_date, r.rol_name
              FROM user u, rol r
              WHERE u.rol_id = r.id
              ORDER BY surname ASC LIMIT $start, $resultPerPage";
  try{
    $res = $this->db->prepare($sql);
    $res->execute();
    $count = $res->fetchColumn();

    if($count) {
      $res = $this->db->prepare($sqlPage);
      $res->execute();
      $users = $res->fetchAll(PDO::FETCH_OBJ);
      return $this->response->withJson(['allUsers' => $users, 'total' => $count, 'actual' => $page, 'totalPages' => ceil($count/$resultPerPage)]);
    }else{
      return $this->response->withJson(['cod' => '404', 'message' => 'Datos no disponibles.']);
    }

    $res = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
});

// DELETE: Delete user by ID
$app->delete('/admin/api/users/delete/{id}', function(Request $request, Response $response, array $args){
  $id_user = $request->getAttribute('id');
  $sql = "DELETE FROM user WHERE id = $id_user";
  try{
    $res = $this->db->prepare($sql);
    $res->bindParam(':id', $id_user);
    $res->execute();
    return $this->response->withJson(['cod' => '200', 'message' => 'Usuario eliminado.']);

    $res = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
});

// PUT: Update user profile
// $app->put('/admin/api/userProfile/update/{id}', function(Request $request, Response $response, array $args){
//    $id = $request->getAttribute('id');
//    $name = $request->getParam('name');
//    $surname = $request->getParam('surname');
//    $telephone = $request->getParam('telephone');
//    $address = $request->getParam('address');
//    $city = $request->getParam('city');
//    $province = $request->getParam('province');
//    $zipcode = $request->getParam('zipcode');
//    $aboutme = $request->getParam('aboutme');
//    $userFcbk = $request->getParam('userFcbk');
//    $userYtube = $request->getParam('userYtube');
//    $userInsta = $request->getParam('userInsta');
//    $image = $request->getParam('image');
//
//   $sql = "UPDATE user SET
//           name = :name,
//           surname = :surname,
//           telephone = :telephone,
//           address = :address,
//           city = :city,
//           province = :province,
//           zipcode = :zipcode,
//           aboutme = :aboutme,
//           user_fcbk = :userFcbk,
//           user_ytube = :userYtube,
//           user_insta = :userInsta,
//           image = :image
//         WHERE id = $id";
//
//   try{
//     $res = $this->db->prepare($sql);
//     $res->bindParam(':name', $name);
//     $res->bindParam(':surname', $surname);
//     $res->bindParam(':telephone', $telephone);
//     $res->bindParam(':address', $address);
//     $res->bindParam(':city', $city);
//     $res->bindParam(':province', $province);
//     $res->bindParam(':zipcode', $zipcode);
//     $res->bindParam(':aboutme', $aboutme);
//     $res->bindParam(':userFcbk', $userFcbk);
//     $res->bindParam(':userYtube', $userYtube);
//     $res->bindParam(':userInsta', $userInsta);
//     $res->bindParam(':image', $image);
//     $res->execute();
//     return $this->response->withJson(['cod' => '200', 'message' => 'Información actualizada.']);
//     $res = null;
//   }catch(PDOException $e){
//     echo '{"error" : {"text":'.$e->getMessage().'}';
//   }
// });

// POST: Check current password
// $app->post('/admin/api/checkPassword', function(Request $request, Response $response, array $args){
//   $input = $request->getParsedBody();
//   $sql = "SELECT id, password FROM user WHERE id= :id";
//   try{
//     $input = $request->getParsedBody();
//     $res = $this->db->prepare($sql);
//     $res->bindParam("id", $input['id']);
//     $res->execute();
//     $user = $res->fetchObject();
//
//     // verify password.
//     if (!password_verify($input['password'],$user->password)) {
//       return $this->response->withJson(['check' => 0]);
//     }
//
//     return $this->response->withJson(['check' => 1]);
//
//     $res = null;
//   }catch(PDOException $e){
//     echo '{"error" : {"text":'.$e->getMessage().'}';
//   }
// });

// PUT: Update password
// $app->put('/admin/api/userProfile/updatePass/{id}', function(Request $request, Response $response, array $args){
//    $id = $request->getAttribute('id');
//    $password = $request->getParam('password');
//    $password = password_hash($password, PASSWORD_DEFAULT);
//
//   $sql = "UPDATE user SET
//           password = :password
//         WHERE id = $id";
//
//   try{
//     $res = $this->db->prepare($sql);
//     $res->bindParam(':password', $password);
//
//     $res->execute();
//     return $this->response->withJson(['cod' => '200', 'message' => 'Información actualizada.']);
//     $res = null;
//   }catch(PDOException $e){
//     echo '{"error" : {"text":'.$e->getMessage().'}';
//   }
// });
