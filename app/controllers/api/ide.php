<?php

$title = 'GraphiQL IDE';
  
// Проверка авторизации пользователя
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: /");
    exit;
}

$nonce = $_SESSION['nonce'];

require VIEWS . '/ide.tpl.php';