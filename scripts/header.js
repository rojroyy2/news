var appHeader = new Vue({
	el: '#header',
	data: {
		userName: '',
		root: 0,
		login: '',
		password: '',
	},
	methods: {
		// Авторизция
		autorisation(){

			let formData = {
				login: this.login,
				password: this.password
			}

			axios({
				method: 'POST',
				headers: { "X-Requested-With": "XMLHttpRequest" },
				url: '../modules/autorisation.php',
				data: formData
			})
			.then(function(response){

				if (response['data']['status'] == true){

					appHeader.userName = response['data']['userName'];
					appHeader.root = response['data']['root'];

				}else{

					alert('Неверный логин или пароль!');

				}

			})
			.catch(function(error){
				console.log(error);
			});

		},
		// Проверка, авторизован ли пользователь
		autorisationChange(){

			axios({
				method: 'POST',
				headers: { "X-Requested-With": "XMLHttpRequest" },
				url: '../modules/sessionChange.php',
			})
			.then(function(response){

				if (response['data']['root'] != 0){

					appHeader.userName = response['data']['userName'];
					appHeader.root = response['data']['root'];

				}

			})
			.catch(function(error){
				console.log(error);
			});

		},
		// Выход пользователя
		userExit(){

			axios({
				method: 'POST',
				headers: { "X-Requested-With": "XMLHttpRequest" },
				url: '../modules/userExit.php',
			})
			.then(function(response){
				document.location.href = "http://news/index.php";
			})
			.catch(function(error){
				console.log(error);
			});

		},
		// Выбор категории в шапке
		categoryClick(){
			if (document.location.href != "http://news/index.php"){
				document.location.href = 'http://news/index.php?category='+ event.target.dataset.category;
			}else{
				app.categoryClick();
			}
		}
	}
});