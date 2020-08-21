## Начало работы <br>

В работе используется <br>
Laravel 5.8 <br>
MySQL 5.7 <br>
Для работы создать базу данных на сервере `json_api`. <br>
При скачивании проекта создать файл `.env` по примеру `.env.example`<br>
Запустить команду `composer install` и миграции с помощью команды `php artisan migration`.<br>
После для демонстрации запустить `php artisan category::add`, а затем
`php artisan product:add`

###Команды для считывания файлов products.json и categories.json <br>

`php artisan product:add` - Считывает и обновляет данные в бд<br>
`php artisan category::add` - Считывает и обновляет данные в бд таблицы категории<br>
Файлы обязательно должны быть расположены в корне проекта.<br><br>
###Маршруты <br>
GET `api/categories` - Просмотр всех категорий<br>
GET `api/categories/{$id}` - Просмотр подкатегорий<br>
POST  `api/categories` - Добавление данных в категории<br>
PUT|PATCH `api/categories/{$id}` - Редактирование категории<br>
DELETE `api/categories/{$id}` - Удаление категории<br><br>
GET `api/categories/{$category}/products` - Отображение всех продуктов<br>
GET `api/categories/{$category}/products/{$id}` - Просмотр опреденного продукта<br>
POST  `api/categories` - Добавление данных о продукте<br>
PUT|PATCH `api/categories/{$category}/products/{$id}` - Редактирование продукта<br>
DELETE `api/categories/{$category}/products/{$id}` - Удаление продукта<br><br>
###Сортировка <br>
GET `api/categories/{$category}/products?sort=price` - Сортирует продукты по цене<br>
GET `api/categories/{$category}/products?sort=-price` - Сортирует продукты по цене по убыванию<br>
GET `api/categories/{$category}/products?sort=date_creation` - Сортирует продукты по дате<br>
GET `api/categories/{$category}/products?sort=-date_creation` - Сортирует продукты по дат<br>
