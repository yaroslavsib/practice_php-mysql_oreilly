<?php
require_once('startsession.php');

$page_title = "Редактировать профиль.";
require_once('header.php');

require_once('appvars.php');
require_once('connectvars.php');

require_once('navmenu.php');
  
  if (!isset($_SESSION['user_id'])) {
    echo '<p class="login">Пожалуйста, <a href="login.php">войдите</a> чтобы просматривать эту страницу</p>';
    exit();
  } else {
    echo '<p class="login">Вы вошли как ' . $_SESSION['username'] . '. <a href="logout.php">Выйти</a></p>';
  }


  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

  if (isset($_POST['submit'])) {
  	// Сбор данных из массива POST
  	foreach ($_POST as $key => $value) {
  		$$key = mysqli_real_escape_string($dbc, trim($value));
  	}
  	$old_picture = mysqli_real_escape_string($dbc, trim($_POST['old_picture']));
    $new_picture = mysqli_real_escape_string($dbc, trim($_FILES['new_picture']['name']));
    $new_picture_type = $_FILES['new_picture']['type'];
    $new_picture_size = $_FILES['new_picture']['size'];
    list($new_picture_width, $new_picture_height) = getimagesize($_FILES['new_picture']['tmp_name']);
    $error = false;

    // Валидация и перемешение загруженного файла, если это необходимо
    if (!empty($new_picture)) {
      if ((($new_picture_type == 'image/gif') || ($new_picture_type == 'image/jpeg') || ($new_picture_type == 'image/pjpeg') ||
        ($new_picture_type == 'image/png')) && ($new_picture_size > 0) && ($new_picture_size <= MM_MAXFILESIZE) &&
        ($new_picture_width <= MM_MAXIMGWIDTH) && ($new_picture_height <= MM_MAXIMGHEIGHT)) {
      	  if ($_FILES['file']['error'] == 0) {
      	  	$target = MM_UPLOADPATH . basename($new_picture);
      	  	if (move_uploaded_file($_FILES['new_picture']['tmp_name'], $target)) {
      	  	  if (!empty($old_picture) && ($old_picture != $new_picture)) {
                @unlink(MM_UPLOADPATH . $old_picture);
              } else {
              	// Загрузка файла провалилась, поэтому нужно удалить временный файл и вывести сообщение об ошибке.
              	@unlink($_FILES['new_picture']['tmp_name']);
              	$error = true;
              	echo '<p class="error">Произошла проблема при загрузке файла</p>';
              }
      	  	}
      	  }
      } else {

      	// Файл не валидный, поэтому нужно удалить временный файл и вывести сообщение об ошибке.
      	@unlink($_FILES['new_picture']['tmp_name']);
        $error = true;
        echo '<p class="error">Ваше фото должно быть в формате GIF, JPEG или PNG, а размер изображения не должен превышать ' . (MM_MAXFILESIZE / 1024) .
          ' Кб и ' . MM_MAXIMGWIDTH . 'x' . MM_MAXIMGHEIGHT . ' пикселей.</p>';
      }
    }

    // Обновляем данные профиля в БД
    if (!$error) {
      if (!empty($first_name) && !empty($last_name) && !empty($gender) && !empty($birthdate) && !empty($city) && !empty($state)) {
      	if (!empty($new_picture)) {
      	  $query = "UPDATE mismatch_user SET first_name = '$first_name', last_name = '$last_name', gender = '$gender', " .
            " birthdate = '$birthdate', city = '$city', state = '$state', picture = '$new_picture' WHERE user_id = '" . $_SESSION['user_id'] . "'";
      	} else {
      	    $query = "UPDATE mismatch_user SET first_name = '$first_name', last_name = '$last_name', gender = '$gender', " .
            " birthdate = '$birthdate', city = '$city', state = '$state' WHERE user_id = '" . $_SESSION['user_id'] . "'";
      	}
      	mysqli_query($dbc, $query);
      	// Подтверждение успешного обновления профиля
      	echo '<p>Ваш профиль был успешно обновлен. Хотите вернуться на <a href="viewprofile.php">страницу</a> своего профиля?</p>';

      	mysqli_close($dbc);
      	exit();
      } else {
      	echo '<p class="error">Вы должны ввести все необходимые данные (кроме фото, оно опционально)</p>';
      }
    } 
  } // Конец проверки submit;
  else {
  	// Сбор данных профиля из БД.
  	$query = "SELECT first_name, last_name, gender, birthdate, city, state, picture FROM mismatch_user WHERE user_id = '" . $_SESSION['user_id'] . "'";
  	$data = mysqli_query($dbc, $query)
  	  or die("Ошибка 99");
    $row = mysqli_fetch_array($data);

    if ($row != 0) {
    	foreach ($row as $key => $value) {
    		$$key = $value;
    	}
    	$old_picture = $row['picture'];
    } else {
    	echo '<p class="error">Возникла проблема при доступе к вашему профилю</p>';
    }
  }
mysqli_close($dbc);
?>
<form enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
  <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo MM_MAXFILESIZE; ?>" />
  <fieldset>
  	<legend>Персональная информация</legend>
  	<label for="firstname">Имя: </label>
  	<input type="text" id="firstname" name="first_name" value="<?php if (!empty($first_name)) echo $first_name; ?>"/><br />
  	<label for="lastname">Фамилия: </label>
  	<input type="text" id="lastname" name="last_name" value="<?php if (!empty($last_name)) echo $last_name; ?>"/><br />
  	<label for="gender">Пол: </label>
  	<select id="gender" name="gender">
  	  <option value="M" <?php if (!empty($gender) && $gender == 'M') echo 'selected = "selected"'; ?>>Муж</option>
  	  <option value="F" <?php if (!empty($gender) && $gender == 'F') echo 'selected = "selected"'; ?>>Жен</option>
  	</select><br />
  	<label for="birthdate">Дата рождения: </label>
  	<input type="text" id="birthdate" name="birthdate" value="<?php if (!empty($birthdate)) echo $birthdate; else echo 'YYYY-MM-DD'; ?>" /><br />
  	<label for="city">Город</label>
  	<input type="text" id="city" name="city" value="<?php if (!empty($city)) echo $city; ?>" /><br />
  	<label for="state">Страна</label>
  	<input type="text" id="state" name="state" value="<?php if (!empty($state)) echo $state; ?>" /><br />
  	<input type="hidden" name="old_picture" value="<?php if (!empty($old_picture)) echo $old_picture; ?>" /><br />
  	<label for="new_picture">Изображение</label>
  	<input type="file" id="new_picture" name="new_picture" />
  	<?php if(!empty($old_picture)) {
  		echo '<img src="'.MM_UPLOADPATH.$old_picture.'" alt="'.$name.'"/>';
  	} ?>
  </fieldset>
  <input type="submit" value="Сохранить изменения" name="submit" />
<?php
require_once('footer.php');
?>