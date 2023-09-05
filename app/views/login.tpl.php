<?php require VIEWS . '/includes/header.php' ?>



  <div class="login">
  <form class="form" action="" method="post">
    <label class="form__label" for="username">Логин</label>
    <input class="form__input login__input" type="text" name="username" id="username" required>
    <label class="form__label" for="password">Пароль</label>
    <input class="form__input login__input" type="password" name="password" id="password" required>
    <div class="login__action">
      <label><input type="checkbox" name="remember" value="1" checked>Запомнить меня</label>
      <button class="btn btn__default" type="submit">Войти</button>
    </div>
  </form>
  <div id="error-message"></div>
  </div>


<?php require VIEWS . '/includes/footer.php' ?>