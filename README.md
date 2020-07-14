# news
Лента новостей
Перед установкой базы данных необходимо зайти в файл 'config.php' и ввести информацию для входа на сервер СУБД (использовал MySQL 5.6).
Далее необходимо перейти в браузере файл 'install.php', этот скрипт создаст новую базу данных и наполнит её первоначальной тестовой информацией:
	Таблица "Category" - список катгорий новостей;
	Таблица "Article" - таблица с новостями, установщик вставит в неё 15 записей, так же в "styles/newsImg/" содержатся 15 изображений для данных новостей;
	Таблица "admin" - содержит в себе запись с информацией дя входа администратора, котоый может добавлять новые новости (Логин - "admin", Пароль - "admin").
Публиный интерфейс:
	index.php - выводит ленту новостей (присутствует пагенация, вывод по 5 новостей, выведены изображения даты публикации, колчество посмотров, категории (можно переключаться между категориями из шапки сайта, или кликнув на название категории, непосредственно на новости)). Так же на данной странице есть возможность авторизоваться и далее для администратора пройти на страницу для добавления новости. С этой страницы можно перейти на стрницу "news.php", на которой уже можно прочитать полный текст новости.
Интерфейс администратора:
	admin.php - позволяет добавлять новости. Попасть на данную стрницу может только после авторизации, кликнув на кнопку в "шапке" сайта (кнопка "Добавить").
	На данной страниценеобходимо ввести:
		Текст новости;
		Анонс;
		Название;
		Выбрать категорию в выпадающем меню;
		Прикрепит изображение в формате "*.jpeg";
		Клинуть на кнопку "Опубликовать". После чего данные будут отправлены на сервер для добавления новости, а пользователю будет предложено добавить ещё новость или же перейти к просмору только что опубликованной новости.
В ходе разработки были использовны следующие технлогии:
	PHP, HTML, CSS, JS (Vue JS), СУБД - MySQL 5.6.
Время затраченное на разрабоку - 12 часов.