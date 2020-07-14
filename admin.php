<?php

	session_start();

	if ($_SESSION['root'] != 1){
		$_SESSION['root'] = 0;
		$_SESSION['userName'] = 'null';
		session_destroy();
		header("Location: http://news/index.php");
	}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="styles/admin.css">
	<title>Добивить новость</title>
</head>
<body>
	<h1>Добавление новости:</h1>
	<form action="modules/addNews.php" method="POST" enctype="multipart/form-data">
		<input id="newsName" name="newsName" type="text" placeholder="Название:">
		<select name="category" id="categoryList" placeholder="Категория:">
			<?php

				require('modules/db_connect.php');

				$categoryQuery = mysqli_query($link, 'SELECT * FROM `category`;');
				while ($category = mysqli_fetch_assoc($categoryQuery)){

			?>
				<option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
			<?php

				}

			?>
		</select>
		<input type="file" name="file" accept="image/jpeg">
		<br>
		<textarea id="newsPreview" name="newsPreview" id="" placeholder="Анонс:"></textarea>
		<br>
		<textarea id="newsText" name="newsText" id="" placeholder="Текст новости:"></textarea>
		<br>
		<input id="button" type="submit" value="Опубликовать">
	</form>
</body>
</html>