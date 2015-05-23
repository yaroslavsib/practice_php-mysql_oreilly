<?php

if (isset($_POST['submit'])) {
	$from = 'syberbatman@gmail.com';
	$subject = $_POST['subject'];
	$elvismail = $_POST['elvismail'];
	$output_form = false;
	
	// Удостовериться, что форма не была пустой, иначе зачем отправлять:)
	if (!$subject && !$elvismail) {
		echo "Вы не ввели никаких данных<br />"; // Если форма пустая - выводим опять форму для заполнения. Если не пустая - соединяемся с базой и выполняем код дальше
		$output_form = true;
	} elseif ($elvismail && !$subject) {
		echo "Вы не ввели тему письма";
		$output_form = true;
	} elseif ($subject && !$elvismail) {
		echo "Вы не ввели содержание письма";
		$output_form = true;
	}
	if ($subject && $elvismail) {
		$dbc = mysqli_connect('localhost', 'root', '', 'elvis_store')
        	or die("Не удалось подключиться к базе данных");

		$query = "SELECT * FROM email_list";

		$result = mysqli_query($dbc, $query)
    		or die("Невозможно отправить данные");

		while ($row = mysqli_fetch_array($result)) { // Отправляет письма на каждый эл адрес в таблице.
			$first_name = $row['first_name'];
			$last_name = $row['last_name'];
	
			$msg = "Уважаемый $first_name $last_name,\n$elvismail"; // Вставляется индивидуальное имя и текст письма, общий для всей рассылки

			$to = $row['email'];

			mail($to, $subject, $msg, 'From: '.$from);

			echo "Электронное письмо отправлено: $to <br />";
		}

		mysqli_close($dbc);
	}
} else {
	$output_form = true;
}

if ($output_form) {
?>
<p>Составьте и отправьте электронное письмо</p>
<form method="post" action="<?php $_SERVER['PHP_SELF']; ?>"> <?php // Файл ссылается сам на себя с помощью параметра $_SERVER['PHP_SELF']; ?>
	<label for="subject">Тема письма</label><br />
	<input type="text" id="subject" name="subject" value="<?php echo $subject; ?>"><br />
	<label for="elvismail">Содержание письма</label><br />
	<textarea id="elvismail" name="elvismail" rows="8" cols="60"><?php echo $elvismail; ?></textarea><br />
	<input type="submit" name="submit" value="Отправить">
</form>

<?php
}
?>