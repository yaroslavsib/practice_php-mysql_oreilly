<?php
  require_once('connectvars.php');

  session_start();

  $error_msg = '';

  if (!isset($_SESSION['user_id'])) {
    if (isset($_POST['submit'])) {
      $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

      $user_username = mysqli_real_escape_string($dbc, trim($_POST['username']));
      $user_password = mysqli_real_escape_string($dbc, trim($_POST['password']));

      if (!empty($user_username) && !empty($user_password)) {
        $query = "SELECT user_id, username FROM mismatch_user WHERE username = '$user_username' AND password = SHA('$user_password')";
        $data  = mysqli_query($dbc, $query);

        if (mysqli_num_rows($data) == 1) {
          // Такая запись есть в БД, значит логирование проведено успешно
          $row = mysqli_fetch_array($data);
          $_SESSION['user_id']  = $row['user_id'];
          $_SESSION['username'] = $row['username'];
          setcookie('user_id', $row['user_id'], time() + (60 * 60 * 24 * 30));
          setcookie('username', $row['username'], time() + (60 * 60 * 24 * 30));
          $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index.php';
          header('Location: ' . $home_url);
        } else {
          $error_msg = "Вы должны ввести валидные имя и пароль, чтобы войти";
        }
      } else {
        $error_msg = "Вы должны ввести свое имя и пароль, чтобы войти";
      }
    }
  }
$page_title = "Вход в систему.";
require_once('header.php');

if (empty($_SESSION['user_id'])) {
  echo '<p class="error">' . $error_msg . '</p>';
?>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<fieldset>
  <legend>Вход</legend>
  <label for="username">Имя пользователя: </label>
  <input type="text" id="username" name="username" value="<?php if (!empty($user_username)) echo $user_username; ?>" /><br />
  <label for="password">Пароль: </label>
  <input type="password" id="password" name="password" />
</fieldset>
<input type="submit" value="Войти" name="submit" />
</form>

<?php
  } else {
    echo '<p class="login">Вы зашли, как ' . $_SESSION['username'] . '</p>';
  }
require_once('footer.php');
?>
