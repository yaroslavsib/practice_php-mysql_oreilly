<?php
require_once('authorize.php');
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Гитарные войны. Страница администратора</title>
	<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
	<h2>Гитарные войны. Страница администратора</h2>
	<p>Используй эту страницу, чтобы удалить необходимые рейтинги.</p>
	<hr />

<?php
require_once('appvars.php');
require_once('connectvars.php');

$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
  or die("Не удалось подключиться к базе данных");
$query = "SELECT * FROM guitarwars order by score desc, date asc";
$result = mysqli_query($dbc, $query) 
  or die("Не удалось обработать запрос");

echo '<table>';
while ($row = mysqli_fetch_array($result)):
  echo '<tr><td><strong>'.$row['name'].'</strong></td>';
  echo '<td>'.$row['date'].'</td>';
  echo '<td>'.$row['score'].'</td>';
  echo '<td><a href="removescore.php?id='.$row['id'].'&amp;date='.$row['date'].
    '&amp;name='.$row['name'].'&amp;score='.$row['score'].'&amp;screenshot='.
    $row['screenshot'].'">Удалить</a>';
  if ($row['approved'] == 0) {
    echo ' / <a href="approvescore.php?id='.$row['id'].'&amp;date='.$row['date'].
    '&amp;name='.$row['name'].'&amp;score='.$row['score'].'&amp;screenshot='.
    $row['screenshot'].'">Санкционировать</a>';
  }
  echo '</td></tr>';
  
endwhile;
echo '</table>';

mysqli_close($dbc);
?>
</body>
</html>
