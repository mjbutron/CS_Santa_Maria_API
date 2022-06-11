<?php
use Slim\Http\Request;
use Slim\Http\Response;
use \Firebase\JWT\JWT;

// Routes Users

// GET: Get all users
$app->get('/api/allUsers', function(Request $request, Response $response, array $args){
  $sql = "SELECT * FROM user ORDER BY surname ASC";
  try{
    $res = $this->db->prepare($sql);
    $res->execute();
    $users = $res->fetchAll(PDO::FETCH_OBJ);

    return $this->response->withJson(['cod' => '200', 'allUsers' => $users]);
    $res = null;

  }catch(PDOException $e){
    return $this->response->withStatus(503)->withHeader('Content-Type', 'application/json')
    ->withJson(['cod' => 503, 'message' => 'No es posible conectar con la base de datos.']);
  }
});

// GET: Get users by page
$app->get('/api/usersByPage/{page}', function(Request $request, Response $response, array $args){
  $page = $request->getAttribute('page');
  $resultPerPage = 5;
  $start = ($page - 1) * $resultPerPage;

  $sql = "SELECT COUNT(*) FROM user";
  $sqlPage = "SELECT u.id, u.active, u.name, u.surname, u.email, u.telephone,
                     u.address, u.city, u.province, u.zipcode, u.password,
                     u.user_fcbk, u.user_ytube, u.user_insta, u.image, u.last_login,
                     u.create_date, u.update_date, r.id as rol_id, r.rol_name
              FROM user u, rol r
              WHERE u.rol_id = r.id
              ORDER BY surname ASC LIMIT $start, $resultPerPage";
  try{
    $res = $this->db->prepare($sql);
    $res->execute();
    $count = $res->fetchColumn();

    $res = $this->db->prepare($sqlPage);
    $res->execute();
    $users = $res->fetchAll(PDO::FETCH_OBJ);
    return $this->response->withJson(['cod' => '200', 'allUsers' => $users, 'total' => $count, 'actual' => $page, 'totalPages' => ceil($count/$resultPerPage)]);
    $res = null;

  }catch(PDOException $e){
    return $this->response->withStatus(503)->withHeader('Content-Type', 'application/json')
    ->withJson(['cod' => 503, 'message' => 'No es posible conectar con la base de datos.']);
  }
});

// POST: Add new user
$app->post('/admin/api/users/new', function(Request $request, Response $response, array $args){
  $name = $request->getParam('name');
  $surname = $request->getParam('surname');
  $email = $request->getParam('email');
  $telephone = $request->getParam('telephone');
  $address = $request->getParam('address');
  $city = $request->getParam('city');
  $province = $request->getParam('province');
  $zipcode = $request->getParam('zipcode');
  $rol_id = $request->getParam('rol_id');

  $settings = $this->get('settings'); // get settings array.
  $password =   $settings['default']['pass'];
  $password = password_hash($password, PASSWORD_DEFAULT);

  $sql = "INSERT INTO user (
            name,
            surname,
            email,
            telephone,
            address,
            city,
            province,
            zipcode,
            password,
            rol_id)
          VALUES (
            :name,
            :surname,
            :email,
            :telephone,
            :address,
            :city,
            :province,
            :zipcode,
            :password,
            :rol_id)";

  try{
    $res = $this->db->prepare($sql);
    $res->bindParam(':name', $name);
    $res->bindParam(':surname', $surname);
    $res->bindParam(':email', $email);
    $res->bindParam(':telephone', $telephone);
    $res->bindParam(':address', $address);
    $res->bindParam(':city', $city);
    $res->bindParam(':province', $province);
    $res->bindParam(':zipcode', $zipcode);
    $res->bindParam(':password', $password);
    $res->bindParam(':rol_id', $rol_id);
    $res->execute();
    return $this->response->withJson(['cod' => '200', 'message' => 'Nuevo usuario creado.']);
    $res = null;
  }catch(PDOException $e){
    return $this->response->withStatus(503)->withHeader('Content-Type', 'application/json')
    ->withJson(['cod' => 503, 'message' => 'No es posible conectar con la base de datos.']);
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
    return $this->response->withStatus(503)->withHeader('Content-Type', 'application/json')
    ->withJson(['cod' => 503, 'message' => 'No es posible conectar con la base de datos.']);
  }
});

// PUT: Update user profile
$app->put('/admin/api/users/update/{id}', function(Request $request, Response $response, array $args){
   $id = $request->getAttribute('id');
   $active = $request->getParam('active');
   $name = $request->getParam('name');
   $surname = $request->getParam('surname');
   $email = $request->getParam('email');
   $telephone = $request->getParam('telephone');
   $address = $request->getParam('address');
   $city = $request->getParam('city');
   $province = $request->getParam('province');
   $zipcode = $request->getParam('zipcode');
   $rol_id = $request->getParam('rol_id');

  $sql = "UPDATE user SET
          active = :active,
          name = :name,
          surname = :surname,
          email = :email,
          telephone = :telephone,
          address = :address,
          city = :city,
          province = :province,
          zipcode = :zipcode,
          rol_id = :rol_id
        WHERE id = $id";

  try{
    $res = $this->db->prepare($sql);
    $res->bindParam(':active', $active);
    $res->bindParam(':name', $name);
    $res->bindParam(':surname', $surname);
    $res->bindParam(':email', $email);
    $res->bindParam(':telephone', $telephone);
    $res->bindParam(':address', $address);
    $res->bindParam(':city', $city);
    $res->bindParam(':province', $province);
    $res->bindParam(':zipcode', $zipcode);
    $res->bindParam(':rol_id', $rol_id);
    $res->execute();
    return $this->response->withJson(['cod' => '200', 'message' => 'Usuario actualizado.']);
    $res = null;
  }catch(PDOException $e){
    return $this->response->withStatus(503)->withHeader('Content-Type', 'application/json')
    ->withJson(['cod' => 503, 'message' => 'No es posible conectar con la base de datos.']);
  }
});

// POST: Validate email
$app->post('/admin/api/validateEmail', function(Request $request, Response $response, array $args){
  $sql = "SELECT * FROM user WHERE email= :email";
  try{
    $input = $request->getParsedBody();
    $res = $this->db->prepare($sql);
    $res->bindParam("email", $input['email']);
    $res->execute();
    $user = $res->fetchObject();

    if($user) {
      return $this->response->withJson(['cod' => '200', 'exists' => true]);
    }
    else{
      return $this->response->withJson(['cod' => '200', 'exists' => false]);
    }

    $res = null;

  }catch(PDOException $e){
    return $this->response->withStatus(503)->withHeader('Content-Type', 'application/json')
    ->withJson(['cod' => 503, 'message' => 'No es posible conectar con la base de datos.']);
  }
});
