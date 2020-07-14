<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Новостной сайт</title>
	<link rel="stylesheet" type="text/css" href="styles/style.css">
	<link rel="stylesheet" type="text/css" href="styles/index.css">
</head>
<body>
	<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>

		<?php

		require('modules/db_connect.php');
		require_once('header.php');

		?>
	<div id="app">
		<div id="newsConteiner">
			<div class="pagenator">
				<div class="pag" :class="{pagClick: pagenator == elem}" @click="pagClick" v-for="elem in pagenatorList" :data-date="elem.date" v-show="pagenatorMax >= 1">{{elem}}</div>
				<div class="allNews pag" v-if="category != 0" @click="allNewsClick">Все новости</div>
			</div>
			<div id="newsLoading" v-show="newsLoadingStatus != ''">{{newsLoadingStatus}}</div>
			<div v-for="elem in newsList" class="news" v-show="(newsList.length != 0)&&(newsLoadingStatus == '')">
				<div class="newsImg">
					<img v-bind:src="elem.img">
				</div>
				<div class="newsInfoConteiner">
					<a class="newsTitle" v-bind:href="elem.url">{{elem.title}}</a>
					<div class="info">
						<p @click="categoryClick" class="categoryNews" v-bind:data-category="elem.categoryId">{{elem.categoryName}}</p>
						<p class="dateNews">Дата публикации: {{elem.date}}</p>
						<p class="viewsNews">{{elem.numberViews}} просмотров</p>
					</div>
					<div class="preview">{{elem.preview}} ...</div>
				</div>
			</div>
			<div class="pagenator" v-show="newsLoadingStatus == ''">
				<div class="pag" :class="{pagClick: pagenator == elem}" @click="pagClick" v-for="elem in pagenatorList" :data-date="elem.date" v-show="pagenatorMax >= 1">{{elem}}</div>
				<div class="allNews pag" v-if="category != 0" @click="allNewsClick">Все новости</div>
			</div>
		</div>
	</div>
	<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
	<script type="text/javascript" src="scripts/header.js"></script>
	<script type="text/javascript" src="scripts/index.js"></script>	
	<?php 

		if (isset($_GET['category'])){
			echo '<script>app.category = '. (int) $_GET['category'].';</script>';
		}

	?>
</body>
</html>