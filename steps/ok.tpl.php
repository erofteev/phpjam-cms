<?php include VIEWS . '/includes/header.php' ?>

<div style="display: grid;place-content: center;grid-template-columns: 0.5fr;height: 100%;">
  <div class="form">
    <h2>Соединение установлено</h2>
    <div style="color: #bbb;margin-bottom: 30px;">
      <p>Соединение с базой данных было успешно установлено. Вы можете продолжить дальнейшую установку, для этого нажмите кнопку ниже.</p>
    </div>
    <a class="btn btn__default" href="install?step=3">Продолжить установку</a>
  </div>
</div>

<?php include VIEWS . '/includes/footer.php' ?>