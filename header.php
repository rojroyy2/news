<div id="header">
	<div>
		<?php

			$categoryQuery = mysqli_query($link, 'SELECT * FROM `category`;');

			while ($category = mysqli_fetch_assoc($categoryQuery)){

		?>

		<div @click="categoryClick" class="category" v-bind:data-category="<?php echo $category['id']; ?>"><?php echo $category['name'] ?></div>

		<?php

			}

		?>
		<div id="autorisationForm">
			<div id="notAuthorized" class="Authorized" v-if="root == 0">
				<input type="text" class="autorizedInput" placeholder="Логин:" v-model="login">
				<input type="password" class="autorizedInput" placeholder="Пароль:" v-model="password">
				<div class="button" @click="autorisation">Вход</div>
			</div>
			<div id="Authorized" class="Authorized" v-if="root != 0">
				<h1>{{userName}}</h1>
				<a href="admin.php" class="button">Добавить</a>
				<div class="button" @click="userExit">Выход</div>
			</div>
		</div>
	</div>
</div>