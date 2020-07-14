window.onload = function(){
	app.newsSelect();
	appHeader.autorisationChange();
};
// Приложение Vue
var app = new Vue({
	el: '#app',
	data: {
		newsList: [],
		pagenator: 1,
		pagenatorMax: 0,
		pagenatorList: [],
		category: 0,
		newsLoadingStatus: ""
	},
	methods: {
		// Загрузка новостей
		newsSelect(){

			this.newsLoadingStatus = "Пожалуйства подождите, идёт загрузка новостей...";

			let param = {
				pagenator: this.pagenator,
				category: this.category
			}

			// Выполнение запроса
			axios({
				method: 'POST',
				headers: { "X-Requested-With": "XMLHttpRequest" },
				url: '../modules/indexNewsSelect.php',
				data: param
			})
			.then(function(response){

				if (response['data']['status'] == true){

					app.pagenatorMax = response['data']['pagenatorMax'];
					app.newsList = response['data']['newsList'];
					
					let i = 1;
					app.pagenatorList = [];

					while (i <= app.pagenatorMax){

						app.pagenatorList.push(i);
						i++;

					}

					app.newsLoadingStatus = "";

				}else{

					app.newsLoadingStatus = "Новостей не найдено.";

				}

			})
			.catch(function(error){
				console.log(error);
			});

		},
		// Выбрать категорию новостей
		categoryClick(){

			this.category = parseInt(event.target.dataset.category);
			this.newsSelect();

		},
		// Переключение страницы со списком новостей
		pagClick(){

			this.pagenator = parseInt(event.target.innerHTML);
			this.newsSelect();

		},
		// Показать все новости
		allNewsClick(){
			this.category = 0;
			this.pagenator = 1;
			this.newsSelect();
		}
	}

});