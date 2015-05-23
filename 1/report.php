<!DOCTYPE html>
<html>
<head>
	<title>Космические пришельцы похищали меня - сообщение о похищении</title>
</head>
<body>
    <h2>Космические пришельцы похищали меня - сообщение о похищении</h2>
    <?php
    $name = $_POST['firstname'].' '.$_POST['lastname'];
    /*$when_it_happened = $_POST['whenithappened'];
    $how_long = $_POST['howlong'];
    $alien_description = $_POST['aliendescription'];
    $how_many = $_POST['howmany'];
    $what_they_did = $_POST['whattheydid'];
    $fang_spotted = $_POST['fangspotted'];
    $other = $_POST['other'];
    $email = $_POST['email'];*/
    foreach ($_POST as $key => $value) {
         $$key = $value;
     } 

    $msg = "$name был похищен $whenithappened и отсутствовал в течение $howlong.\n".
    "Количество космических пришельцев: $howmany\n".
    "Описание космических пришельцев: $aliendescription\n".
    "Что они делали? $whattheydid\n".
    "Фэнг замечен? $fangspotted\n".
    "Дополнительная информация: $other";
    $to = "syberbatman@gmail.com";
    $subject = "Космические пришельцы похищали меня - Сообщение о похищении";
    mail($to, $subject, $msg, 'From: '.$email); // Отправка данных из формы на почту.

    echo "Спасибо за заполнение формы.<br>";
    echo "Как вас зовут? $name <br>";
    echo "Вы были похищены $whenithappened <br>";
    echo " и отсутствовали в течение $howlong <br>";
    echo "Опишите их: $aliendescription <br>";
    echo "Сколько их было? $howmany <br>";
    echo "Что они сделали с вами? $whattheydid <br>";
    echo "Видели ли вы мою собаку Фэнга?  $fangspotted <br>";
    echo "Что-нибудь еще? $other <br>";
    echo "Ваш адрес электронной почты $email";
    ?>
</body>
</html>