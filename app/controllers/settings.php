<?php

$title = 'Настройки CMS';

// Проверка авторизации пользователя
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: /");
    exit;
}

$nonce = $_SESSION['nonce'];

// Подключение файла конфигурации
require_once CONFIG . '/config.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

require_once CORE . '/functions.php';

// Проверка отправки формы вебхуков
if (isset($_POST['webhook_url'], $_POST['webhook_event'], $_POST['webhook_token'], $_POST['nonce']) && $_POST['nonce'] === $nonce) {
  $webhookUrl = $_POST['webhook_url'];
  $webhookEvent = $_POST['webhook_event'];
  $token = $_POST['webhook_token'];
  
  // Сохранение информации о новом вебхуке в базе данных
  $stmt = $db->prepare("INSERT INTO webhooks (url, event, token) VALUES (?, ?, ?)");
  $stmt->bind_param("sss", $webhookUrl, $webhookEvent, $token);
  $stmt->execute();

  // Перенаправление
  header("HTTP/1.1 303 See Other");
  header("Location: settings");
  exit;
}


// Выборка информации о вебхуках из базы данных
$webhooks = [];
$result = $db->query("SELECT id, url, event FROM webhooks");
while ($row = $result->fetch_assoc()) {
  $webhooks[] = $row;
}

// Проверка запроса на удаление вебхука
if (isset($_GET['action'], $_GET['id']) && $_GET['action'] === 'delete') {
  // Получение идентификатора вебхука из URL
  $id = $_GET['id'];

  // Удаление вебхука из базы данных
  $stmt = $db->prepare("DELETE FROM webhooks WHERE id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();

  // Перенаправление на страницу настроек
  header("Location: settings");
  exit;
}


$eventDescriptions = [
  'all_events' => 'Все события',
  'create_post' => 'Создание новой записи',
  'update_post' => 'Обновление записи',
  'delete_post' => 'Удаление записи'
];


require VIEWS . '/settings.tpl.php';
