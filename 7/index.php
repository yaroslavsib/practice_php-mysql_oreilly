<?php
require_once('startsession.php');

$page_title = 'Там, где противоположности сходятся.';
require_once('header.php');

require_once('appvars.php');
require_once('connectvars.php');

require_once('navmenu.php');

  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

  $query = "SELECT user_id, first_name, picture FROM mismatch_user WHERE first_name IS NOT NULL ORDER BY join_date DESC LIMIT 5";
  
  $data = mysqli_query($dbc, $query);

  echo '<h4>Последние пользователи:</h4>';
  echo '<table>';

  while ($row = mysqli_fetch_array($data)) {
  	if (is_file(MM_UPLOADPATH.$row['picture']) && filesize(MM_UPLOADPATH.$row['picture']) > 0) {
  		echo '<tr><td><img src="' . MM_UPLOADPATH . $row['picture'] . '" alt="'.$row['first_name'].'"/></td></tr>';
  	} else {
  		echo '<tr><td><img src="'.MM_UPLOADPATH.'nopic.jpeg" alt="'.$row['first_name'].'"/></td></tr>';
  	}
    if (isset($_SESSION['user_id'])) {
      echo '<tr><td><a href="viewprofile.php?user_id=' . $row['user_id']. '">' . $row['first_name'] . '</a></td></tr>';
    } else {
  	   echo '<tr><td>'.$row['first_name'].'</td></tr>';
    }
  }
  echo '</table>';
  mysqli_close($dbc);
  require_once('footer.php');
?>

