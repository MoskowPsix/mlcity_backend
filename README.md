## Laravel API for cityLife

- PHP 8
- Laravel 9

## Содержание:
1. [Как запустить для разработки](#install)
2. [Пример развертывания проекта (deploy) на хостинге](#deploy)
3. [Генерация документаци](#docs)

## <a name="install"><h4>Как запустить:</h4></a>

- Установить **PHP 8+**

- Создать базу **PostgreSQL**

- Установить **composer**

- Клонировать проект ```git clone https://github.com/MoskowPsix/mlcity_backend.git```

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
    VKONTAKTE_SERVICE_KEY=
    VK_TOKEN=
    VK_OWNER_ID=
  ```
    где ```PROVIDER``` нужная соц сеть, например vkontakte.
- В файле .env установить подключение к почтовому сервису:
    ```
    MAIL_MAILER=
    MAIL_HOST=
    MAIL_PORT=
    MAIL_USERNAME=
    MAIL_PASSWORD=
    MAIL_ENCRYPTION=
    MAIL_FROM_ADDRESS=
    ```
-  В файле .env если не используется elasticsearch то установить:
    ```
   ELASTICSEARCH_ENABLED=false
   ```
- В файле .env если не используется redis, то установить:
    ```
  CACHE_DRIVER=file
  ```
- В терминале выполнить команду ```composer install```

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
- На хостинге должен быть установлен Docker

- Клонировать проект ```git clone https://github.com/MoskowPsix/mlcity_backend.git```

- Переходим в директорию `docker/`

- Тут создаём `.env` файл с следующим содержимым:
    ```
    APP_NAME=VOKRUG.CITY
    APP_ENV=production
    APP_KEY= # Сгенерируйте ключь локально и вставте его потом сюда
    APP_DEBUG=false
    APP_URL=https://localhost
    FRONT_APP_URL= # Адрес frontend
    
    LOG_CHANNEL=stack
    LOG_DEPRECATIONS_CHANNEL=null
    LOG_LEVEL=debug
    
    DB_CONNECTION=pgsql
    DB_HOST=db
    DB_PORT= # Порт базы данных (На своё усмотрение)
    DB_DATABASE= # Название бд (На своё усмотрение)
    DB_USERNAME= # Имя пользователя бд (На своё усмотрение)
    DB_PASSWORD= # Пароль пользователя бд (На своё усмотрение)
    
    BROADCAST_DRIVER=log
    CACHE_DRIVER=redis
    FILESYSTEM_DISK=local
    QUEUE_CONNECTION=redis
    SESSION_DRIVER=cookie
    SESSION_DOMAIN=localhost
    SANCTUM_STATEFUL_DOMAINS=localhost
    SESSION_LIFETIME=120
    
    MEMCACHED_HOST=127.0.0.1
    
    REDIS_HOST=cache
    REDIS_PASSWORD=
    REDIS_PORT=6379
    
    # Блок сервиса почты (начало)
  
    # Обязательно установить свои данные почтового сервиса
    MAIL_MAILER=
    MAIL_HOST=
    MAIL_PORT=
    MAIL_USERNAME=
    MAIL_PASSWORD=
    MAIL_ENCRYPTION=
    MAIL_FROM_ADDRESS=
    MAIL_FROM_NAME="${APP_NAME}"
    MAIL_EHLO_DOMAIN=null
  
    # Блок сервиса почты (Конец)
    
    AWS_ACCESS_KEY_ID=
    AWS_SECRET_ACCESS_KEY=
    AWS_DEFAULT_REGION=us-east-1
    AWS_BUCKET=
    AWS_USE_PATH_STYLE_ENDPOINT=false
    
    PUSHER_APP_ID=
    PUSHER_APP_KEY=
    PUSHER_APP_SECRET=
    PUSHER_HOST=
    PUSHER_PORT=6001
    PUSHER_SCHEME=http
    PUSHER_APP_CLUSTER=mt1
    
    VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
    VITE_PUSHER_HOST="${PUSHER_HOST}"
    VITE_PUSHER_PORT="${PUSHER_PORT}"
    VITE_PUSHER_SCHEME="${PUSHER_SCHEME}"
    VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
    VITE_YANDEX_APP_KEY="${YANDEX_MAP_API_KEY}"
    VITE_YANDEX_APP_KEY_SUBGEKT="${YANDEX_MAP_API_KEY_SUBGEKT}"
    
    # Блок вк (Начало)
  
    VKONTAKTE_CLIENT_ID=
    VKONTAKTE_CLIENT_SECRET=
    VKONTAKTE_REDIRECT_URI=${APP_URL}/api/social-auth/vkontakte/callback
    VKONTAKTE_SERVICE_KEY=
    
    VK_TOKEN=
    VK_OWNER_ID=
  
    # Блок вк (Конец)
 
    MOONSHINE_TITLE=VOKRUG.CITY # Заголов админки (На своё усмотрение)
    # MOONSHINE_LOGO
    # MOONSHINE_LOGO_SMALL
    MOONSHINE_ROUTE_PREFIX=moon # Маршрут по которому доступна админка (На своё усмотрение)
    
    ELASTICSEARCH_USERNAME=post
    ELASTICSEARCH_PASSWORD=postpost
    ELASTICSEARCH_ENABLED=true
    ELASTICSEARCH_PORT=9200
    ELASTICSEARCH_HOSTS=http://elasticsearch:${ELASTICSEARCH_PORT}
    
    KIBANA_PORT=5602
  ```
  
- Запускаем проект командой `docker compose up --build -d` и проект стартанёт на `443` порту по `https`

- Перезапускаем проект командой `docker compose restart` и проект стартанёт на `443` порту по `https`

- Останавливаем проект командой `docker compose down` и проект стартанёт на `443` порту по `https`

- Запуск команд в контейнере осуществляется через команду `doocker compose exet -it <container-name> <command>`

## <a name="docs"><h4>Сгенерируем и посмотрим api документацию:</h4></a>

- После запуска проекта нужно ввести команду `php artisan scribe:generate`

- Теперь документация api доступна по адресу `docs/`

