<?php

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';

define("ROOT", dirname(__DIR__));
define("PUBLIC", ROOT . '/public');
define("CORE", ROOT . '/core');
define("CONFIG", ROOT . '/config');
define("APP", ROOT . '/app');
define("CONTROLLERS", APP . '/controllers');
define("VIEWS", APP . '/views');
define("PATH", $protocol . $_SERVER['HTTP_HOST']);

require CORE . '/functions.php';

$uri = trim(parse_url($_SERVER['REQUEST_URI'])['path'], '/');

if ($uri === '') {
    require CONTROLLERS . '/index.php';
} elseif ($uri == 'install') {
    require ROOT . '/install.php';
} elseif ($uri == 'login') {
    require CONTROLLERS . '/login.php';
} elseif ($uri == 'admin') {
    require CONTROLLERS . '/admin.php';
} elseif ($uri == 'settings') {
    require CONTROLLERS . '/settings.php';
} elseif ($uri == 'locale') {
    require CONTROLLERS . '/locale.php';
} elseif ($uri == 'options') {
    require CONTROLLERS . '/options.php';
} elseif ($uri == 'users') {
    require CONTROLLERS . '/users.php';
} elseif ($uri == 'post') {
    require CONTROLLERS . '/post.php';
} elseif ($uri == 'upload') {
    require CONTROLLERS . '/upload.php';
} elseif ($uri == 'api/graphql') {
    require CONTROLLERS . '/api/graphql.php';
} elseif ($uri == 'api/ide') {
    require VIEWS . '/ide.tpl.php';
} else {
    abort();
}