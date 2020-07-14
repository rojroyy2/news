<?php
	
	if ((!isset($_GET['id'])||($_GET['id'] <= 0))){

		header("Location: http://news/index.php");
		exit();

	}

	require('modules/db_connect.php');

	$articleQuery = mysqli_query($link, 'SELECT `id`, DATE_FORMAT(`article`.`datePublication`, "%H:%i %d.%m.%Y") as `date`, `text`, `title` FROM `article` WHERE `id` = '.$_GET['id'].';');
	$article = mysqli_fetch_assoc($articleQuery);

	if (!isset($article['id'])){
		header("Location: http://news/index.php");
		exit();
	}

	$viewsUpdateQuery =  mysqli_query($link, 'UPDATE `article` SET `numberViews` = `numberViews` + 1 WHERE `id` = '.(int) $_GET['id'].';');

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Новостной сайт</title>
	<link rel="stylesheet" type="text/css" href="styles/style.css">
	<link rel="stylesheet" type="text/css" href="styles/news.css">
</head>
<body>
	<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>

	<?php

	require_once('header.php');

	?>

	<div id="news">
		<h1><?php echo $article['date'].'   -   ' .$article['title']; ?></h1>
		<div class="imgConteyner">
			<img src="styles/newsImg/<?php echo $_GET['id'] ?>.jpg">
		</div>
		<div class="text"><?php echo $article['text'] ?></div>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
	<script type="text/javascript" src="scripts/header.js"></script>
	<script type="text/javascript" src="scripts/news.js"></script>
</body>
</html>