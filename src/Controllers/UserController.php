<?php
require_once dirname(__DIR__, 2) . '/init.php';
require_once BASE_DIR . '/Core/Controller.php';
require_once BASE_DIR . '/src/Models/UserModel.php';

class UserController extends Controller
{
  protected $db;

  public function __construct()
  {
    $this->db = new UserModel();
  }

  public function index()
  {
    require_once BASE_DIR . '/src/Views/user/index.php';
  }

  public function login()
  {
    session_start();

    $login = $_POST['login'] ?? '';
    $password = trim($_POST['password'] ?? '');

    if (empty($password)) {
      $_SESSION["passwordError"] = "Пароль не может быть пустым";
      exit;
    }

    if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
      $sql = "SELECT * FROM `users` WHERE email = :login";
    } elseif (preg_match('/^\+?[0-9]{7,15}$/', $login)) {
      $sql = "SELECT * FROM `users` WHERE phone = :login";
    } else {
      $_SESSION["loginError"] = "Неверный формат логина";
      header("Location: /");
      exit;
    }

    $stmt = $this->db->prepare($sql);
    $stmt->execute(['login' => $login]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
      if (password_verify($password, $user['password'])) {
        $_SESSION['name'] = $user['name'];
        $_SESSION['id'] = $user['id'];
        header("Location: /cabinet");
        exit;
      } else {
        $_SESSION["passwordError"] = "Неверный пароль";
        header("Location: /");
      }
    } else {
      $_SESSION["loginError"] = "Пользователь не найден";
      header("Location: /");
    }
  }

  public function register()
  {
    require_once BASE_DIR . '/src/Views/user/register.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $name = htmlspecialchars(trim($_POST["name"]));
      $email = htmlspecialchars(trim($_POST['email']));
      $phone = htmlspecialchars(trim($_POST['phone']));
      $password = htmlspecialchars(trim($_POST["password"]));
      $repassword = htmlspecialchars(trim($_POST["repassword"]));

      unset(
        $_SESSION['nameError'],
        $_SESSION['emailError'],
        $_SESSION['phoneError'],
        $_SESSION['passwordError'],
        $_SESSION['repasswordError'],
        $_SESSION['success']
      );

      $hasErrors = false;

      if (empty($name)) {
        $_SESSION['nameError'] = "Имя не может быть пустым";
        $hasErrors = true;
      }

      if (empty($email)) {
        $_SESSION['emailError'] = "Почта не может быть пустой";
        $hasErrors = true;
      } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['emailError'] = "Некорректный формат почты";
        $hasErrors = true;
      }

      if (empty($phone)) {
        $_SESSION['phoneError'] = "Телефон не может быть пустым";
        $hasErrors = true;
      } elseif (!preg_match('/^\d+$/', $phone)) {
        $_SESSION['phoneError'] = "Телефон должен содержать только цифры";
        $hasErrors = true;
      }

      if (empty($password)) {
        $_SESSION['passwordError'] = "Пароль не может быть пустым";
        $hasErrors = true;
      }

      if (empty($repassword)) {
        $_SESSION['repasswordError'] = "Повторный пароль не может быть пустым";
        $hasErrors = true;
      } elseif ($password !== $repassword) {
        $_SESSION['repasswordError'] = "Пароли не совпадают";
        $hasErrors = true;
      }

      if (!$hasErrors) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email OR phone = :phone OR name = :name");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':name', $name);
        $stmt->execute();
        $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingUser) {
          if ($existingUser['email'] === $email) {
            $_SESSION['emailError'] = "Пользователь с такой почтой уже существует";
          }
          if ($existingUser['phone'] === $phone) {
            $_SESSION['phoneError'] = "Пользователь с таким телефоном уже существует";
          }
          if ($existingUser['name'] === $name) {
            $_SESSION['nameError'] = "Имя пользователя уже занято";
          }
          $hasErrors = true;
        }
      }

      if (!$hasErrors) {
        try {
          $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
          $stmt = $this->db->prepare("INSERT INTO users (name, phone, email, password)
                                            VALUES (:name, :phone, :email, :password)");
          $stmt->bindParam(':name', $name);
          $stmt->bindParam(':phone', $phone);
          $stmt->bindParam(':email', $email);
          $stmt->bindParam(':password', $hashedPassword);
          $stmt->execute();

          $_SESSION['success'] = "Вы успешно зарегистрировались $name";
          header("Location: /register");
          exit;
        } catch (PDOException $e) {
          $_SESSION['dbError'] = "Ошибка базы данных: " . $e->getMessage();
        }
      } else {
        header("Location: /register");
        exit;
      }
    }
  }

  public function cabinet()
  {
    session_start();
    $id = $_SESSION['id'];
    $sql = "SELECT * FROM users WHERE id = :id";
    $stmt = $this->db->prepare($sql);
    $stmt->execute(['id' => $id]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    require_once BASE_DIR . '/src/Views/user/cabinet.php';
  }

  public function update()
  {
    session_start();

    if (!isset($_SESSION['id'])) {
      header('Location: /');
      exit;
    }
    $userId = $_SESSION['id'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $name = trim($_POST['login'] ?? '');
      $phone = trim($_POST['phone'] ?? '');
      $email = trim($_POST['email'] ?? '');
      $password = $_POST['password'] ?? '';

      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['loginError'] = 'Некорректный email';
        header('Location: /update');
        exit;
      }

      $params = [
        ':name' => $name,
        ':phone' => $phone,
        ':email' => $email,
        ':id' => $userId,
      ];

      if ($password !== '') {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET name = :name, phone = :phone, email = :email, password = :password WHERE id = :id";
        $params[':password'] = $passwordHash;
      } else {
        $sql = "UPDATE users SET name = :name, phone = :phone, email = :email WHERE id = :id";
      }

      $stmt = $this->db->prepare($sql);
      $stmt->execute($params);

      $_SESSION['name'] = $name;
      $_SESSION['phone'] = $phone;
      $_SESSION['email'] = $email;

      header('Location: /cabinet');
      exit;
    }

    $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->execute([':id' => $userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    require_once BASE_DIR . '/src/Views/user/update.php';
  }

  public function exit()
  {
    $_SESSION = [];
    session_destroy();
    header('Location: /');
    exit();
  }
}
