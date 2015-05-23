<!DOCTYPE html>
<html xml:lang="en" lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Отправка данных в базу</title>
</head>
<body>
<?php
	/*$first_name = $_POST['first_name'];
	$last_name = $_POST['last_name'];
	$email = $_POST['email'];*/
	foreach ($_POST as $key => $value) {
		$$key = $value;
	}
	$dbc = mysqli_connect('localhost', 'root', '', 'elvis_store')
	    or die("Не удалось подключиться к базе данных");

	$query = "INSERT INTO email_list (first_name, last_name, email)".
	    "VALUES ('$first_name', '$last_name', '$email')";

	$result = mysqli_query($dbc, $query)
	    or die("Невозможно отправить данные");

	mysqli_close($dbc);

	echo "<h1>Вы ввели следующие данные: </h1><br />";
	echo "Имя и Фамилия: $first_name $last_name <br />";
	echo "Ваш электронный адрес: $email <br />";
	echo "Данные успешно отправлены в базу. Спасибо!";
?>
</body>
</html>