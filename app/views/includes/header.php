<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo 'CMS - ' . (isset($title) ? htmlspecialchars($title) : 'Название страницы'); ?></title>
  <base href="<?= PATH ?>/">

  <?php if (isset($inline_head)): ?>
    <?php echo $inline_head; ?>
  <?php endif; ?>

    <link rel="stylesheet" href="assets/css/main.min.css">
    <script defer src="assets/js/main.min.js"></script>

  <?php
    if (isset($scripts)) {
        foreach ($scripts as $script => $defer) {
            echo '<script ' . ($defer ? 'defer ' : '') . 'src="assets/js/' . htmlspecialchars($script) . '"></script>';
        }
    }
  ?>

</head>
<body>
<div class="page container">
  <header class="header">
  <div class="logo"><a class="logo__link" href="/"></a></div>
  <?php
      session_start();
      if (isset($_SESSION['user_id'])):

    require_once CONFIG . '/config.php';
    $db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    $site = $db->query("SELECT * FROM site LIMIT 1")->fetch_assoc();
    
  ?>


<?php if (!empty($site['url'])): ?>
  <div class="site">
    <a class="site__link" target="_blank" href="<?= htmlspecialchars($site['url']) ?>">Перейти на сайт <?= htmlspecialchars($site['name']) ?></a>
  </div>
<?php endif; ?>

<div class="profile">
<span class="profile__title">Вы вошли как</span>
<span class="profile__login"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
<a class="profile__logout profile__logout--icon" href="admin?action=logout&_nonce=<?php echo $nonce ?>" title="Выйти"></a>
</div>

  <?php endif; ?>

</header>


<main class="main">

<?php require VIEWS . '/includes/sidebar.php' ?>