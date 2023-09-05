<?php

function abort($code = 404)
{
    http_response_code($code);
    require VIEWS . "/errors/{$code}.tpl.php";
    die;
}

//function session($header = '/') {
//  session_start();
//  if (!isset($_SESSION['user_id'])) {
//    header("Location: $header");
//    exit;
//  }
//}

// Вебхуки:
function triggerWebhook($webhookUrl, $token) {
  $ch = curl_init($webhookUrl);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['event_type' => 'build']));
  curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: token ' . $token,
    'User-Agent: GithubActions'
  ]);
  curl_exec($ch);
  curl_close($ch);
}

function triggerWebhooksForEvent($db, $event) {
  // Получение списка вебхуков для указанного события и для события "all_events"
  $stmt = $db->prepare("SELECT url, token FROM webhooks WHERE event = ? OR event = 'all_events'");
  $stmt->bind_param("s", $event);
  $stmt->execute();
  $result = $stmt->get_result();
  
  // Отправка запросов на URL-адреса вебхуков
  while ($row = $result->fetch_assoc()) {
    triggerWebhook($row['url'], $row['token']);
  }
}

//function route($page) {
//  if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
//    require $page;
//  } else {
//    require VIEWS . '/includes/header.php';
//    require $page;
//    require VIEWS . '/includes/footer.php';
//  }
//}

