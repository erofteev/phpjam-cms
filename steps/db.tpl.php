<?php include VIEWS . '/includes/header.php' ?>

<div style="display: grid;place-content: center;grid-template-columns: 0.5fr;height: 100%;">
<form class="form" action="install?step=2" method="post">
  <h2>Настройка базы данных</h2>
  <div style="color: #bbb;margin-bottom: 30px;">
    <p>Введите здесь информацию о подключении к базе данных. Если вы в ней не уверены, свяжитесь с поддержкой вашего хостинга.</p>
  </div>
  <label class="form__label" for="db_host">Хост:</label>
  <input class="form__input" placeholder="localhost" type="text" name="db_host" id="db_host" value="<?php echo htmlspecialchars($_SESSION['db_host']); ?>" required>
  <label class="form__label" for="db_user">Имя пользователя:</label>
  <input class="form__input" placeholder="username" type="text" name="db_user" id="db_user" value="<?php echo htmlspecialchars($_SESSION['db_user']); ?>" required>
  <label class="form__label" for="db_pass">Пароль:</label>
  <input class="form__input" placeholder="password" type="password" name="db_pass" id="db_pass" value="<?php echo htmlspecialchars($_SESSION['db_pass']); ?>">
  <label class="form__label" for="db_name">Имя базы данных:</label>
  <input class="form__input" placeholder="cms" type="text" name="db_name" id="db_name" value="<?php echo htmlspecialchars($_SESSION['db_name']); ?>" required>

  <button class="btn btn__default" type="submit">Проверить подключение</button>
</form>
</div>


<?php include VIEWS . '/includes/footer.php' ?>