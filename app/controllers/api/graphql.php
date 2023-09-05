<?php
require_once ROOT . '/vendor/autoload.php';
require_once CONFIG . '/config.php';

use GraphQL\Error\DebugFlag;
use GraphQL\GraphQL;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Schema;

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  // Отправка заголовков CORS
  header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
  header('Access-Control-Allow-Headers: Content-Type');
  exit;
}


// Подключение к базе данных
$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Получение списка таблиц из базы данных
$tablesResult = $db->query('SHOW TABLES');
$tables = $tablesResult->fetch_all();

// Создание типов таблиц и добавление их в список полей корневого запроса
$fields = [];
foreach ($tables as $table) {
    $tableName = $table[0];

    // Пропуск таблицы "users"
    if ($tableName === 'users') {
      continue;
    }

    $type = createTypeFromTable($tableName, $db);
    if ($tableName === 'posts') {
        // Добавление аргумента drafts и изменение функции resolve для поля posts
        $fields['posts'] = [
          'type' => Type::listOf($type),
          'args' => [
              'drafts' => ['type' => Type::boolean()],
              'page' => ['type' => Type::string()],
          ],
          'resolve' => function ($root, $args) use ($db, $tableName) {
              // Проверка наличия аргумента drafts
              if (isset($args['drafts']) && $args['drafts'] === true) {
                  // Возвращаем только черновики
                  $stmt = $db->prepare("SELECT * FROM `$tableName` WHERE is_draft = 1");
              } else {
                  // Возвращаем только опубликованные статьи
                  if (isset($args['page'])) {
                      // Проверка наличия аргумента page
                      $stmt = $db->prepare("SELECT * FROM `$tableName` WHERE is_draft = 0 AND page = ? ORDER BY create_date ASC");
                      $stmt->bind_param("s", $args['page']);
                  } else {
                      // Возвращаем все опубликованные статьи
                      $stmt = $db->prepare("SELECT * FROM `$tableName` WHERE is_draft = 0 ORDER BY create_date ASC");
                  }
              }
              $stmt->execute();
              $result = $stmt->get_result();
              $data = $result->fetch_all(MYSQLI_ASSOC);
      
              return $data;
          },
      ];
    } elseif ($tableName === 'site') {
        $fields['site'] = [
          'type' => $type,
          'resolve' => function ($root, $args) use ($db) {
              // Получение данных из таблицы site
              $result = $db->query("SELECT * FROM `site`");
              $data = $result->fetch_all(MYSQLI_ASSOC);

              return $data[0];
          },
      ];
    } else {
        // Добавление полей для других таблиц
        $fields[$tableName] = [
            'type' => Type::listOf($type),
            'resolve' => function ($root, $args) use ($db, $tableName) {
                // Получение данных из определенной таблицы
                $result = $db->query("SELECT * FROM `$tableName`");
                $data = $result->fetch_all(MYSQLI_ASSOC);

                return $data;
            },
        ];
    }
}

// Определение корневого запроса
$queryType = new ObjectType([
    'name' => 'Query',
    'fields' => $fields,
]);

// Создание схемы GraphQL
$schema = new Schema([
    'query' => $queryType,
]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Получение тела запроса
        $body = json_decode(file_get_contents('php://input'), true);

        // Проверка наличия тела запроса
        if (!isset($body['query'])) {
            throw new Exception('Запрос отсутствует');
        }

        // Выполнение GraphQL-запроса
        $result = GraphQL::executeQuery(
            $schema,
            $body['query'],
            null,
            null,
            $body['variables'] ?? null
        );
    } catch (Exception $e) {
        // Обработка ошибок
        $result = [
            'errors' => [
                ['message' => $e->getMessage()],
            ],
        ];
    }

    // Добавление заголовка Access-Control-Allow-Origin
    header('Access-Control-Allow-Origin: *');

    // Вывод результата в формате JSON
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode($result, DebugFlag::INCLUDE_DEBUG_MESSAGE | DebugFlag::INCLUDE_TRACE);
} else {
    // Отображение сообщения об ошибке для других методов HTTP
    http_response_code(405);
    echo 'Метод не разрешен';
}

function createTypeFromTable($tableName, $db) {

  // Пропуск таблицы "users"
  if ($tableName === 'users') {
    return null;
  }

  // Получение списка колонок таблицы
  $result = $db->query("SHOW COLUMNS FROM `$tableName`");
  $columns = $result->fetch_all(MYSQLI_ASSOC);

  // Определение полей типа
  $fields = [];
  foreach ($columns as $column) {
      $fields[$column['Field']] = [
          'type' => Type::string(), // Здесь вы можете определить тип поля в зависимости от типа колонки в базе данных
      ];
  }

  // Создание нового типа
  $type = new ObjectType([
      'name' => ucfirst($tableName),
      'fields' => $fields,
  ]);

  return $type;
}
