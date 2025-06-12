<?php session_start(); ?>
<!DOCTYPE html>
<html lang="ru">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Test task</title>
  <link rel="stylesheet" href="/styles.css">
</head>

<body>
  <div class="login">
    <form class="login__form" action="/login" method="post">
      <label class="login__label">Телефон или почта
        <input class="login__input" type="text" name="login" required >
        <span class="login-error"><?php echo $_SESSION["loginError"] ?? '&nbsp;' ?></span>
      </label>
      <label class="login__label">Пароль
        <input class="login__input" type="password" name="password" required>
        <span class="password-error"><?php echo $_SESSION['passwordError'] ?? '&nbsp;' ?></span>
      </label>
      <div class="login__actions">
        <button class="login__button" type="submit">Войти</button>
        <a class="login__link" href="/register">Регистрация</a>
      </div>
    </form>
    <?php unset($_SESSION['loginError']);
    unset($_SESSION['passwordError']);
    ?>
  </div>
</body>

</html>
