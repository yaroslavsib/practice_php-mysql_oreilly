<?php
require_once('startsession.php');

$page_title = "Анкета.";
require_once('header.php');

require_once('appvars.php');
require_once('connectvars.php');

if (!isset($_SESSION['user_id'])) {
    echo '<p class="login"><a href="login.php">Залогинтесь</a>, чтобы получить доступ к этой странице</p>';
    exit();
  }

require_once('navmenu.php');

$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Если пользователь не вводил еще ни одного признака несоответствия в анкету, добавление
// в таблицу БД записей с пустыми значениями признаков несоответствия
$query = "select * from mismatch_response where user_id = '" . $_SESSION['user_id'] . "'";
$data = mysqli_query($dbc, $query);
if (mysqli_num_rows($data) == 0) {
	$query = "select topic_id from mismatch_topic order by category, topic_id";
	$data = mysqli_query($dbc, $query);
	$topicsIDs = array();
	while ($row = mysqli_fetch_array($data)) {
		array_push($topicsIDs, $row['topic_id']);
	}

	// Добавление записей с пустыми значениями в таблицу mismatch_response
	foreach ($topicsIDs as $topic_id) {
		$query = "insert into mismatch_response (user_id, topic_id) values ('" . $_SESSION['user_id'] . "', '$topic_id')";
		mysqli_query($dbc, $query);
	}
}

// Если Анкета отправлена на сервер для обработки,
// обновление признаков несоответствия в таблице mismatch_response
if (isset($_POST['submit'])) {
	foreach ($_POST as $response_id => $response) {
		$query = "update mismatch_response set response = '$response'".
		"where response_id = '$response_id'";
		mysqli_query($dbc, $query);
	}
	echo '<p>Ваши признаки несоответствия сохранены</p>';
}

// Извлечение данных признаков несоответствия из базы для создания формы
$query = "SELECT mr.response_id, mr.response, mr.topic_id, mt.name as topic_name, mc.name as category_name ".
	"from mismatch_response as mr " . 
	"inner join mismatch_topic as mt using (topic_id) " .
	"inner join mismatch_category as mc using(category_id) " .
	"where mr.user_id =" . $_SESSION['user_id'];
$data = mysqli_query($dbc, $query);
$responses = array();
while ($row = mysqli_fetch_array($data)) {
		array_push($responses, $row);
}

mysqli_close($dbc);

// Создание формы "анкета" путем прохождения в цикле
// массива с данными признаков несоответствия
echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '" />';
echo '<p>Что вы чувствуете по каждому из этих признаков несоответствия?</p>';
$category = $responses[0]['category_name'];
echo '<fieldset><legend>' . $responses[0]['category_name'] . '</legend>';
foreach ($responses as $response) {
	// Начинайте новую группу признаков несоответствия только в том случае,
	// если изменилась категория, к которой они относятся
	if ($category != $response['category_name']) {
		$category = $response['category_name'];
		echo '</fieldset><fieldset><legend>' . $response['category_name'] . '</legend>';
	}
// Вывод кнопок с зависимой фиксацией для выбора признаков несоответствия
	echo '<label ' . ($response['response'] == NULL ? 'class="error"' : '') . ' for="' .
		$response['response_id'] . '">' . $response['topic_name'] . ':</label>';
	echo '<input type="radio" id="' . $response['response_id'] . '" name="' . $response['response_id'] . 
		'" value="1" ' . ($response['response'] == 1 ? 'checked="checked"' : '') . ' />Предпочтение ';
	echo '<input type="radio" id="' . $response['response_id'] . '" name="' . $response['response_id'] . 
		'" value="2" ' . ($response['response'] == 2 ? 'checked="checked"' : '') . ' />Отвращение <br />';
}

echo '</fieldset>';
echo '<input type="submit" value="Сохранение анкеты" name="submit" />';
echo '</form>';

require_once('footer.php');

?>