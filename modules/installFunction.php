<?php

	if (file_exists('db_connect.php') == true){

		echo 'База данных уже установлена!';
		exit();

	}else{

		$host = $_POST['host'];
		$user = $_POST['user'];
		$password = $_POST['password'];
		$dbName = $_POST['dbName'];
		
		install($host, $user, $password, $dbName);

	}

	function install($host, $user, $password, $dbName){

		// Создание файла db_connect.php

		$fileName = "db_connect.php";

		$db_connectInfo = "<?php $dbInfo = ['host'=> '".$host."', 'user'=> '".$user."', 'password'=> '".$password."', 'db'=> '".$dbName."' ]; $link = mysqli_connect($dbInfo['host'], $dbInfo['user'], $dbInfo['password'], $dbInfo['db']); if ($link == false){ echo 'Ошибка! Не удалось подключиться к базе данных! <br>'; exit(mysqli_error()); } ?>";

		fopen($fileName, 'w');
		fwrite($f_hdl, $db_connectInfo);
		fclose($fileName);

		if (require('db_connect.php') == false){
			unlink($fileName);
			echo 'Не удалось создать файл для соединения с базой данных!';
			exit();
		}

		// Подключение к СУБД

		$link = mysqli_connect($dbInfo['host'], $dbInfo['user'], $dbInfo['password']);

		if($link == false) {
			echo 'Ошибка! Не удалось подключиться к СУБД! <br>';
			exit(mysql_error());
		}else{
			echo "Подключён к СУБД... <br>";
		}

		// Создание новой БД

		if(!mysqli_query($link, 'CREATE DATABASE '.$dbInfo['db'].';')){
			echo "Не удалось создать новую базу данных!!! <br>";
			dbBack($link,$dbInfo);
		}else{

				mysqli_close($link);

				// Подключаемся к БД
				$link = mysqli_connect($dbInfo['host'], $dbInfo['user'], $dbInfo['password'], $dbInfo['db']);
				if ($link == false){
					echo 'Ошибка! Не удалось подключиться к базе данных! <br>';
					dbBack($link,$dbInfo);
				}

				// Установка кодировки
				mysqli_query($link, 'SET NAMES utf8');
				echo 'База данных "'.$dbInfo['db'].'" успешно создана... <br>';

			// Создание таблиц
			// Создание таблицы статей
			$createTableArticle = mysqli_query($link, "CREATE TABLE `newsDB`.`article` ( `id` INT(11) NOT NULL AUTO_INCREMENT, `title` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, `category` INT(11) NOT NULL, `preview` VARCHAR(400) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, `text` TEXT NOT NULL, `datePublication` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, `numberViews` INT(11) NOT NULL, PRIMARY KEY (`id`)) ENGINE = InnoDB;");

			// Создание таблицы администраторов

			$createTableAdmin = mysqli_query($link, "CREATE TABLE `newsDB`.`admin` ( `id` INT(11) NOT NULL AUTO_INCREMENT , `surname` VARCHAR(15) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL , `name` VARCHAR(15) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL , `patronymic` VARCHAR(15) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL , `login` VARCHAR(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL , `password` VARCHAR(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;");

			// Создание таблицы категорий статей

			$createTableCategory = mysqli_query($link, 'CREATE TABLE `newsDB`.`category` ( `id` INT(11) NOT NULL AUTO_INCREMENT , `name` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;');

			if (($createTableArticle == true)&&($createTableAdmin == true)&&($createTableCategory == true)){

				echo "Таблицы успешно созданы... <br>";

				// Создание связи
				if (!mysqli_query($link, 'ALTER TABLE `article` ADD FOREIGN KEY (`category`) REFERENCES `category`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;')){

					dbBack($link, $dbInfo);

				}else{

				// Наполняем первичной информацией
				textInfoEnter($link);

				}

			}else{

				echo "Ошибка! Таблицы не созданы!";

			}

		}

		// Функция для удаления созданной базы данных в случае ошибки, возникшей при её создании

		function dbBack($link, $dbInfo){

			echo "<br> Откат изменений!";
			mysqli_query($link, "DROP DATABASE `".$dbInfo['db']."`");
			exit();

		}

		// Функция для наполнения базы данных тестовой информацией

		function textInfoEnter($link){

			// Создание аккаунта администратора

			$solt = "strokaDlyaUslogneniyaChecha";
			if(!mysqli_query($link, 'INSERT INTO `admin` (`id`, `surname`, `name`, `patronymic`, `login`, `password`) VALUES (NULL, "Иванов", "Иван", "Иванович", "'.md5(md5("admin". $solt)).'", "'.md5(md5("admin". $solt)).'");')){

				echo "Не удалось создать аккаунт администратора!";
				dbBack($link, $dbInfo);

			}else{

				echo "Аккаунт администратора успешно создан... <br>";

			}

			// Добавляем категории

			$categoryArray = ['Политика', 'Спорт', 'Техника', 'Наука', 'Шоу-бизнес'];

			foreach ($categoryArray as $key => $value) {

				mysqli_query($link, 'INSERT INTO `category` (`id`, `name`) VALUES (NULL, "'.$value.'");');

			}

			// Добавление 15 статей

			$i=1;

			for ($i; $i <= 15; $i++) { 
				
				$categoryNumber = rand (1,5);

				if(!mysqli_query($link, "INSERT INTO `article` (`id`, `title`, `category`, `preview`, `text`, `datePublication`, `numberViews`) VALUES (NULL, 'Статья №".$i."', ".$categoryNumber.", 'Nullam fames tempus euismod duis hac pede lacus ac velit tristique egestas eget massa mattis nam parturient aliquam purus sociosqu sollicitudin ullamcorper magnis dictum eros sagittis ultrices hymenaeos dolor class nibh euismod, hymenaeos placerat taciti interdum mi neque. Ullamcorper sed. Pede. Fermentum suspendisse duis sociosqu mattis potenti adipiscing posuere torquent.', 'Pede. Mus nec magna tristique dapibus sit sagittis. Enim commodo mollis nascetur dignissim leo pretium, eu gravida et. Lobortis facilisis, tristique mauris condimentum. Libero donec. Nisl lacinia litora quis pulvinar dictumst vel ridiculus, odio donec cursus dui primis leo gravida elit id quisque molestie nascetur nisi pellentesque dapibus donec bibendum habitasse luctus est sociis nascetur litora convallis turpis in. Magnis arcu. Mi amet adipiscing id. Tortor laoreet iaculis ornare rutrum odio bibendum torquent litora ante non consequat ante natoque gravida taciti. Aenean habitant integer condimentum ante per cum. Mattis fringilla dis purus. Ipsum tincidunt sem aptent ad lacus arcu proin cubilia vestibulum habitant molestie convallis arcu aptent sodales convallis. Nam sociosqu aenean dis. Eros litora. Accumsan molestie consequat Purus viverra bibendum purus. Sagittis commodo orci placerat eu velit vestibulum bibendum curabitur a suscipit et odio. Ullamcorper orci Nostra vitae per dictumst. Est suspendisse dignissim sem nonummy aliquet. Pretium augue quam. Mauris donec. Venenatis auctor vitae ad orci consequat, odio Phasellus neque ad. Mattis integer vulputate. Dictum condimentum pede ligula per amet nostra consectetuer fames eu nec viverra quisque non euismod ac malesuada risus est blandit magna quisque penatibus suspendisse consectetuer laoreet vestibulum, conubia congue. Turpis litora nisl posuere pretium enim, cubilia augue. A. Tempor tristique eget metus lorem et nonummy pharetra nostra nullam aliquam dolor per erat vitae sem ornare mattis. Eget mus penatibus rutrum erat. Commodo class adipiscing nam massa consequat eget volutpat potenti laoreet inceptos ultricies ipsum luctus pulvinar lorem quisque natoque faucibus magna dis nibh torquent, semper, consectetuer metus dis auctor semper taciti metus donec. Sodales integer fringilla id nam suscipit natoque senectus sem pharetra convallis. Faucibus rhoncus imperdiet dignissim per integer ridiculus turpis accumsan sociosqu orci integer Interdum fusce netus Suspendisse facilisi lacus fringilla suspendisse ornare eros fermentum natoque, magnis sagittis venenatis inceptos tortor Non integer ante suspendisse euismod, tristique senectus tempus quisque. Penatibus vulputate litora interdum neque augue amet accumsan fringilla. Tempor risus in montes luctus felis penatibus, mattis sit. Interdum elementum nisl, hymenaeos feugiat semper mattis mollis fermentum nec mollis nonummy maecenas nulla varius vestibulum taciti aptent gravida dictumst lorem facilisis orci auctor class pede metus justo dui nonummy erat, sit. Rhoncus imperdiet interdum ipsum, mollis. Habitant orci nisi habitasse netus ipsum nisi. Consectetuer semper sem consectetuer quis neque aenean purus eleifend posuere ultricies consectetuer netus risus amet diam. Luctus mi aliquam velit sodales morbi commodo ipsum netus bibendum nullam, sapien venenatis duis posuere urna malesuada sodales fusce nibh posuere faucibus dictumst. Non. Per. Commodo tempor condimentum habitasse adipiscing nascetur curae; magnis potenti tincidunt cum cras donec nibh luctus hendrerit nulla phasellus nisi in dictum. Fames habitant erat non sociosqu risus fusce dictumst magnis fusce morbi vivamus parturient morbi inceptos egestas sociis lacus, hac, mauris. Pellentesque elementum. Urna orci sit Hendrerit at egestas dictum ante, malesuada Egestas elit rutrum ornare nibh auctor purus a porta luctus eget odio dis nisi faucibus eget felis primis lorem dolor bibendum semper blandit nam penatibus ornare platea taciti malesuada bibendum massa risus vestibulum. Sapien ultrices elit integer rutrum. Curabitur, rhoncus torquent habitasse urna vivamus arcu adipiscing torquent ultrices tempus per. Convallis sapien et nec rhoncus dis semper hac pretium cras lorem aliquet vestibulum ligula tellus nostra netus lobortis per iaculis. Nonummy parturient non morbi in pulvinar id non nisi at. Ultrices. Vehicula, magna facilisis cursus, magnis, proin, enim pretium varius commodo egestas. Auctor enim rhoncus ultrices condimentum vehicula congue. Lobortis tortor urna vivamus vitae elementum fames mattis platea. Netus lectus diam tristique. Nisl est sapien. In cubilia consequat per amet molestie risus lacus enim nulla dictumst turpis molestie mattis nisl inceptos dictumst primis ac nascetur dolor luctus et mauris dignissim nibh malesuada imperdiet hendrerit scelerisque massa rhoncus elit fames laoreet, semper sapien arcu laoreet. Bibendum. Nec torquent dis. Cubilia purus vitae est et suspendisse ligula in commodo feugiat cum. Vivamus malesuada etiam lobortis aptent fusce. Non consectetuer fermentum hendrerit pellentesque rutrum penatibus aliquam aliquet felis. Gravida. Auctor at Urna ultrices mollis aptent enim proin. Magna cras penatibus nascetur enim sit posuere nisl Egestas, magna quam eleifend. Habitasse nisi cum malesuada nonummy placerat eu convallis in magnis vehicula. Aliquam feugiat a ut Semper, est mus habitasse viverra quam. Ut ultrices tincidunt mi mollis congue diam purus nonummy eu amet maecenas rutrum eros quis leo libero hendrerit et eu class. Nibh euismod fusce inceptos consectetuer congue Integer elementum malesuada in volutpat cursus enim, dui ridiculus, libero iaculis metus pulvinar mattis enim. Litora conubia sollicitudin urna sed est fringilla metus venenatis ad pede magna. Mattis litora posuere ligula pede nunc cubilia ante, non Per turpis hendrerit habitasse hymenaeos interdum odio erat, habitasse quisque risus semper. Donec nullam ac tincidunt accumsan magna posuere cum congue magna sociis elementum. Pretium nullam bibendum aliquam turpis class vivamus felis lorem mauris phasellus tristique. Proin porttitor ante, elementum eu lobortis sapien amet egestas varius maecenas natoque vestibulum. Aliquam blandit torquent dolor neque primis adipiscing aenean nonummy. Sociosqu iaculis, suspendisse quam nascetur nisl interdum maecenas tortor pretium non pellentesque. Eleifend potenti netus varius hac nascetur ligula nascetur Integer class convallis curabitur. Class volutpat penatibus litora ad est eleifend tortor quisque lacinia cum potenti, purus ornare dui auctor. Proin dapibus est iaculis senectus. Dolor lectus nam porttitor nam risus torquent tortor sed ultricies ultricies curae; aliquet, adipiscing vivamus. Magnis accumsan cum accumsan est nonummy gravida hendrerit pretium nullam habitant potenti turpis tellus euismod fringilla nisl vestibulum nascetur interdum litora, ut. Fermentum potenti iaculis adipiscing commodo gravida taciti amet condimentum Euismod urna pretium mi fusce dolor tempus id sed purus dolor dui. Habitant lorem. Congue sem elementum sociis vehicula. Varius, cras augue Massa convallis. Aliquet penatibus aenean sit sed. Ante nascetur nulla hendrerit dictum lobortis. Suscipit taciti. Curabitur ligula iaculis ultrices mi pellentesque per. Malesuada integer. Eleifend eros suscipit ac commodo risus taciti dis platea nascetur sapien aliquam purus augue lorem egestas. Tortor, nunc ultrices Metus quis scelerisque nullam auctor pellentesque duis habitasse blandit taciti curabitur dui odio quis id suspendisse Lobortis gravida netus sagittis pharetra non. Sodales torquent velit cursus etiam nec curae; nullam erat sodales. Integer gravida.', '2020-06-".$i." 12:00', ".rand(0, 1000).");")){

					break;

				}

			}

			if($i != 16){

				echo "Не удалось добавить статьи!";
				dbBack($link, $dbInfo);

			}else{

				echo "Статьи успешно добавлены! <br>Устаовка завершена! <a href='http://news/index.php'>Нажмите для перехода на публичную страницу</a>";

			}

		}
	}

?>