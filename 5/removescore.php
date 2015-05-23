<?php
require_once('authorize.php');
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Гитарные войны. Удалить рейтинг</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
  <h2>Гитарные войны. Удаление рейтинга</h2>
<?php
require_once('appvars.php');
require_once('connectvars.php');
if (isset($_GET['id']) && isset($_GET['date']) && isset($_GET['name']) && isset($_GET['score']) && isset($_GET['screenshot'])) {
	// Собираем данные из ГЕТ запроса и присваиваем их новым переменным
	foreach ($_GET as $key => $value) {
		$$key = $value;
	}
} elseif (isset($_POST['id']) && isset($_POST['name']) && isset($_POST['score'])) {
	// Собираем данные из Пост запроса и присваиваем их новым переменным
	foreach ($_POST as $key => $value) {
		$$key = $value;
	}
} else {
	echo '<p class="error">Вы не выбрали данные для удаления.</p>';
}
if (isset($_POST['submit'])) {
  if ($_POST['confirm'] == 'Да') {
  	// Удаляем файл изображения
  	@unlink(GW_UPLOADPATH.$screenshot);

  	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
  	  or die("Не удалось подключиться к базе данных");
  	$query = "DELETE FROM guitarwars where id = $id limit 1";
  	mysqli_query($dbc, $query);
  	mysqli_close($dbc);

  	echo '<p>Рейтинг '.$score.' для имени '.$name.' был успешно удален</p>';
  } else {
  	echo '<p class="error">Рейтинг не был удален</p>';
  }
} elseif (isset($id) && isset($name) && isset($date) && isset($score)) {
	echo '<p>Вы действительно хотите удалить этот рейтинг?</p>';
	echo '<p><strong>Имя: </strong>'.$name.'<br /><strong>Дата: </strong>'.$date.
	'<br /><strong>Рейтинг: </strong>'.$score.'</p>';
	echo '<form method="post" action="'.$_SERVER['PHP_SELF'].'">';
	echo '<input type="radio" name="confirm" value="Да"/>Да ';
	echo '<input type="radio" name="confirm" value="Нет" checked="checked" /> Нет <br />';
	echo '<input type="submit" name="submit" value="Подтвердить"/>';
	echo '<input type="hidden" name="id" value="'.$id.'"/>';
	echo '<input type="hidden" name="name" value="'.$name.'"/>';
	echo '<input type="hidden" name="score" value="'.$score.'"/>';
	echo '</form>';
}
echo '<p><a href="admin.php">Назад на страницу администратора</a></p>';
?>
</body>
</html>