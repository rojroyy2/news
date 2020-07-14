<?php

	session_start();
	// Обработчик авторизации пользователя

	$_POST = json_decode(file_get_contents('php://input'), true);

	require('db_connect.php');

	$solt = "strokaDlyaUslogneniyaChecha";

	$login = mysqli_real_escape_string($link, $_POST['login']);
	$password = mysqli_real_escape_string($link, $_POST['password']);

	$accessQuery = mysqli_query($link, 'SELECT `id`, CONCAT(`admin`.`surname`, " ", `admin`.`name`, " ", `admin`.`patronymic`) as `userName` FROM `admin` WHERE ((`admin`.`login` = "'.md5(md5($login . $solt)).'")&&((`admin`.`password` = "'.md5(md5($password . $solt)).'")));');

	$access = mysqli_fetch_assoc($accessQuery);

	if(isset($access['id'])){
		$_SESSION['userName'] = $access['userName'];
		$_SESSION['root'] = 1;
		$response['root'] = 1;
		$response['userName'] = $access['userName'];
		$response['status'] = true;
	}else{
		$response['status'] = false;
	}

	echo json_encode($response);

?>