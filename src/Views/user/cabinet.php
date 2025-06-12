<?php session_start();
if (empty($_SESSION["id"])) {
  header("Location: /");
  exit;
}
?>
<!DOCTYPE html>
<html lang="ru">

<head>
  <meta charset="UTF-8" />
  <title>Кабинет</title>
  <link rel="stylesheet" href="/page.css">
</head>

<body>
  <div class="page">
    <div class="page__info">
      <h1>Добро пожаловать, <?php echo htmlspecialchars($_SESSION['name']); ?></h1>
      <p>Имя: <?php echo $user['name']; ?></p>
      <p>Телефон: <?php echo $user['phone']; ?></p>
      <p>Почта: <?php echo $user['email']; ?></p>
      <a href="/exit">Выйти из аккаунта</a>
    </div>

    <div class="page__form">
      <h2>Изменить данные</h2>
      <form action="/update" method="post">
        <label class="page__form-label">Имя
          <input class="page__form-input" type="text" name="login" required value="<?php echo $user['name']; ?>">
        </label>
        <label class="page__form-label">Телефон
          <input class="page__form-input" type="text" name="phone" required value="<?php echo $user['phone']; ?>">
        </label>
        <label class="page__form-label">Почта
          <input class="page__form-input" type="text" name="email" required value="<?php echo $user['email']; ?>">
        </label>
        <label class="page__form-label">Изменить пароль
          <input class="page__form-input" type="text" name="password">
        </label>
        <button class="page__form-button" type="submit">Обновить данные</button>
        <a class="page__form-link" href="/">Вернуться назад</a>
      </form>
    </div>
  </div>

</body>

</html>
