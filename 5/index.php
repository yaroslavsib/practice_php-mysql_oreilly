<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Гитарные войны</title>
	<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
	<h2>Гитарные воины. Список рейтингов</h2>
	<p>Добро пожаловать, Гитарный Воин! Твой рейтинг бьет рекорд, зарегистрированный в этом списке рейтингов?</p>
	<p>Если так, просто <a href="addscore.php">добавь свой рейтинг</a> в список</p>
	<hr />

	<?php
	require_once('appvars.php');
	require_once('connectvars.php');

	// Подключение к базе
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
	    or die("Не удалось подключиться к базе");

	// Запрос из БД
	$query = "SELECT * FROM guitarwars WHERE approved = 1 ORDER BY score desc, date asc";
	$data = mysqli_query($dbc, $query)
	    or die("Не удалось обработать запрос");

	echo "<table>"; // Рисуем таблицу с рейтингами
	$i = 0; // Итерация цикла - нужно выделить первую запись
	while ($row = mysqli_fetch_array($data)):
		if ($i == 0) { // Самый высокий рейтинг должен быть выделен соответствующе
			echo '<tr><td colspan="2" class="topscoreheader">Самый высокий рейтинг: '.$row['score'].'</td></tr>';
		}
		echo '<tr><td class="scoreinfo">';
		echo '<span class="score">' . $row['score'] . '</span><br />';
        echo '<strong>Name:</strong> ' . $row['name'] . '<br />';
        echo '<strong>Date:</strong> ' . $row['date'] . '</td>';
        if (is_file(GW_UPLOADPATH . $row['screenshot']) && filesize(GW_UPLOADPATH . $row['screenshot']) > 0) { // Если файл существует и он не пустой
        	echo '<td><img src="' . GW_UPLOADPATH . $row['screenshot'] . '" alt="Подтвержденный рейтинг"/></td></tr>';
        } else {
        	echo '<td><img src="' . GW_UPLOADPATH . 'unverified.gif' . '" alt="Неподтвержденный рейтинг"/></td></tr>';
        }
        $i++;
	endwhile;
	echo '</table>';
	mysqli_close($dbc);
	?>

</body>
</html>