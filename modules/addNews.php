<?php
	
	session_start();
	// Проверка пользователя
	if (!isset($_SESSION['root'])){
		$response['root'] = 0;
		$response['userName'] = 'null';
		session_destroy();
		header("Location: http://news/index.php");
		exit();
	}

	require('db_connect.php');

	// Проверка введённых данных
	if ((strlen($_POST['newsName']) == 0) || (strlen($_POST['newsName']) > 255) || (strlen($_POST['newsPreview']) == 0) || (strlen($_POST['newsPreview']) > 400) || (strlen($_POST['newsText']) == 0) || ($_POST['category'] > 5) || ($_POST['category'] < 1)){

		echo 'Проверите правильность заполнения полей! ';
		echo '<a href="http://news/admin.php">Вернуться назад ...</a>';
		exit();

	}

	$newsName = mysqli_real_escape_string($link, $_POST['newsName']);
	$preview = mysqli_real_escape_string($link, $_POST['newsPreview']);
	$text = mysqli_real_escape_string($link, $_POST['newsText']);

	$addNewsQuery = mysqli_query($link, 'INSERT INTO `article` (`id`, `title`, `category`, `preview`, `text`, `datePublication`, `numberViews`) VALUES (NULL, "'.$newsName.'", '.(int) $_POST['category'].', "'.$preview.'", "'.$text.'", CURRENT_TIMESTAMP, 0)');

	$new = mysqli_insert_id($link);

	if (imgSave($new) == true){

		echo 'Новость успешно добавлена! <br>';
		echo '<a href="http://news/admin.php">Добавить ещё</a><br>';
		echo '<a href="http://news/news.php?id='.$new.'">Добавленная статья</a>';

	}else{

		$delNewsQuery = mysqli_query($link, 'DELETE FROM `article` WHERE `id` = '.$new.';');
		echo '<a href="http://news/admin.php">Новость не добавлена. Вернуться назад ...</a>';
		exit();

	}

	// Сохранение изображения

	function imgSave($id){

		if($_FILES['file']['type'] != 'image/jpeg'){
			echo 'Неверный формат файла, должен быть *.jpeg!';
			return false;
		}

		$freedom_size = disk_free_space("/");

		if ($freedom_size <= $_FILES['img']['size']){
			
			echo 'Недостаточно места для сохранения изображения!';
			return false;

		}

		$file = $_FILES['file']['tmp_name'];

		$tempName = $_SERVER['DOCUMENT_ROOT'] . '/styles/newsImg/'.$id.'.jpg';

		if (!move_uploaded_file($file, $tempName)){
			echo 'Изображение не загружено <br>';
			return false;
		}
		return true;

	}

?>