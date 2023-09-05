<?php

$title = 'Создание публикации';

// Проверка авторизации пользователя
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login");
    exit;
}

$nonce = $_SESSION['nonce'];

// Подключение файла конфигурации
require_once CONFIG . '/config.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

require_once CORE . '/functions.php';

// Определение режима (создание или редактирование)
$edit_mode = isset($_GET['id']);

// Получение информации о статье в режиме редактирования
if ($edit_mode) {
  // Получение идентификатора статьи из URL
  $id = $_GET['id'];

  // Выборка информации о статье из базы данных
  $stmt = $db->prepare("SELECT title, content, is_draft, page FROM posts WHERE id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $result = $stmt->get_result();
  $post = $result->fetch_assoc();
}

// Проверка отправки формы
if (isset($_POST['submit']) || isset($_POST['draft'])) {
// Получение данных из формы
$title = $_POST['title'];
$content = $_POST['content'];
$page = $_POST['page']; // Получение значения поля "page"

// Валидация данных

if (isset($_POST['submit'])) {
  // Установка значения is_draft в false
  $is_draft = false;

  if ($edit_mode) {
      // Выборка информации о статье из базы данных
      $stmt = $db->prepare("SELECT is_draft FROM posts WHERE id = ?");
      $stmt->bind_param("i", $id);
      $stmt->execute();
      $result = $stmt->get_result();
      $post = $result->fetch_assoc();

      // Проверка, является ли статья черновиком
      if ($post['is_draft']) {
          // Обновление информации о статье в базе данных
          $stmt = $db->prepare("UPDATE posts SET title = ?, content = ?, is_draft = ?, page = ?, create_date = ?, update_date = ? WHERE id = ?");
          $stmt->bind_param("ssisssi", $title, $content, $is_draft, $page, date('Y-m-d H:i:s'), date('Y-m-d H:i:s'), $id);
          $stmt->execute();
      } else {
          // Обновление информации о статье в базе данных
          $stmt = $db->prepare("UPDATE posts SET title = ?, content = ?, is_draft = ?, page = ?, update_date = ? WHERE id = ?");
          $stmt->bind_param("ssissi", $title, $content, $is_draft, $page, date('Y-m-d H:i:s'),$id);
          $stmt->execute();
      }

      // Отправка запросов на URL-адреса вебхуков для события "update_post"
      triggerWebhooksForEvent($db, 'update_post');
  } else {
      // Сохранение новой статьи в базе данных
      $stmt = $db->prepare("INSERT INTO posts (title, content, page, create_date, update_date) VALUES (?, ?, ?, ?, ?)");
      $stmt->bind_param("sssss", $title, $content, $page, date('Y-m-d H:i:s'), date('Y-m-d H:i:s'));
      $stmt->execute();

      // Отправка запросов на URL-адреса вебхуков для события "create_post"
      triggerWebhooksForEvent($db, 'create_post');
  }
} elseif (isset($_POST['draft'])) {
    // Установка значения is_draft в true
    $is_draft = true;

    if ($edit_mode) {
        // Обновление информации о статье в базе данных
        $stmt = $db->prepare("UPDATE posts SET title = ?, content = ?, is_draft = ?, page = ? WHERE id = ?");
        $stmt->bind_param("ssisi", $title, $content, $is_draft, $page,$id);
        $stmt->execute();
    } else {
        // Сохранение новой статьи в базе данных
        $stmt = $db->prepare("INSERT INTO posts (title, content, is_draft, page) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssis", $title, $content, $is_draft, $page);
        $stmt->execute();
    }
}


    // Перенаправление на страницу со списком статей
    header("HTTP/1.1 303 See Other");
    header("Location: admin");
    exit;
}

// Проверка запроса на удаление статьи
if (isset($_POST['delete'])) {
  // Выборка информации о статье из базы данных
  $stmt = $db->prepare("SELECT is_draft FROM posts WHERE id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $result = $stmt->get_result();
  $post = $result->fetch_assoc();

  // Проверка, является ли статья черновиком
  if (!$post['is_draft']) {
      // Отправка запросов на URL-адреса вебхуков для события "delete_post"
      triggerWebhooksForEvent($db, 'delete_post');
  }

  // Удаление статьи из базы данных
  $stmt = $db->prepare("DELETE FROM posts WHERE id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();

  // Перенаправление на страницу со списком статей
  header("HTTP/1.1 303 See Other");
  header("Location: admin");
  exit;
}

require VIEWS . '/post.tpl.php';