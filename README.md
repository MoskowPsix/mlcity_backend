## Laravel API for cityLife

- PHP 8
- Laravel 9

## Содержание:
1. [Как запустить](#install)
2. [Пример развертывания проекта (deploy) на хостинге](#deploy)
3. [API](#api)
   - [Регистрация и получение токена](#api_register)
   - [Авторизация](#api_login)
   - [Авторизация через соцсети](#api_socialAuth)
   - [Выход](#api_logout)

## <a name="install"><h4>Как запустить:</h4></a>

- Установить **PHP 8+**

- Установить **PostgreSQL**

- Установить **composer**

- Клонировать проект ```git clone https://github.com/refus91/citylife_backend```

- В файле .env установить ключ приложения (**APP_KEY**) и настройки подключения к БД (**DB_CONNECTION, DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME,DB_PASSWORD**)

- В файле .env удалить ```SESSION_DRIVER=file``` и установить 
    ```
    SESSION_DRIVER=cookie 
    SESSION_DOMAIN=localhost 
    SANCTUM_STATEFUL_DOMAINS=localhost
    ```

- В файле .env установить реквизиты приложения Вконтакте и редирект после авторизации 
    ```
    VKONTAKTE_CLIENT_ID=51440111 
    VKONTAKTE_CLIENT_SECRET=PsB4W0SVkFGP1M3I2BAH 
    VKONTAKTE_REDIRECT_URI=http://DOMAIN/social-auth/PROVIDER/callback
    ```
    где ```PROVIDER``` нужная соц сеть, например vkontakte.

- В терминале выполнить команду ```composer update```

- В терминале выполнить команду ```php artisan migrate```

- В терминале выполнить команду ```php artisan key:generate```

- В терминале выполнить команду ```php artisan db:seed``` В результате выполнения команды в БД
будут добавлены: пользователь ```Admin``` с паролем ```Qwerty123```  с email ```123n@mail.ru``` и ролью ```Admin```
(в дальнейшем в панели управления необходимо будет изменить email и пароль); роли пользователей
  ```Admin и Moderator```; типы мероприятий и достопримечательностей

- Открыть новую вкладку в терминале и выполнить команду ```php artisan serve```

- Перейти по указанному адресу

- Для отката всей БД и нового ее заполнения используй команду ```php artisan migrate:refresh --seed```

## <a name="deploy"><h4>Пример развертывания проекта (deploy) на хостинге:</h4></a>
https://www.youtube.com/watch?v=Vle7D38pmDg

## <a name="api"><h4>API:</h4></a>

### Используйте Postman для теста API

### <a name="api_register"><h4>Регистрация и получение токена:</h4></a>

Перед регистрацией необходимо запросить CRSF токен
http://DOMAIN/sanctum/csrf-cookie

- URL: http://DOMAIN/api/register

- Method: POST

- Перейдите на вкладку ``Headers`` и введите ``key => Accept, value => application/json``

- Перейдите на вкладку ``Body`` и выберите ``form-data``

  |                   |  **key**   |             **value**             |
  |-------------------|:---------------------------------:|:---------------------------------:|
  | Введите имя:      |    name    | текст, мин - 3 символа, макс - 50 |
  | Введите email:    |   email    |     уникальный почтовый адрес     |
  | Введите пароль:   |  password  |      текст, мин - 3 символа       |
  | Повторите пароль: | password_confirmation |      текст, мин - 3 символа       |

- Вы получите токен, например, ``1|FLolPZ7eg1LGixtlRR1AE3TTAg2HU9DvDWP7maX3``
- Запомните имя и почтовый адрес, запишите токен - он понадобится для авторизации на других маршутах

Успешный ответ:
```
{
"status": "success",
"message": "Вы успешно зарегистрированы!",
"access_token": "1|zMno0E4VJi9BB6tIhKAjVTHhS5CkvrbLQaHB2Hl7",
"token_type": "Bearer",
 "user": {
        "id": 1,
        "name": "Admin1",
        "email": "123@rambler.ru",
        "email_verified_at": null,
        "created_at": "2023-01-12T16:16:33.000000Z",
        "updated_at": "2023-01-12T16:16:33.000000Z"
    }
}
```
Ответ в случае ошибки (могут быть разными):
```
{
    "message": "Такое значение поля email адрес уже существует.",
    "errors": {
        "email": [
            "Такое значение поля email адрес уже существует."
        ]
    }
}
```

### <a name="api_login"><h4>Авторизация:</h4></a>
Перед авторизацией необходимо запросить CRSF токен
http://DOMAIN/sanctum/csrf-cookie

- URL: http://DOMAIN/api/login

- Method: POST
  Перейдите на вкладку ``Headers`` и введите ``key => Accept, value => application/json``
- Перейдите на вкладку ``Body`` и выберите ``form-data``

  |                 |  **key** |                       **value**                      |
    |-----------------|:--------:|:----------------------------------------------------:|
  | Введите email:  | email    | почтовый адрес, который использовали при регистрации |
  | Введите пароль: | password | пароль, который использовали при регистрации         |


Успешный ответ:
```
{
    "status": "success",
    "message": "Вы успешно авторизовались",
    "access_token": "6|igmhnoW7duePpVqyxBbnG6ZlZokFoXPyVcutaSmq",
    "token_type": "Bearer",
    "user": {
        "id": 1,
        "name": "Admin1",
        "email": "123@rambler.ru",
        "email_verified_at": null,
        "created_at": "2023-01-12T16:16:33.000000Z",
        "updated_at": "2023-01-12T16:16:33.000000Z"
    }
}
```
Ответ в случае ошибки (могут быть разными):
```
{
    "status": "error",
    "message": "Неудачная авторизации"
}
```

### <a name="api_socialAuth"><h4>Авторизация через соц. сети:</h4></a>

URL: http://DOMAIN/social-auth/PROVIDER

где  PROVIDER нужная соц сеть, например vkontakte.
Для подключения нужного провайдера его нужно подключить через команду
- Method: GET

```composer require socialiteproviders/PROVIDER```
 Подробнее смотри на https://socialiteproviders.com/


### <a name="api_logout"><h4>Выход:</h4></a>

- URL: http://DOMAIN/api/logout

- Method: POST

- Перейдите на вкладку ``Headers``

- Введите ``key => Accept, value => application/json``

- Введите токен: ``key => Authorization, value => 'Bearer '.$accessToken``
  должна получится строка вида - ``Bearer 1\|FLolPZ7eg1LGixtlRR1AE3TTAg2HU9DvDWP7maX3``

Успешный ответ:
```
{
    "status": "success",
    "message": "Пользователь успешно вышел"
}
```
Возможный ответ в случае ошибки:
```
{
    "message": "Unauthenticated."
}
```
