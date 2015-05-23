<?php

$page_title = "Регистрация.";
require_once('header.php');

require_once('connectvars.php');
require_once('appvars.php');

$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if (isset($_POST['submit'])) {
	$username = mysqli_real_escape_string($dbc, trim($_POST['username'])); // Экранирует специальные символы в строках для использования в выражениях SQL, 
	//а трим удаляет пробелы в начале и в конце
	$password1 = mysqli_real_escape_string($dbc, trim($_POST['password1']));
	$password2 = mysqli_real_escape_string($dbc, trim($_POST['password2']));

	if (!empty($username) && !empty($password1) && !empty($password2) && $password1 == $password2) { // Все поля введены и оба пароля совпадают
		$query = "SELECT * FROM mismatch_user WHERE username = '$username'";
		$data = mysqli_query($dbc, $query);

		if (mysqli_num_rows($data) == 0) { // Имя, введенное пользователем не используется, поэтому вводим новые данные в БД.
		  $query = "INSERT INTO mismatch_user (username, password, join_date) VALUES ('$username', SHA('$password1'), now())";
		  mysqli_query($dbc, $query);

		  //  Вывод подтверждения пользователю
		  echo '<p>Ваша новая учетная запись успешно создана. Вы можете войти в приложение и <a href="editprofile.php">отредактировать свой профиль</a>.</p>';

		  mysqli_close($dbc);
		  exit();

		} else {
			// Учетная запись с таким именем уже используется, так что выдаем сообщение об ошибке
			echo '<p class="error">Учетная запись с таким именем уже используется. Введите другой логин</p>';
			$username = '';
		}
	} else {
		echo '<p class="error">Вы должны ввести все поля, в том числе пароль - дважды</p>';
	}
}

mysqli_close($dbc);
?>

<p>Введите ваше имя пользователя и пароль для создания учетной записи в приложении &quot;Несоответствия&quot;.</p>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
  <label for="username">Имя пользователя: </label>
  <input type="text" id="username" name="username" value="<?php if (!empty($username)) echo $username; ?>" /><br />
  <label for="password1">Пароль: </label>
  <input type="password" id="password1" name="password1"/><br />
  <label for="password2">Повторите пароль: </label>
  <input type="password" id="password2" name="password2"/><br />
  <input type="submit" value="Зарегистрироваться" name="submit" />
</form>
<?php
require_once('footer.php');
?>
