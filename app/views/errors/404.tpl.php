<?php

  $title = 'Ошибка 404';

  session_start();
  if (!isset($_SESSION['user_id'])) {
      exit;
  }
  
  $nonce = $_SESSION['nonce'];

?>

<?php include VIEWS . '/includes/header.php' ?>

<h3>404 - Страница не найдена</h3>

<?php include VIEWS . '/includes/footer.php' ?>