<?php include VIEWS . '/includes/header.php' ?>

<div style="display: grid;place-content: center;grid-template-columns: 0.5fr;height: 100%;">
  <div class="form">
    <h2>Ошибка установки соединения с базой данных</h2>
    <div style="color: #bbb;margin-bottom: 30px;">
      <p>Не удалось установить соединение с базой данных. Возможно вы ошиблись при вводе данных в форму либо сервер недоступен.</p>
      <p>Пожалуйста, убедитесь, что все данные верны и попробуйте повторить попытку.</p>
    </div>
    <a class="btn btn__default" href="install?step=1">Попробовать ещё раз</a>
  </div>
</div>

<?php include VIEWS . '/includes/footer.php' ?>