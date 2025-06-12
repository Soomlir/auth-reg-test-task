<?php session_start(); ?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Регистрация нового пользователя</title>
  <link rel="stylesheet" href="/styles.css">
</head>
<body>
  <div class="login">
    <?php if (!isset($_SESSION['success']) || !$_SESSION['success']): ?>
      <form class="login__form" action="/register" method="post">
        <h1>Регистрация</h1>
        <label class="login__label">Имя
          <input class="login__input" type="text" name="name">
          <span class="login-error"><?php echo $_SESSION["nameError"] ?? '&nbsp;';
                                    unset($_SESSION['nameError']); ?></span>
        </label>
        <label class="login__label">Телефон
          <input class="login__input" type="text" name="phone">
          <span class="login-error"><?php echo $_SESSION["phoneError"] ?? '&nbsp;';
                                    unset($_SESSION['phoneError']); ?></span>
        </label>
         <label class="login__label">Почта
          <input class="login__input" type="text" name="email">
          <span class="login-error"><?php echo $_SESSION["emailError"] ?? '&nbsp;';
                                    unset($_SESSION['emailError']); ?></span>
        </label>
        <label class="login__label">Пароль
          <input class="login__input" type="password" name="password">
          <span class="password-error"><?php echo $_SESSION['passwordError'] ?? '&nbsp;';
                                        unset($_SESSION['passwordError']); ?></span>
        </label>
        <label class="login__label">Повторите пароль
          <input class="login__input" type="password" name="repassword">
          <span class="repassword-error"><?php echo $_SESSION['repasswordError'] ?? '&nbsp;';
                                          unset($_SESSION['repasswordError']); ?></span>
        </label>
        <div class="login__actions">
          <button class="login__button" type="submit">Зарегистрироваться</button>
          <a class="login__link" href="/">Назад</a>
        </div>
      </form>
    <?php else: ?>
      <p><?php echo $_SESSION['success'];
          unset($_SESSION['success']); ?></p>
    <?php endif; ?>
  </div>
  </div>
</body>
</html>
