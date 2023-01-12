## Laravel API for cityLife

- PHP 8
- Laravel 9

### Содержание:
1. [Как запустить](#install)
2. [Пример развертывания проекта (deploy) на хостинге](#deploy)
3. [API](#api)

### <a name="install"><h4>Как запустить:</h4></a>

- Установить **PHP 8+**

- Установить **PostgreSQL**

- Установить **composer**

- Клонировать проект ```git clone https://github.com/refus91/citylife_backend```

- В файле .env установить ключ приложения (**APP_KEY**) и настройки подключения к БД (**DB_CONNECTION, DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME,DB_PASSWORD**)

- В терминале выполнить команду ```php artisan migrate```

- В терминале выполнить команду ```php artisan key:generate```

- В терминале выполнить команду ```php artisan db:seed```

- Открыть новую вкладку в терминале и выполнить команду ```php artisan serve```

- Перейти по указанному адресу

### <a name="deploy"><h4>Пример развертывания проекта (deploy) на хостинге:</h4></a>
https://www.youtube.com/watch?v=Vle7D38pmDg

### <a name="api"><h4>API:</h4></a>

### Используйте Postman для теста API

#### Регистрация и получение токена

- URL: http://127.0.0.1:8000/api/register

- Method: POST

- Перейдите на вкладку Headers и введите key => Accept, value => application/json

- Перейдите на вкладку Body и выберите form-data

  |                   |  **key**   |             **value**             |
  |-------------------|:---------------------------------:|:---------------------------------:|
  | Введите имя:      |    name    | текст, мин - 3 символа, макс - 50 |
  | Введите email:    |   email    |     уникальный почтовый адрес     |
  | Введите пароль:   |  password  |      текст, мин - 3 символа       |
  | Повторите пароль: | password_confirmation |      текст, мин - 3 символа       |

- Вы получите токен, например, ``1|FLolPZ7eg1LGixtlRR1AE3TTAg2HU9DvDWP7maX3``
- Запомните имя и почтовый адрес, запишите токен - он понадобится для авторизации на других маршутах

#### Авторизация

- URL: http://127.0.0.1:8000/api/login

- Method: POST
  Перейдите на вкладку Headers и введите key => Accept, value => application/json
- Перейдите на вкладку Body и выберите form-data

  |                 |  **key** |                       **value**                      |
    |-----------------|:--------:|:----------------------------------------------------:|
  | Введите email:  | email    | почтовый адрес, который использовали при регистрации |
  | Введите имя:    | name     | имя, которое использовали при регистрации            |
  | Введите пароль: | password | пароль, который использовали при регистрации         |
  | Введите токен:  | token    | токен который получили при регистрации               |

#### Выход

- URL: http://127.0.0.1:8000/api/logout

- Method: POST
  Перейдите на вкладку Headers и введите key => Accept, value => application/json
