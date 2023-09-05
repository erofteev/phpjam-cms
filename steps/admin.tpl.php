<?php include VIEWS . '/includes/header.php' ?>

<div style="display: grid;place-content: center;grid-template-columns: 0.5fr;height: 100%;">
<form class="form" action="install?step=4" method="post">
  <h2>Создание администратора</h2>
  <label class="form__label" for="username">Логин:</label>
  <input class="form__input" placeholder="username" type="text" name="username" id="username" required>
  <label class="form__label" for="password">Пароль:</label>
  <input class="form__input" placeholder="password" type="password" name="password" id="password" required>
  <label class="form__label" for="email">Email:</label>
  <input class="form__input" placeholder="email" type="email" name="email" id="email" required>

  <button class="btn btn__default" type="submit">Завершить установку</button>
</form>
</div>

<?php include VIEWS . '/includes/footer.php' ?>