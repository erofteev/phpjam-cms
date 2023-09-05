<?php

// Проверка наличия файла config.php
if (!file_exists(CONFIG . '/config.php')) {
  // Файл config.php не существует, перенаправление на страницу установки
  header("Location: install");
  exit;
}

$title = 'Авторизация';

// Проверка авторизации пользователя
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: /');
    exit;
}

// Подключение к базе данных
require_once CONFIG . '/config.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Проверка отправки формы
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Получение данных из формы авторизации
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Поиск пользователя в базе данных
    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Проверка результата
    if ($result->num_rows > 0) {
      // Получение данных пользователя
      $user = $result->fetch_assoc();

      // Проверка пароля
      if (password_verify($password, $user['password'])) {
          // Авторизация успешна
          session_start();
          // Проверка чекбокса "Запомнить меня"
          if (isset($_POST['remember']) && $_POST['remember'] == '1') {
              // Установка времени жизни сессии на 30 дней
              $lifetime = 30 * 24 * 60 * 60;
              session_set_cookie_params($lifetime);
          }

          $_SESSION['user_id'] = $user['id'];
          $_SESSION['username'] = $user['username'];

          // Возвращаем JSON-ответ с информацией об успешной авторизации и перенаправлением
          echo json_encode(array('success' => true, 'redirect' => 'admin'));
          exit;
      } else {
          // Неверный пароль
          echo json_encode(array('success' => false, 'message' => 'Неверный пароль!'));
          exit;
      }
    } else {
      // Пользователь не найден
      echo json_encode(array('success' => false, 'message' => 'Пользователь не найден!'));
      exit;
    }

    $stmt->close();
}

$db->close();


require_once VIEWS . '/login.tpl.php';