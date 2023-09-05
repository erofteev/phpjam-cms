<?php require VIEWS . '/includes/header.php' ?>

<h2>Вебхуки</h2>
<p>При создании вебхуков на определённые события, возможна небольшая задержка при выполнении этих событий, поскольку перед выполнением события, происходит отправка запроса по адресу вебхука.</p>

<?php if ($result->num_rows > 0): ?>
	<ul class="list">
	<?php foreach ($webhooks as $webhook): ?>
		<li class="list__item">
			<a href="settings?action=delete&id=<?= urlencode($webhook['id']) ?>">Удалить</a>
			<strong>URL:</strong> <?= htmlspecialchars($webhook['url']) ?>
			<strong>Событие:</strong> <?= htmlspecialchars($eventDescriptions[$webhook['event']] ?? $webhook['event']) ?>
		</li>
	<?php endforeach; ?>
</ul>
	<?php else: ?>
		<ul class="list">
			<li class="list__empty">Вебхуков нет</li>
		</ul>
	<?php endif; ?>


<form class="form needs-validation" action="settings" method="POST" novalidate>
<div class="form__group">
  <div class="mb-3">
    <label class="form__label form-label" for="webhook-url">URL вебхука</label>
    <input class="form__input form-control" type="url" id="webhook-url" name="webhook_url" placeholder="https://api.github.com/repos/USERNAME/REPO/dispatches" pattern="https?://.+" required>
    <div class="invalid-feedback">Пожалуйста, введите правильную ссылку.</div>
  </div>
  <div class="mb-3">
    <label class="form__label form-label" for="webhook-token">Токен доступа</label>
    <input class="form__input form-control" type="text" id="webhook-token" name="webhook_token" placeholder="Введите ваш токен доступа">
  </div>
  <div class="mb-3">
    <label class="form__label form-label" for="webhook-event">Событие</label>
    <select class="form__select form-select" id="webhook-event" name="webhook_event">
      <option value="all_events">Все события</option>
      <option value="create_post">Создание новой записи</option>
      <option value="update_post">Обновление записи</option>
      <option value="delete_post">Удаление записи</option>
    </select>
  </div>
	</div>
	<input type="hidden" name="nonce" value="<?= $nonce ?>">
	<button class="btn btn__default btn-primary" type="submit">Создать вебхук</button>
</form>




<?php require VIEWS . '/includes/footer.php' ?>