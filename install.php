<?php

$title = 'Установка';

session_start();

// Проверка отправки формы
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Получение данных из формы
    if (isset($_POST['db_host'])) {
        $db_host = $_POST['db_host'];
        $db_user = $_POST['db_user'];
        $db_pass = $_POST['db_pass'];
        $db_name = $_POST['db_name'];

        // Проверка соединения с базой данных
        $db = @new mysqli($db_host, $db_user, $db_pass, $db_name);
        if ($db->connect_error) {
            // Сохранение ошибки в сессии
            $_SESSION['error'] = $db->connect_error;

            // Сохранение данных формы в сессии
            $_SESSION['db_host'] = $db_host;
            $_SESSION['db_user'] = $db_user;
            $_SESSION['db_pass'] = $db_pass;
            $_SESSION['db_name'] = $db_name;
        } else {
            // Сохранение данных формы в сессии
            $_SESSION['db_host'] = $db_host;
            $_SESSION['db_user'] = $db_user;
            $_SESSION['db_pass'] = $db_pass;
            $_SESSION['db_name'] = $db_name;
            $_SESSION['db_connected'] = true;

            // Создание таблиц базы данных
            $query = "
              CREATE TABLE `posts` (
                  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                  `title` varchar(255) NOT NULL,
                  `content` text NOT NULL,
                  `is_draft` tinyint(4) DEFAULT '0',
                  `page` varchar(255) DEFAULT NULL,
                  `create_date` DATETIME,
                  `update_date` DATETIME
              );
            ";
            $query .= "
              CREATE TABLE `site` (
                  `url` varchar(255) NOT NULL,
                  `name` varchar(255) NOT NULL,
                  `description` text,
                  `themeColor` varchar(7) DEFAULT NULL,
                  `lang` varchar(5) DEFAULT NULL,
                  `tel` varchar(20) DEFAULT NULL,
                  `email` varchar(255) DEFAULT NULL,
                  `telegram` varchar(255) DEFAULT NULL,
                  `pages` varchar(255) DEFAULT NULL
              );
            ";
            $query .= "
              CREATE TABLE `users` (
                  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                  `username` varchar(255) NOT NULL,
                  `password` varchar(255) NOT NULL,
                  `email` varchar(255) DEFAULT NULL,
                  `role` varchar(255) DEFAULT NULL
              );
            ";
            $query .= "
              CREATE TABLE `webhooks` (
                  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                  `url` varchar(255) NOT NULL,
                  `event` varchar(255) NOT NULL,
                  `token` text DEFAULT NULL
              );
            ";
            $db->multi_query($query);

            // Удаление ошибки из сессии
            unset($_SESSION['error']);
        }
    } elseif (isset($_POST['username'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $email = $_POST['email'];

        // Подключение к базе данных
        $db_host = $_SESSION['db_host'];
        $db_user = $_SESSION['db_user'];
        $db_pass = $_SESSION['db_pass'];
        $db_name = $_SESSION['db_name'];
        $db = new mysqli($db_host, $db_user, $db_pass, $db_name);

        // Добавление пользователя в базу данных
        $password = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, 'admin')";
        $stmt = $db->prepare($query);
        $stmt->bind_param("sss", $username, $password, $email);
        $stmt->execute();
        $stmt->close();

        // Закрытие соединения с базой данных
        $db->close();

        // Сохранение флага создания администратора в сессии
        $_SESSION['admin_created'] = true;
    }
}

// Проверка выполнения предыдущих шагов
if (isset($_GET['step'])) {
    $step = $_GET['step'];
} else {
    $step = 0;
}

if ($step > 1 && (!isset($_SESSION['db_host']) || !isset($_SESSION['db_user']) || !isset($_SESSION['db_pass']) || !isset($_SESSION['db_name']))) {
    // Перенаправление пользователя на первый шаг
    header('Location: install?step=1');
    exit;
} elseif ($step > 2 && !isset($_SESSION['db_connected'])) {
    // Перенаправление пользователя на второй шаг
    header('Location: install?step=2');
    exit;
} elseif ($step > 3 && !isset($_SESSION['admin_created'])) {
    // Перенаправление пользователя на третий шаг
    header('Location: install?step=3');
    exit;
}

switch ($step) {
    case 0:
        // Отображение приветственного сообщения и ссылки на первый шаг
        require_once ROOT . '/steps/welcome.tpl.php';
        break;
    case 1:
        // Отображение формы для первого шага (ввод данных базы данных)
        require_once ROOT . '/steps/db.tpl.php';
        break;
    case 2:
      // Проверка ошибки соединения с базой данных
      if (isset($_SESSION['error'])) {
          // Отображение сообщения об ошибке и ссылки на предыдущий шаг
          require_once ROOT . '/steps/error.tpl.php';
      } else {
          // Отображение сообщения об успешном соединении и ссылки на следующий шаг
          require_once ROOT . '/steps/ok.tpl.php';
      }
      break;
    case 3:
      // Отображение формы для третьего шага (ввод данных администратора)
      require_once ROOT . '/steps/admin.tpl.php';
      break;
    case 4:
        // Отображение сообщения об успешной установке
        require_once ROOT . '/steps/done.tpl.php';

        // Создание файла config.php
        $config = <<<EOT
        <?php

        define('DB_HOST', '$db_host');
        define('DB_USER', '$db_user');
        define('DB_PASS', '$db_pass');
        define('DB_NAME', '$db_name');

        \$api_vt = '0b93fe34567fc98f5e00e793086a87be993e2df546c2f25ea40b35c877058b91';
        EOT;

        file_put_contents(CONFIG . '/config.php', $config);

        // Чтение содержимого файла index.php
        $indexContent = file_get_contents(ROOT . '/public/index.php');

        // Удаление строки кода
        $pattern = <<<EOT
} elseif (\$uri == 'install') {
    require ROOT . '/install.php';
EOT;
        $updatedContent = str_replace($pattern, '', $indexContent);

        // Запись обновленного содержимого обратно в файл index.php
        file_put_contents(ROOT . '/public/index.php', $updatedContent);

        // Удаление файла install.php
        unlink(__FILE__);
      
        // Удаление файлов из папки steps
        $files = glob(ROOT . '/steps/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }

        // Удаление папки steps
        rmdir(ROOT . '/steps');

        // Удаление сессии
        session_unset();
        session_destroy();

        exit;
}