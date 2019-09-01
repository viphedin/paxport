## Курсы валют

#### Установка

- `config/autoload/local.php.sample` переименовать в `config/autoload/local.php` и заполнить подключение к БД
- загрузить `sql\dump.sql` в БД
- запустить `composer install`


#### Загрузка курсов валют

`php public/index.php daily-currencies`

загружает курсы валют USD, EUR за текущую дату