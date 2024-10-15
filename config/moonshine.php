<?php

use MoonShine\Exceptions\MoonShineNotFoundException;
use MoonShine\Forms\LoginForm;
use MoonShine\Http\Middleware\Authenticate;
use MoonShine\Http\Middleware\SecurityHeadersMiddleware;
use MoonShine\Models\MoonshineUser;
use MoonShine\MoonShineLayout;
use MoonShine\Pages\ProfilePage;

return [
    'dir' => 'app/MoonShine',
    'namespace' => 'App\MoonShine',

    'title' => env('MOONSHINE_TITLE', 'MoonShine'),
    'logo' => env('MOONSHINE_LOGO'),
    'logo_small' => env('MOONSHINE_LOGO_SMALL'),

    'route' => [
        'domain' => env('MOONSHINE_URL', ''),
        'prefix' => env('MOONSHINE_ROUTE_PREFIX', 'admin'),
        'single_page_prefix' => 'page',
        'index' => 'moonshine.index',
        'middlewares' => [
            SecurityHeadersMiddleware::class,
        ],
        'notFoundHandler' => MoonShineNotFoundException::class,
    ],

    'use_migrations' => true,
    'use_notifications' => true,
    'use_theme_switcher' => true,

    'layout' => MoonShineLayout::class,

    'disk' => 'public',

    'disk_options' => [],

    'cache' => 'file',

    'assets' => [
        'js' => [
            'script_attributes' => [
                'defer',
            ]
        ],
        'css' => [
            'link_attributes' => [
                'rel' => 'stylesheet',
            ]
        ]
    ],

    'forms' => [
        'login' => LoginForm::class
    ],

    'pages' => [
        'dashboard' => App\MoonShine\Pages\Dashboard::class,
        'profile' => ProfilePage::class
    ],

    'model_resources' => [
        'default_with_import' => true,
        'default_with_export' => true,
    ],

    'auth' => [
        'enable' => true,
        'middleware' => Authenticate::class,
        'fields' => [
            'username' => 'email',
            'password' => 'password',
            'name' => 'name',
            'avatar' => 'avatar',
        ],
        'guard' => 'moonshine',
        'guards' => [
            'moonshine' => [
                'driver' => 'session',
                'provider' => 'moonshine',
            ],
        ],
        'providers' => [
            'moonshine' => [
                'driver' => 'eloquent',
                'model' => \App\Models\User::class,
            ],
        ],
        'pipelines' => [],
    ],
    'locales' => [
        'en',
        'ru',
    ],

    'global_search' => [
        // User::class
    ],

    'tinymce' => [
        'file_manager' => false, // or 'laravel-filemanager' prefix for lfm
        'token' => env('MOONSHINE_TINYMCE_TOKEN', ''),
        'version' => env('MOONSHINE_TINYMCE_VERSION', '6'),
    ],

    'socialite' => [
//        'vkontakte' => '/images/vkontakte.png',
        // 'driver' => 'path_to_image_for_button'
    ],
    // config for media manager page
    'media-manager' => [
        // Автоматическое добавление в меню
//        'auto_menu' => false,
        // Корневая директория
        'disk' => config('filesystem.default', 'public'),
        // Разрешенные для загрузки расширения файлов
        'allowed_ext' => 'jpg,jpeg,png,pdf,doc,docx,zip',
        // Вид менеджера по-умолчанию
        'default_view' => 'table',
    ],
    // config for composer viewer page
    'composer-viewer' => [
        // Автоматическое добавление в меню
        'auto_menu' => false,
        // Указать расположение composer'а
        'composer' => '/usr/local/bin/composer', // !! ВАЖНО !!
    ],
    // config for task page
    'scheduling' => [
        // Автоматическое добавление в меню
        'auto_menu' => false,
    ],
    // config for logs page
    'log_viewer' => [
        // Автоматическое добавление в меню
        'auto_menu' => false,
        // Путь до директории с логами
        'path' => storage_path('logs'),
    ],
];
