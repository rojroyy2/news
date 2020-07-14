<?php

	require('db_connect.php');

	$_POST = json_decode(file_get_contents('php://input'), true);

	// Формирование SQL запросов в зависимости от категории

	$_POST['pagenator'] = (int) $_POST['pagenator'];
	if ($_POST['category'] == 0){

		$category = 'IS NOT NULL';

	}else{

		$category = '= '. (int) $_POST['category'];

	}

	$countNews = mysqli_fetch_assoc(mysqli_query($link, 'SELECT count(*) as `count` FROM `article` WHERE `category` '. $category .';'));

	if ($countNews['count'] == 0){

		// Новостей не найдено
		$response['status'] = false;

	}else{

		$pagenatorMax = ceil($countNews['count'] / 5);
		if (($_POST['pagenator'] < 1)||($_POST['pagenator'] > $pagenatorMax)){

			$_POST['pagenator'] = 1;

		}
		$offset = ($_POST['pagenator'] * 5) - 5;

	}

	// Получение 5 новостей

	$newsQuery = mysqli_query($link, 'SELECT `article`.`id` as `id`, `article`.`title`, `article`.`category` as `categoryId`, `category`.`name` as `categoryName`, `article`.`preview` as `preview`, DATE_FORMAT(`article`.`datePublication`, "%H:%i %d.%m.%Y") as `date`, `article`.`numberViews` FROM `article` LEFT JOIN `category` ON `article`.`category` = `category`.`id` WHERE `article`.`category` '. $category .' ORDER BY `article`.`datePublication` DESC LIMIT '. $offset.',5;');

	while ($news = mysqli_fetch_assoc($newsQuery)){

		$news['img'] = "styles/newsImg/".$news['id'].".jpg";
			$news['url'] = "news.php?id=". $news['id'];
			$response['newsList'][] = $news;
			$response['pagenatorMax'] = $pagenatorMax;

	}

	$response['status'] = true;
	echo json_encode($response);

?>