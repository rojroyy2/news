<?php

	require($_SERVER['DOCUMENT_ROOT'].'\config.php');

	$link = mysqli_connect($dbInfo['host'], $dbInfo['user'], $dbInfo['password'], $dbInfo['db']);

	if ($link == false){
		echo 'Ошибка! Не удалось подключиться к базе данных! <br>';
		exit(mysqli_error());
	}

?>