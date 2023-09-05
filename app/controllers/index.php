<?php
// Проверка наличия файла db.php
if (!file_exists(CONFIG . '/config.php')) {
  // Файл db.php не существует, перенаправление на страницу установки
  header("Location: install");
  exit;
}

session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: admin");
    exit;
} else {
  header("Location: login");
  exit;
}
