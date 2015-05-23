<?php

session_start();

if (isset($_SESSION['user_id'])) {
	// Если юзер залогинен, удаляем сессию и куки этой сессии
	$_SESSION = array();

	if (isset($_COOKIE[session_name()])) {
		setcookie(session_name(), '', time() - 3600);
	}
	// Удаляем сессию
	session_destroy();
}
// Удаляем куки на user_id и username
setcookie('user_id', '', time() - 3600);
setcookie('username', '', time() - 3600);

// Редирект на домашнюю страницу
$home_url = 'http://' . $_SERVER['SERVER_NAME'] . dirname($_SERVER['PHP_SELF']) . '/index.php';
header('Location: ' . $home_url);
?>