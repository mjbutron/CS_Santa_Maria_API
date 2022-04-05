<?php
// Configuration Data Base

// DB instance
try {
  $container['db'] = function ($c) {
      $db = $c['settings']['db'];
      $pdo = new PDO("mysql:host=" . $db['host'] . ";dbname=" . $db['name'] . ";charset=utf8", $db['user'], $db['password']);
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
      return $pdo;
  };
} catch (PDOException $e){
    return $e->getMessage();
}

?>
