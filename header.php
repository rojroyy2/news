<div id="header">
	<div>
		<?php

			$categoryQuery = mysqli_query($link, 'SELECT * FROM `category`;');

			while ($category = mysqli_fetch_assoc($categoryQuery)){

		?>

		<div class="category" data-id="<?php echo $category['id']; ?>"><?php echo $category['name'] ?></div>

		<?php

			}

		?>
		<div id="autorisationForm">
			<div id="notAuthorized" class="Authorized">
				<input type="text" class="autorizedInput" placeholder="Логин:">
				<input type="password" class="autorizedInput" placeholder="Пароль:">
				<div class="button">Вход</button>
			</div>
<!-- 			<div id="Authorized" class="Authorized">
				<h1>Администратоские ФИО</h1>
				<a href="admin.php" class="button">Редактор</a>
				<div class="button">Выход</div>
			</div> -->
		</div>
	</div>
</div>