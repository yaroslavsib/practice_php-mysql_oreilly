<p>Выберите, пожалуйста, адреса электронной почты, которые вы хотите удалить и нажмите "Удалить"</p>

<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">

<?php

$dbc = mysqli_connect('localhost', 'root', '', 'elvis_store')
    or die("Не удалось подключиться к базе данных");

if (isset($_POST['submit'])) {
	foreach ($_POST['todelete'] as $delete_id) {
		$query = "DELETE FROM email_list where id = $delete_id";
		mysqli_query($dbc, $query)
    		or die("Невозможно удалить данные");
	}
	echo "Покупатели удалены <br />";
}

$query = "SELECT * FROM email_list";
$result = mysqli_query($dbc, $query)
    or die("Невозможно выбрать данные");

while ($row = mysqli_fetch_array($result)) {
	echo '<input type="checkbox" value="'.$row['id'].'" name="todelete[]"/>';
	echo $row['first_name'];
	echo ' '.$row['last_name'];
	echo ' '.$row['email'];
	echo '<br />';
}

mysqli_close($dbc);
?>

<input type="submit" value="Удалить" name="submit"/>
</form>


