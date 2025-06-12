<?php
class Database
{
  protected $db;
  protected $host = "MySQL-8.0";
  protected $pass = "";
  protected $database = "auth-test";

  public function __construct()
  {
    try {
      $this->db = new PDO("mysql:host=$this->host;dbname=$this->database", "root", "");
    } catch (PDOException $e) {
      echo $e;
    }
  }

  public function prepare($query)
  {
    return $this->db->prepare($query);
  }

  public function query($sql, $params = [])
  {
    $stmt = $this->db->prepare($sql);
    $stmt->execute($params);
    return $stmt;
  }
}
