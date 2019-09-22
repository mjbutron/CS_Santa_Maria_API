<?php
  class db{
    private $dbHost ='localhost';
    private $dbUser = 'admin';
    private $dbPass = '12345678';
    private $dbName = 'cssmdb';

    // Connection
    public function conectDB(){
      $mysqlConnect = "mysql:host=$this->dbHost;dbname=$this->dbName;charset=utf8";
      $dbConnection = new PDO($mysqlConnect, $this->dbUser, $this->dbPass);
      $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      return $dbConnection;
    }
  }
