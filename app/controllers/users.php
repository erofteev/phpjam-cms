<?php

$title = 'Управление пользователями';

// Проверка авторизации пользователя
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: /");
    exit;
}

$nonce = $_SESSION['nonce'];

// Подключение файла конфигурации
require_once CONFIG . '/config.php';

// Подключение к базе данных MySQL
$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Обработка формы создания пользователя
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Получение данных из формы
  $username = $_POST['username'];
  $password = $_POST['password'];
  $role = $_POST['role'];

  // Проверка, существует ли пользователь с таким именем
  $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  if ($stmt->fetch()) {
      // Пользователь с таким именем уже существует
      echo json_encode(array('success' => false, 'message' => 'Пользователь с таким именем уже существует'));
      exit;
  } else {
      // Хеширование пароля
      $password_hash = password_hash($password, PASSWORD_DEFAULT);

      // Вставка нового пользователя в базу данных
      $stmt = $db->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
      $stmt->bind_param("sss", $username, $password_hash, $role);
      if (!$stmt->execute()) {
          // Ошибка при добавлении пользователя
          echo json_encode(array('success' => false, 'message' => 'Ошибка при добавлении пользователя: ' . $stmt->error));
          exit;
      } else {
          // Пользователь успешно добавлен
          $id = $db->insert_id;
          echo json_encode(array('success' => true, 'message' => 'Пользователь успешно добавлен', 'id' => $id));
          exit;
      }
  }

  // Перенаправление на страницу пользователей с использованием PRG
  header("HTTP/1.1 303 See Other");
  header("Location: /users");
  exit;
}

// Получение списка пользователей из базы данных
$users = [];
$result = $db->query("SELECT * FROM users");
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

// Обработка действия удаления пользователя
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
  // Проверка наличия параметра id
  if (!isset($_GET['id'])) {
    echo json_encode(array('success' => false, 'message' => 'Не указан идентификатор пользователя'));
    exit;
  }

  // Проверка, что идентификатор пользователя не равен 1
  if ($_GET['id'] == 1) {
    echo json_encode(array('success' => false, 'message' => 'Нельзя удалить этого пользователя'));
    exit;
  }

  // Удаление пользователя из базы данных
  $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
  $stmt->bind_param("i", $_GET['id']);
  $stmt->execute();

  // Возврат JSON-ответа об успешном удалении пользователя
  echo json_encode(array('success' => true, 'message' => 'Пользователь успешно удален'));
  exit;
}

$db->close();



require_once VIEWS . '/users.tpl.php';
