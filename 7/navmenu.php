<?php

echo '<hr />';
if (isset($_SESSION['username'])) {
	echo '&#10084; <a href="index.php">Главная</a><br />';
    echo '&#10084; <a href="viewprofile.php">Просмотр профиля</a><br />';
    echo '&#10084; <a href="editprofile.php">Редактирование профиля</a><br />';
    echo '&#10084; <a href="questionnaire.php">Анкета</a><br />';
    echo '&#10084; <a href="searchmismatch.php">Мое несоответствие</a><br />';
    echo '&#10084; <a href="logout.php">Выход из приложения ('. $_SESSION['username'].' )</a><br />';
  } else {
    echo '&#10084; <a href="login.php">Войти</a><br />';
    echo '&#10084; <a href="signup.php">Зарегистрироваться</a><br />';
  }
echo '<hr />';
?>