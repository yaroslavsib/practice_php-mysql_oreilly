<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Гитарные войны. Добавьте свой рейтинг</title>
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>
	<body>
		<h2>Гитарные войны. Добавьте свой рейтинг</h2>
		<hr />

		<?php
		require_once('appvars.php');
		require_once('connectvars.php');
		$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) 
			    	  or die("Невозможно подключиться к базе данных");

		if (isset($_POST['submit'])) {
		  $name = mysqli_real_escape_string($dbc, trim($_POST['name']));
		  $score = mysqli_real_escape_string($dbc, trim($_POST['score']));
		  $screenshot = mysqli_real_escape_string($dbc, trim($_FILES['screenshot']['name']));
		  $screenshot_type = $_FILES['screenshot']['type'];
		  $screenshot_size = $_FILES['screenshot']['size'];

		    if (!empty($name) && !empty($score) && is_numeric($score) && !empty($screenshot)) {
		      if ((($screenshot_type == 'image/gif') || ($screenshot_type == 'image/jpeg') || ($screenshot_type == 'image/pjeg') || ($screenshot_type == 'image/png'))
		      	&& ($screenshot_size > 0) && ($screenshot_size <= GW_MAXFILESIZE)) {
		      	if ($_FILES['screenshot']['error'] == 0) {
				  $target = GW_UPLOADPATH.$screenshot;
				  // Перемещаем файл в $target
				  if (move_uploaded_file($_FILES['screenshot']['tmp_name'], $target)) {

					$query = "INSERT INTO guitarwars (date, name, score, screenshot) VALUES (now(), '$name', '$score', '$screenshot')";
					mysqli_query($dbc, $query);
					// Confirm success with the user	
					echo "Спасибо за то, что добавили свой рейтинг<br /><br />";
					echo "Имя: $name<br />";
					echo "Рейтинг: $score<br/ >";
					echo '<img src="' .GW_UPLOADPATH.$screenshot. '" alt="Изображение, подтвержающее подлинность рейтинга" /><br />';
					echo '<p><a href="index.php"><< Назад к списку рейтингов</a></p>';

			// Очищаем переменные, чтобы поля формы были пустыми
			$name = '';
			$score = '';
			$screenshot = '';

			} else {
				echo '<p class="error">Возникла проблема при загрузке вашего файла</p>';
			  }
			}
		  } else {
		  	  echo '<p class="error">Скриншот должен быть формата JPEG, GIF or PNG и не превосходить '.(GW_MAXFILESIZE/1024).'килобайт.</p>';
		  	}
		  	// Удаляет временный файл скриншота

		  	@unlink($_FILES['screenshot']['tmp_name']);
		}
		else {
			echo '<p class="error">Пожалуйста, введите всю необходимую информацию корректно.</p>';
		}
	}
	mysqli_close($dbc);
?>
<hr />
		<form enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
			<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo GW_MAXFILESIZE; ?>" />
			<label for="name">Имя:</label>
			<input type="text" id="name" name="name"><br />
			<label for="score">Рейтинг: </label>
			<input type="text" id="score" name="score"><br />
			<label for="screenshot">Файл изображения</label>
			<input type="file" id="screenshot" name="screenshot" />
			<hr />
			<input type="submit" value="Добавить" name="submit">
		</form>
	</body>

</html>
