<?php

  $title = 'Ошибка 500';

  session_start();
  if (!isset($_SESSION['user_id'])) {
      exit;
  }
  
  $nonce = $_SESSION['nonce'];

?>

<?php include VIEWS . '/includes/header.php' ?>

<h3>500 - Ошибка сервера</h3>

<?php include VIEWS . '/includes/footer.php' ?>