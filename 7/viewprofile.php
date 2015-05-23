<?php
require_once('startsession.php');

$page_title = 'Посмотреть профиль.';
require_once('header.php');

require_once('navmenu.php');

require_once('appvars.php');
require_once('connectvars.php');
  
  if (!isset($_SESSION['user_id'])) {
    echo '<p class="login"><a href="login.php">Залогинтесь</a>, чтобы получить доступ к этой странице</p>';
    exit();
  } else {
    echo '<p class="login">Вы вошли как ' . $_SESSION['username'] . '. <a href="logout.php">Выйти</a></p>';
  }

  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

  if (!isset($_GET['user_id'])) {
    $query = "SELECT username, first_name, last_name, gender, birthdate, city, state, picture FROM mismatch_user WHERE user_id = '" . $_SESSION['user_id'] . "'";
  } else {
  	$query = "SELECT username, first_name, last_name, gender, birthdate, city, state, picture FROM mismatch_user WHERE user_id = '" . $_GET['user_id'] . "'";
  }
  $data = mysqli_query($dbc, $query);

  if (mysqli_num_rows($data) == 1) {
    // The user row was found so display the user data
    $row = mysqli_fetch_array($data);
    echo '<table>';
    if (!empty($row['username'])) {
      echo '<tr><td class="label">Логин:</td><td>' . $row['username'] . '</td></tr>';
    }
    if (!empty($row['first_name'])) {
      echo '<tr><td class="label">Имя:</td><td>' . $row['first_name'] . '</td></tr>';
    }
    if (!empty($row['last_name'])) {
      echo '<tr><td class="label">Фамилия:</td><td>' . $row['last_name'] . '</td></tr>';
    }
    if (!empty($row['gender'])) {
      echo '<tr><td class="label">Пол:</td><td>';
      if ($row['gender'] == 'M') {
        echo 'Мужской';
      }
      else if ($row['gender'] == 'F') {
        echo 'Женский';
      }
      else {
        echo '?';
      }
      echo '</td></tr>';
    }
    if (!empty($row['birthdate'])) {
      if (!isset($_GET['user_id']) || ($_SESSION['user_id'] == $_GET['user_id'])) {
        // Show the user their own birthdate
        echo '<tr><td class="label">День рождения:</td><td>' . $row['birthdate'] . '</td></tr>';
      }
      else {
        // Show only the birth year for everyone else
        list($year, $month, $day) = explode('-', $row['birthdate']);
        echo '<tr><td class="label">Год рождения:</td><td>' . $year . '</td></tr>';
      }
    }
    if (!empty($row['city']) || !empty($row['state'])) {
      echo '<tr><td class="label">Местонахождение:</td><td>' . $row['city'] . ', ' . $row['state'] . '</td></tr>';
    }
    if (!empty($row['picture'])) {
      echo '<tr><td class="label">Изображение:</td><td><img src="' . MM_UPLOADPATH . $row['picture'] .
        '" alt="Profile Picture" /></td></tr>';
    }
    echo '</table>';
    if (!isset($_GET['user_id']) || ($_SESSION['user_id'] == $_GET['user_id'])) {
      echo '<p>Вы хотите <a href="editprofile.php">отредактировать</a> свой профиль?</p>';
    }
  } // End of check for a single row of user results
  else {
    echo '<p class="error">Возникла проблема при доступе к вашему профилю.</p>';
  }

  mysqli_close($dbc);
  require_once('footer.php');
?>