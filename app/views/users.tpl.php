<?php
  ob_start();
?>


<?php
$inline_head = ob_get_clean();
ob_start();
?>




<?php require VIEWS . '/includes/header.php' ?>

<h2>Пользователи</h2>
<ul class="list">
  <?php foreach ($users as $user): ?>
    <li class="list__item">
      <?php if ($_SESSION['role'] === 'admin'): ?>
        <a href="users?action=delete&id=<?php echo urlencode($user['id']); ?>" data-action="delete" data-id="<?php echo urlencode($user['id']); ?>">Удалить</a>
      <?php endif; ?>
      <span class="list__username"><?php echo htmlspecialchars($user['username']); ?></span>
      <?php
        if ($user['role'] === 'admin') {
          echo 'Администратор';
        } elseif ($user['role'] === 'editor') {
          echo 'Редактор';
        } else {
          echo htmlspecialchars($user['role']);
        }
      ?>
    </li>
  <?php endforeach; ?>
</ul>


<button type="button" class="btn btn__default" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Добавить пользователя</button>

<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="background-color: transparent;">


      <form class="form needs-validation" method="POST" novalidate>
        <div class="form__group">
        <div class="mb-3">
    <label class="form__label form-label" for="username">Имя пользователя</label>
    <input class="form__input form-control" type="text" name="username" id="username" required>
    <div class="invalid-feedback">Пожалуйста, введите имя пользователя.</div>
    </div>
    <div class="mb-3">
    <label class="form__label form-label" for="password">Пароль</label>
    <input class="form__input form-control" type="password" name="password" id="password" required>
    <div class="invalid-feedback">Пожалуйста, введите пароль.</div>
    </div>
    <div class="mb-3">
    <select class="form__select form-select" name="role" id="role">
      <option value="admin">Администратор</option>
      <option value="editor">Редактор</option>
    </select>
    </div>
  </div>
  <button class="btn btn__default btn-primary" type="submit" name="submit">Создать</button>
  <button class="btn btn__delete" type="button" data-bs-dismiss="modal">Закрыть</button>
</form>


    </div>
  </div>
</div>


<?php require VIEWS . '/includes/footer.php' ?>