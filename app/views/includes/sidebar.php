<?php

    //$excludedPages = ['/login', '/install'];
    //if (!in_array($_SERVER['REQUEST_URI'], $excludedPages)):
    session_start();
      if (isset($_SESSION['user_id'])):

?>

<div class="wrapper">
        <div class="sidebar no-transition">
          <ul class="sidebar__list">
            <li class="sidebar__item sidebar__item--toggle">
              <span class="sidebar__toggle sidebar__toggle-icon"></span>
            </li>
            <li class="sidebar__item"><a class="sidebar__link sidebar__link--icon-admin" href="admin"><span class="sidebar__text">Админ-панель</span></a></li>
            <li class="sidebar__item"><a class="sidebar__link sidebar__link--icon-write" href="post"><span class="sidebar__text">Создать публикацию</span></a></li>
            <li class="sidebar__item"><a class="sidebar__link sidebar__link--icon-options" href="options"><span class="sidebar__text">Опции</span></a></li>
            <?php if ($_SESSION['role'] === 'admin'): ?>
            <li class="sidebar__item"><a class="sidebar__link sidebar__link--icon-api" href="api/ide"><span class="sidebar__text">GraphQL IDE</span></a></li>
            <li class="sidebar__item"><a class="sidebar__link sidebar__link--icon-users" href="users"><span class="sidebar__text">Пользователи</span></a></li>
            <li class="sidebar__item"><a class="sidebar__link sidebar__link--icon-settings" href="settings"><span class="sidebar__text">Настройки</span></a></li>
            <?php endif; ?>
          </ul>
        </div>
  
        <div class="content no-transition">

<?php endif; ?>