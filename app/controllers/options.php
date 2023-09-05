<?php

$title = 'Опции';

// Проверка авторизации пользователя
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /");
    exit;
}

$nonce = $_SESSION['nonce'];


// Подключение файла конфигурации
require_once CONFIG . '/config.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

require_once CORE . '/functions.php';

// Выборка данных из таблицы site
$site = $db->query("SELECT * FROM site LIMIT 1")->fetch_assoc();

// Проверка отправки формы настроек
if (isset($_POST['url'], $_POST['name'], $_POST['description'], $_POST['themeColor'], $_POST['lang'], $_POST['tel'], $_POST['email'], $_POST['telegram'], $_POST['pages'])) {
  $url = $_POST['url'];
  $name = $_POST['name'];
  $description = $_POST['description'];
  $themeColor = $_POST['themeColor'];
  $lang = $_POST['lang'];
  $tel = $_POST['tel'];
  $email = $_POST['email'];
  $telegram = $_POST['telegram'];
  $pages = $_POST['pages']; // Получение значения поля "pages"

  // Проверка существования записи в таблице site
  $result = $db->query("SELECT COUNT(*) FROM site");
  if ($result->fetch_row()[0] > 0) {
      // Обновление существующей записи в таблице site
      $stmt = $db->prepare("UPDATE site SET url = ?, name = ?, description = ?, themeColor = ?, lang = ?, tel = ?, email = ?, telegram = ?, pages = ?");
      $stmt->bind_param("sssssssss", $url, $name, $description, $themeColor, $lang, $tel, $email, $telegram, $pages);
      $stmt->execute();
  } else {
      // Добавление новой записи в таблицу site
      $stmt = $db->prepare("INSERT INTO site (url, name, description, themeColor, lang, tel, email, telegram, pages) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
      $stmt->bind_param("sssssssss", $url, $name, $description, $themeColor, $lang, $tel, $email, $telegram, $pages);
      $stmt->execute();
  }

    // Отправка запросов на URL-адреса вебхуков
    triggerWebhooksForEvent($db, 'all_events');

  // Перенаправление
  header("HTTP/1.1 303 See Other");
  header("Location: options");
  exit;
}

require VIEWS . '/options.tpl.php';
