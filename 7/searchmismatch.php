<?php

require_once('startsession.php');

$page_title = "Мое несоответствие";
require_once('header.php');

require_once('appvars.php');
require_once('connectvars.php');

if (!isset($_SESSION['user_id'])) {
    echo '<p class="login"><a href="login.php">Залогинтесь</a>, чтобы получить доступ к этой странице</p>';
    exit();
}

require_once('navmenu.php');

$dbc   = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or die("Не удалось подключиться к базе");

$user_response_query = "select * from mismatch_response where user_id = '" . $_SESSION['user_id'] . "'";
$data                = mysqli_query($dbc, $user_response_query);
if (mysqli_num_rows($data) != 0) {

	$query = "SELECT mr.response_id, mr.topic_id, mr.response, mt.name AS topic_name " . 
    	"FROM mismatch_response AS mr " .
    	"inner join mismatch_topic as mt " .
    	"using (topic_id) " .
    	"WHERE mr.user_id = '" . $_SESSION['user_id'] . "'";
	$data           = mysqli_query($dbc, $query) or die("Ошибка 29");
	$user_responses = array();
	while ($row = mysqli_fetch_array($data)) {
		array_push($user_responses, $row);
	}

	$mismatch_score   = 0;
	$mismatch_user_id = -1;
	$mismatch_topics  = array();

	$mismatch_query = "select user_id from mismatch_user where user_id != '" . $_SESSION['user_id'] . "'";
	$data = mysqli_query($dbc, $mismatch_query);
	while ($row = mysqli_fetch_array($data)) {

		$query2 = "select response_id, topic_id, response from mismatch_response where user_id = '" . $row['user_id'] . "'";
		$data2 = mysqli_query($dbc, $query2);
		$mismatch_responses = array();
		while ($row2 = mysqli_fetch_array($data2)) {
			array_push($mismatch_responses, $row2);
		}

		$score = 0;
		$topics = array();
		for ($i = 0; $i < count($user_responses); $i++) {
			if ($user_responses[$i]['response'] + $mismatch_responses[$i]['response'] == 3) {
				$score += 1;
				array_push($topics, $user_responses[$i]['topic_name']);
			}
		}


		if ($score > $mismatch_score) {
			$mismatch_score = $score;
			$mismatch_user_id = $row['user_id'];
			$mismatch_topics = array_slice($topics, 0);
		}
	}


	if ($mismatch_user_id != -1) {
		$query = "select username, first_name, last_name, city, state, picture FROM mismatch_user WHERE user_id = '$mismatch_user_id'";
		$data = mysqli_query($dbc, $query);
		if (mysqli_num_rows($data) == 1) {
			$row = mysqli_fetch_array($data);
			echo '<table><tr><td class="label">';
			if (!empty($row['first_name']) && !empty($row['last_name'])) {
				echo $row['first_name'] . ' ' . $row['last_name'] . '<br />';
			}
			if (!empty($row['city']) && !empty($row['state'])) {
				echo $row['city'] . ', ' . $row['state'] . '<br />';	
			}
			echo '</td><td>';
			if (!empty($row['picture'])) {
				echo '<img src="' . MM_UPLOADPATH . $row['picture'] . '" alt="Фото"/><br />';
			}
			echo '</td></tr></table>';


			echo '<h4>Вы несовпадаете по ' . count($mismatch_topics) . ' топикам:</h4>';
			foreach ($mismatch_topics as $topic) {
				echo $topic . '<br / >';
			}


			echo '<h4><a href="viewprofile.php?user_id=' . $mismatch_user_id . '" />Посмотреть профиль ' . $row['first_name'] . '</a>.</h4>';
		}
	}
}
else {
	echo '<p>Сначала вы должны ответить на вопросы <a href="questionnaire.php">анкеты</a>.</p>';
}

mysqli_close($dbc);

require_once('footer.php');

?>