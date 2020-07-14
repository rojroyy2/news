<?php

	session_start();
	if (isset($_SESSION['root'])){
		$response['root'] = $_SESSION['root'];
		$response['userName'] = $_SESSION['userName'];
	}else{
		$response['root'] = 0;
		$response['userName'] = 'null';
		session_destroy();
	}
	echo json_encode($response);

?>