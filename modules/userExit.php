<?php

	session_start();
	$_SESSION['root'] = 0;
	$_SESSION['userName'] = 'null';
	session_destroy();
	header("Location: http://news/index.php");

?>