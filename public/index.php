<?php
require_once __DIR__ . '/../init.php';
require_once __DIR__ . '/../src/Controllers/UserController.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

switch ($uri) {
  case '/':
    $controller = new UserController();
    $controller->index();
    break;
  case '/login':
    if ($method === 'POST') {
      $controller = new UserController();
      $controller->login();
    } else {
      http_response_code(405);
      echo "Method Not Allowed";
    }
    break;
  case '/register':
    $controller = new UserController();
    $controller->register();
    break;
  case '/cabinet':
    $controller = new UserController();
    $controller->cabinet();
    break;
  case '/update':
    $controller = new UserController();
    $controller->update();
    break;
  case '/exit':
    $controller = new UserController();
    $controller->exit();
    break;
  default:
    http_response_code(404);
    echo "404 Not Found";
    break;
}
