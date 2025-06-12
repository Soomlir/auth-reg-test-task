<?php
require_once __DIR__ . '/Database.php';
class Model extends Database {
  protected $db;
  public function __construct() {
    $this->db = new Database();
  }
}
