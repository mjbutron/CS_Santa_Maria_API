<?php
use Slim\Http\Request;
use Slim\Http\Response;
use \Firebase\JWT\JWT;

// Routes Notification

// POST: find notification
$app->post('/admin/api/findNotifications', function(Request $request, Response $response, array $args){
  $sql = "SELECT COUNT(id)
  FROM notification
  WHERE user_id=:user_id AND notified = 0";
  try{
    $input = $request->getParsedBody();
    $res = $this->db->prepare($sql);
    $res->bindParam("user_id", $input['user_id']);
    $res->execute();
    $count = $res->fetchColumn();

    return $this->response->withJson(['cod' => '200', 'foundNotifications' => $count]);
    $res = null;

  }catch(PDOException $e){
    return $this->response->withStatus(503)->withHeader('Content-Type', 'application/json')
    ->withJson(['cod' => 503, 'message' => 'No es posible conectar con la base de datos.']);
  }
});

// POST: Get all notifications by page
$app->post('/admin/api/notificationsByPage/{page}', function(Request $request, Response $response, array $args){
  $page = $request->getAttribute('page');
  $resultPerPage = 5;
  $start = ($page - 1) * $resultPerPage;

  $sql = "SELECT COUNT(*) FROM notification WHERE user_id=:user_id";
  $sqlPage = "SELECT * FROM notification
  WHERE user_id=:user_id
  ORDER BY datetime_notification DESC LIMIT $start, $resultPerPage";
  try{
    $input = $request->getParsedBody();
    $res = $this->db->prepare($sql);
    $res->bindParam("user_id", $input['user_id']);
    $res->execute();
    $count = $res->fetchColumn();

    $res = $this->db->prepare($sqlPage);
    $res->bindParam("user_id", $input['user_id']);
    $res->execute();
    $notifications = $res->fetchAll(PDO::FETCH_OBJ);
    return $this->response->withJson(['cod' => '200', 'allNotifications' => $notifications, 'total' => $count, 'actual' => $page, 'totalPages' => ceil($count/$resultPerPage)]);
    $res = null;

  }catch(PDOException $e){
    return $this->response->withStatus(503)->withHeader('Content-Type', 'application/json')
    ->withJson(['cod' => 503, 'message' => 'No es posible conectar con la base de datos.']);
  }
});

// PUT: Update notified
$app->put('/admin/api/allNotified', function(Request $request, Response $response, array $args){
  $user_id = $request->getParam('user_id');
  $sql= "UPDATE notification SET
          notified = 1
          WHERE user_id = $user_id";
  try{
    $res = $this->db->prepare($sql);
    $res->execute();
    return $this->response->withJson(['cod' => '200', 'message' => 'Se han notificado.']);
    $res = null;

  }catch(PDOException $e){
    return $this->response->withStatus(503)->withHeader('Content-Type', 'application/json')
    ->withJson(['cod' => 503, 'message' => 'No es posible conectar con la base de datos.']);
  }
});


?>
