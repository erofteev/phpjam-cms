<?php require VIEWS . '/includes/header.php' ?>

<h2>Дополнительная информация</h2>

<form class="form needs-validation" method="POST" novalidate>
  <div class="mb-3">
    <label for="url" class="form__label form-label">Ссылка</label>
    <input type="text" class="form__input form-control" name="url" id="url" placeholder="https://site.ru" pattern="https?://.+" value="<?= htmlspecialchars($site['url']) ?>">
    <div class="invalid-feedback">Пожалуйста, введите правильную ссылку или оставьте поле пустым.</div>
  </div>
  <div class="mb-3">
    <label for="name" class="form__label form-label">Название</label>
    <input type="text" class="form__input form-control" name="name" id="name" placeholder="Название сайта" value="<?= htmlspecialchars($site['name']) ?>">
    <div class="invalid-feedback">Пожалуйста, введите название сайта или оставьте поле пустым.</div>
  </div>
  <div class="mb-3">
    <label for="description" class="form__label form-label">Описание</label>
    <textarea class="form__input form-control" name="description" id="description" placeholder="Описание вашего сайта"><?= htmlspecialchars($site['description']) ?></textarea>
    <div class="invalid-feedback">Пожалуйста, введите описание вашего сайта или оставьте поле пустым.</div>
  </div>
  <div class="form__short mb-3">
    <label for="themeColor" class="form__label form-label">Цвет темы</label>
    <select class="form__select form-select" name="themeColor" id="themeColor">
      <option value="default" <?= $site['themeColor'] === 'default' ? 'selected' : '' ?>>default</option>
    </select>
    <div class="invalid-feedback">Пожалуйста, выберите цвет темы или оставьте поле пустым.</div>
  </div>
  <div class="form__short mb-3">
    <label for="lang" class="form__label form-label">Язык</label>
    <select class="form__select form-select" name="lang" id="lang">
      <option value="ru" <?=$site['lang']==='ru' ? 'selected' : '' ?>>ru</option>
      <option value="en" <?=$site['lang']==='en' ? 'selected' : '' ?>>en</option>
    </select>
    <div class="invalid-feedback">Пожалуйста, выберите язык или оставьте поле пустым.</div>
  </div>
  <div class="mb-3">
    <label for="tel" class="form__label form-label">Телефон</label>
    <input type="text" class="form__input form-control" name="tel" id="tel" placeholder="+0(000) 00-00-000" pattern="\+\d{1}\(\d{3}\) \d{2}-\d{2}-\d{3}" value="<?= htmlspecialchars($site['tel']) ?>">
    <div class="invalid-feedback">Пожалуйста, введите правильный телефон или оставьте поле пустым.</div>
  </div>
  <div class="mb-3">
    <label for="email" class="form__label form-label">Email</label>
    <input type="email" class="form__input form-control" name="email" id="email" placeholder="your@mail.ru" value="<?= htmlspecialchars($site['email']) ?>">
    <div class="invalid-feedback">Пожалуйста, введите правильный email или оставьте поле пустым.</div>
  </div>
  <div class="mb-3">
    <label for="telegram" class="form__label form-label">Telegram</label>
    <input type="text" class="form__input form-control" name="telegram" id="telegram" placeholder="@username" pattern="@[\w\d_]+" value="<?= htmlspecialchars($site['telegram']) ?>">
    <div class="invalid-feedback">Пожалуйста, введите правильное имя пользователя Telegram или оставьте поле пустым.</div>
  </div>
  <div class="mb-3">
    <label for="pages" class="form__label form-label">Страницы</label>
    <input type="text" class="form__input form-control" name="pages" id="pages" placeholder="Укажите страницы через запятую, либо оставьте поле пустым" value="<?= isset($site['pages']) ? $site['pages'] : '' ?>">
  </div>

  <button class="btn btn__default btn-primary" type="submit">Сохранить</button>
</form>


<?php require VIEWS . '/includes/footer.php' ?>