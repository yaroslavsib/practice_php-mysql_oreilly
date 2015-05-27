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
$user_search = str_replace(',', ' ', $user_search); // Заменяем запятые на пробелы
$where_list  = explode(' ', $user_search); // Делаем массив из строки
$where_list_description = array(); // Новый массив куда будем добавлять части запроса с description
$final_search = ''; // Финальная строка, которую мы добавим к первоначальному запросу
foreach ($where_list as $word) { 
	if (!empty($word)) { // Пустые строки не добавляем, иначе поиск выдаст все результаты
	$where_list_description[] = "description LIKE '%$word%'";
	}
}
$final_search = implode(' OR ', $where_list_description); // Объединяем массив в финальную строку

$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$general_query = "SELECT title, description, state, date_posted FROM riskyjobs"; // Первоначальный запрос

if (!empty($final_search)) {
	$general_query .= " WHERE $final_search"; // Финальный запрос
}

$result = mysqli_query($dbc, $general_query);

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