<?php

$title = 'Админпанель';

// Проверка авторизации пользователя
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login");
    exit;
}

// Проверка запроса на выход из аккаунта
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
  // Проверка nonce
  if (isset($_GET['_nonce']) && $_GET['_nonce'] == $_SESSION['nonce']) {
      // Уничтожение сессии и перенаправление на страницу входа
      session_destroy();
      header("Location: login");
      exit;
  } else {
      die("Ошибка: неверный nonce");
  }
}

// Генерация nonce
$nonce = bin2hex(random_bytes(10));
$_SESSION['nonce'] = $nonce;

// Получение информации об авторизованном пользователе
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Подключение к базе данных
require_once CONFIG . '/config.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

require_once CORE . '/functions.php';

// Получение группы пользователя из базы данных
$stmt = $db->prepare("SELECT role FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$role = $row['role'];

// Сохранение группы в сессии
$_SESSION['role'] = $role;

//// Выборка всех статей из базы данных
//$sql = "SELECT id, title FROM posts";
//$result = $db->query($sql);


// Определение количества элементов на странице
$items_per_page = 10;

// Получение текущих номеров страниц
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$drafts_page = isset($_GET['drafts_page']) ? (int)$_GET['drafts_page'] : 1;

// Вычисление смещений для выборки элементов
$offset = ($page - 1) * $items_per_page;
$drafts_offset = ($drafts_page - 1) * $items_per_page;

// Получение общего количества элементов в таблице posts для каждого списка отдельно
$result = $db->query("SELECT COUNT(*) FROM posts WHERE is_draft = 0");
$row = $result->fetch_row();
$total_items = $row[0];

$draftsResult = $db->query("SELECT COUNT(*) FROM posts WHERE is_draft = 1");
$row = $draftsResult->fetch_row();
$total_drafts_items = $row[0];

// Вычисление общего количества страниц для каждого списка отдельно
$total_pages = ceil($total_items / $items_per_page);
$total_drafts_pages = ceil($total_drafts_items / $items_per_page);

// Получение опубликованных статей из базы данных с учетом пагинации
$result = $db->query("SELECT id, title, page, create_date, update_date FROM posts WHERE is_draft = 0 ORDER BY create_date DESC LIMIT $items_per_page OFFSET $offset");

// Получение черновиков из базы данных с учетом пагинации
$draftsResult = $db->query("SELECT id, title, page FROM posts WHERE is_draft = 1 ORDER BY id DESC LIMIT $items_per_page OFFSET $drafts_offset");

if (isset($_POST['delete'])) {
  // Получение массива идентификаторов отмеченных статей
  $ids = $_POST['ids'] ?? [];

  // Проверка, что массив идентификаторов не пуст
  if (count($ids) > 0) {
    // Создание строки с плейсхолдерами для подготовленного выражения
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    // Подготовка выражения для удаления статей
    $stmt = $db->prepare("DELETE FROM posts WHERE id IN ($placeholders)");
    // Привязка параметров к подготовленному выражению
    $stmt->bind_param(str_repeat('i', count($ids)), ...$ids);
    // Выполнение подготовленного выражения
    $stmt->execute();

    // Отправка запросов на URL-адреса вебхуков для события "delete_post"
    triggerWebhooksForEvent($db, 'delete_post');

    // Перенаправление на страницу со списком статей
    header("HTTP/1.1 303 See Other");
    header('Location: /');
    exit;
  }
} elseif (isset($_POST['draft'])) {
  // Получение массива идентификаторов отмеченных статей
  $ids = $_POST['ids'] ?? [];

  // Проверка, что массив идентификаторов не пуст
  if (count($ids) > 0) {
    // Создание строки с плейсхолдерами для подготовленного выражения
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    // Подготовка выражения для обновления статей
    $stmt = $db->prepare("UPDATE posts SET is_draft = 1 WHERE id IN ($placeholders)");
    // Привязка параметров к подготовленному выражению
    $stmt->bind_param(str_repeat('i', count($ids)), ...$ids);
    // Выполнение подготовленного выражения
    $stmt->execute();

    // Отправка запросов на URL-адреса вебхуков для события "update_post"
    triggerWebhooksForEvent($db, 'update_post');

    // Перенаправление на страницу со списком статей
    header("HTTP/1.1 303 See Other");
    header('Location: /');
    exit;
  }
}

require VIEWS . '/admin.tpl.php';