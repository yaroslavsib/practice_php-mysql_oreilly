<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Космические пришельцы похищали меня - сообщение о похищении</title>
</head>
<body>
    <h2>Космические пришельцы похищали меня - сообщение о похищении</h2>
    <?php
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $when_it_happened = $_POST['whenithappened'];
    $how_long = $_POST['howlong'];
    $how_many = $_POST['howmany'];
    $alien_description = $_POST['aliendescription'];
    $what_they_did = $_POST['whattheydid'];
    $fang_spotted = $_POST['fangspotted'];
    $other = $_POST['other'];
    $email = $_POST['email'];
    
    // 1) ПОДКЛЮЧЕНИЕ К БАЗЕ С ПОМОЩЬЮ MYSQLI.

    $dbc = mysqli_connect('localhost', 'root', '', 'aliendatabase') // Подключение к базе: сервер, пользователь, пароль, база данных.
        or die('Не удалось подключиться к базе данных');

    $query = "INSERT INTO alien_abduction (first_name, last_name, when_it_happened, how_long, how_many, alien_description, what_they_did, fang_spotted, other, email)".
        "VALUES ('$firstname', '$lastname', '$when_it_happened', '$how_long', '$how_many', '$alien_description', '$what_they_did', '$fang_spotted', '$other', '$email')";

    $result = mysqli_query($dbc, $query) // Выполнение запроса
        or die('Не удалось обработать запрос');

    mysqli_close($dbc); // Закрытие соединения с БД

    echo "Спасибо за заполнение формы.<br>";
    echo "Как вас зовут? $firstname $lastname <br>";
    echo "Вы были похищены $when_it_happened <br>";
    echo " и отсутствовали в течение $how_long <br>";
    echo "Опишите их: $alien_description <br>";
    echo "Сколько их было? $how_many <br>";
    echo "Что они сделали с вами? $what_they_did <br>";
    echo "Видели ли вы мою собаку Фэнга?  $fang_spotted <br>";
    echo "Что-нибудь еще? $other <br>";
    echo "Ваш адрес электронной почты $email";
    ?>
</body>
</html>