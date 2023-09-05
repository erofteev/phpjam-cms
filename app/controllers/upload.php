<?php
// Проверка отправки файла
if (!isset($_FILES['image'])) {
    // Файл не отправлен
    // Отправка кода ошибки 404 и перенаправление на страницу ошибки 404
    header('HTTP/1.1 404 Not Found');
    require_once VIEWS . '/errors/404.tpl.php';
    exit;
}

require_once CONFIG . '/config.php';

// API-ключ VirusTotal
$apiKey = $api_vt;

// Определение домена сайта
$scheme = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$domain = $scheme . '://' . $host;

// Проверка отправки файла
if (isset($_FILES['image'])) {
    // Получение информации о файле
    $file = $_FILES['image'];
    $fileTmpName = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileType = $file['type'];

    // Генерация хеша для имени файла
    $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $hashedFileName = md5_file($fileTmpName) . '.' . $fileExtension;

    // Проверка типа файла
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml'];
    if (!in_array($fileType, $allowedTypes)) {
        // Недопустимый тип файла
        echo 'Недопустимый тип файла';
        exit;
    }

    // Проверка размера файла
    $maxSize = 5 * 1024 * 1024; // 5 МБ
    if ($fileSize > $maxSize) {
        // Файл слишком большой
        echo 'Файл слишком большой';
        exit;
    }

    // Проверка имени файла на наличие недопустимых символов
    if (!preg_match('/^[a-zA-Z0-9_\-\s]+\.(jpe?g|png|gif|svg)$/i', $hashedFileName)) {
        // Недопустимое имя файла
        echo 'Недопустимое имя файла';
        exit;
    }

    // Сканирование файла с помощью VirusTotal
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://www.virustotal.com/vtapi/v2/file/scan');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, [
        'apikey' => $apiKey,
        'file' => new CURLFile($fileTmpName),
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    // Проверка результата сканирования
    $data = json_decode($response, true);
    if (isset($data['response_code']) && $data['response_code'] === 1) {
        // Файл успешно отправлен на сканирование
        // Результат сканирования с помощью API VirusTotal
    } else {
        // Ошибка при сканировании файла
        echo 'Ошибка при сканировании файла';
        exit;
    }

    // Определение пути для сохранения файла
    $uploadDir = 'media/';
    $uploadPath = $uploadDir . $hashedFileName;

    // Перемещение файла в папку для загрузок
    if (move_uploaded_file($fileTmpName, $uploadPath)) {
        // Возвращение ссылки на загруженное изображение
        echo $domain . '/' . $uploadPath;
        //echo $uploadPath;
    } else {
        // Отображение сообщения об ошибке
        echo 'Ошибка при загрузке файла';
    }
} else {
    // Отображение сообщения об ошибке
    echo 'Файл не отправлен';
}
