<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Risky Jobs - Search</title>
  <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
  <img src="riskyjobs_title.gif" alt="Risky Jobs" />
  <img src="riskyjobs_fireman.jpg" alt="Risky Jobs" style="float:right" />
  <h3>Risky Jobs - Search Results</h3>

<?php
require_once('connectvars.php');
require_once('functions.php');

$user_search = $_GET['usersearch'];
$user_search = str_replace(',', ' ', $user_search);
$user_search_array = explode(' ', $user_search);
$search_words = array();
foreach ($user_search_array as $word) {
	if (!empty($word)) {
		$search_words[] = "WHERE description LIKE '%$word%'";
	}
}
$search_words = implode(' or ', $search_words);

$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$query = "SELECT * FROM riskyjobs ";

$result = mysqli_query($dbc, $query);

echo '<table border="0" cellpadding="2">';
echo '<tr class="heading"><td>Работа</td><td>Описание</td><td>Штат</td><td>Дата</td></tr>';
while ($row = mysqli_fetch_array($result)) {
	echo '<tr class="results">';
	echo '<td width="20%">' . $row['title'] . '</td>';
	echo '<td width="50%">' . substr($row['description'], 0, 100) . '...</td>';
	echo '<td width="10%">' . $row['state'] . '</td>';
	echo '<td width="20%">' . substr($row['date_posted'], 0, 10) . '</td>';
	echo '</tr>';
}
echo '</table>';
mysqli_close($dbc);


?>
</body>
</html>