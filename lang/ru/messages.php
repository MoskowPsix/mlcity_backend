<?php
//Различные сообщения
return [
    'register' => [
        'success' => 'Вы успешно зарегистрированы',
    ],
    'login' => [
        'success'           => 'Вы успешно авторизовались',
        'error'             => 'Неудачная авторизации',
        'errorSocialAuth'   => 'Удаленный сервер не отвечает. Попробуйте позже',
    ],
    'logout' => [
        'success' => 'Пользователь успешно вышел',
    ],
    'organization' => [
        'created' => [
            'success' => 'Организация успешно создана',
        ],
        'show' => [
            'success' => 'Организация успешно получена',
            'no_auth' => 'Вы не авторизованы',
        ],
        'index' => [
            'success' => 'Организации успешно получены'
        ],
        'get_user_organizations' => [
            'success' => 'Организации пользователя успешно получены',
        ]
    ],
    'event' => [
        'get_events' => [
            'success' => 'Мероприятия успешно получены'
        ],
        'get_event_for_author' => [
            'success' => 'Мероприятия у автора успешно получены',
        ],
        'show' => [
            'success' => 'Мероприятие успешно получено'
        ],
        'show_for_map' => [
            'success' => 'Мероприятие для карты успешно получено'
        ],
        'create' => [
            'success' => 'Мероприятие успешно создано',
            'error' => 'Во время создания произошла критическая ошибка',
            'error_auth' => 'У пользователя нет организаций',
        ],
        'event_user_liked_ids' => [
            'success' => 'События понравившиеся пользователю получены'
        ],
        'event_user_favorites_ids' => [
            'success' => 'Избранные события пользователю получены',
        ],
        'get_organization_of_event' => [
            'success' => 'Организация мероприятия успешно получена',
        ],
    ],
    'sight' => [
        'show' => [
            'success' => 'Место успешно получено',
        ],
        'create' => [
            'success' => 'Событие успешно создано',
        ],
        'get_sights' => [
            'success' => 'Места успешно получены'
        ],
        'get_sights_for_map' => [
            'success' => 'Места для карты успешно получены',
            'error'   => 'Радиус не может ',
        ],
        'get_sights_for_author' => [
            'success' => 'Места у автора успешно получены',
        ],
        'show_for_card' => [
            'success' => 'Место успешно получено',
        ],
        'check_liked' => [
            'liked' => 'Место понравилось пользователю',
            'not_liked' => 'Место не понравилось пользователю',
        ],
        'check_favorite' => [
            'favorite' => 'Место добавлено в избранное',
            'not_favorite' => 'Место не добавлено в избранное',
        ],
        'get_events_in_sight' => [
            'success' => 'События в месте успешно получены',
        ],
    ],
];
