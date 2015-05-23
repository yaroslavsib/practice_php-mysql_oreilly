<?php
$username = 'rock';
$password = '123';

if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW']) || ($_SERVER['PHP_AUTH_USER'] != $username) || ($_SERVER['PHP_AUTH_PW'] != $password)) {
  // Пароль пользователя был неправильным или вообще не введен
  header('HTTP/1.1 401 Unauthorized');
  header('WWW-Authenticate: Basic realm="Guitar Wars"');
  exit('<h2>Гитарные войны</h2>Извини, но ты должен ввести правильные данные, чтобы получить доступ к этой странице.');
}

?>