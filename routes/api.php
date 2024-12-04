<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AuthSocialController;
use App\Http\Controllers\Api\PlaceController;
use App\Http\Controllers\Api\PointController;
use App\Http\Controllers\Api\SeanceController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\EventTypeController;
use App\Http\Controllers\Api\SightController;
use App\Http\Controllers\Api\StatusController;
use App\Http\Controllers\Api\SightTypeController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\FeedbackController;
use App\Http\Controllers\Api\HistoryContentController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\OrganizationController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\OrganizationInviteController;
use App\Http\Controllers\Api\PasswordRecoveryController;
use App\Http\Controllers\Api\AppVersionController;
use Illuminate\Support\Facades\Route;

Route::controller(AppVersionController::class)->group(function () {
    Route::post('app/{platform}/{number}', 'setVersion')->middleware('root');
    Route::get('app', 'getVersion');
});
Route::controller(AuthController::class)->group(function () {
    Route::post('register', 'register')->name('user.register'); // Регистрация
    Route::post('register/guest', 'registerGuest')->name('user.register.guest'); // Регистрация гостя

    Route::post('login', 'login')->name('login');
    Route::post('logout', 'logout')->middleware('auth:sanctum');
    Route::put('admin/reset_password', 'resetPasswordForAdmin')->middleware('auth:sanctum');

    // Методы манипуляций с почтой
    Route::post('verificationEmail', 'generateCodeForEmail')->middleware('auth:sanctum')->name('email.verify.code_generate');
    Route::post('verificationUserEmail', 'verifyEmailForCode')->middleware('auth:sanctum')->name('email.verify.code_verify');
    Route::put('resetEmail', 'resetEmailForCode')->middleware('auth:sanctum')->name('user.edit.email_verify');
    Route::put('users/email', 'editEmailNotVerify')->middleware('auth:sanctum')->name('user.edit.email_not_verify');
});

Route::controller(PointController::class)->group(function () {
    Route::get("users/point", "getForUser")->middleware('auth:sanctum')->name('point.user.get');
    Route::post("users/point", "store")->middleware('auth:sanctum')->name('point.user.store');
    Route::delete("users/point/{id}", "delete")->middleware('auth:sanctum')->name('point.user.delete');
});

Route::controller(UserController::class)->group(function () {
    Route::get('admin/users/', 'listUsers')->middleware('admin'); // Для админ панели(поиск юзера по фильтрам)
    Route::put('admin/users/{id}/', 'updateUsers')->middleware('admin'); // Для админ панели(изменить инфу о юзере)
    Route::delete('admin/users/{id}', 'deleteUsers')->middleware('admin'); //  Для админ панели(удалить юзера)

    Route::get('users', 'getUser')->middleware('auth:sanctum');
    Route::get('users/{id}/social-account', 'getSocialAccountByUserId')->middleware('auth:sanctum');
    Route::post('profile/users', 'updateUser')->middleware('auth:sanctum');
    Route::delete('users', 'deleteForUsers')->middleware('auth:sanctum');

    Route::get('users/{id}/favorite-events', 'getUserFavoriteEventsIds')->middleware('auth:sanctum');
    Route::get('users/{id}/liked-events', 'getUserLikedEventsIds')->middleware('auth:sanctum');
    Route::get('users/{id}/favorite-sights', 'getUserFavoriteSightsIds')->middleware('auth:sanctum');
    Route::get('users/{id}/liked-sights', 'getUserLikedSightsIds')->middleware('auth:sanctum');

    Route::post('users/favorite-event-toggle', 'toggleFavoriteEvent')->middleware('auth:sanctum'); // добавляем убираем в избранное
    Route::post('users/like-event-toggle', 'toggleLikedEvent')->middleware('auth:sanctum'); // добавляем убираем лайк
    Route::post('users/favorite-sight-toggle', 'toggleFavoriteSight')->middleware('auth:sanctum'); // добавляем убираем в избранное
    Route::post('users/like-sight-toggle', 'toggleLikedSight')->middleware('auth:sanctum'); // добавляем убираем лайк

    Route::get('users/name/check/{name}', 'chekUserName');
    Route::get('users/email/check/{email}', 'chekUserEmail');
    Route::get('users/number/check/{number}', 'checkUserNumber');

    Route::post("users/{usr_id}/organizations", "addOrganization")->middleware('auth:sanctum');
    Route::get("users/{usr_id}/organizations", "getOrganizations")->middleware('auth:sanctum');
    Route::get("users/organizations/check", "checkUserHaveOrganizations");

    Route::post("users/agreements/accept", "acceptAgreement");
    Route::get("users/agreements/{agreement_id}/check", "checkAgreement");
});

Route::controller(AuthSocialController::class)->group(function () {
    Route::get('social-auth/yandex', 'yandex')->name("yandex");
    Route::get('social-auth/yandex/redirect', 'yandexRedirect')->name('yandexRedirect');

    Route::post('social-auth/apple', 'callbackApple')->name('auth.social');

    Route::get('social-auth/{provider}', 'index')->name('auth.social');
    Route::get('social-auth/{provider}/callback', 'callback')->name('auth.social.callback');
    Route::post('social-auth/{provider}/callback', 'callback')->name('auth.social.callback');
});

Route::controller(EventController::class)->group(function () {
    Route::post('events/search/text', 'searchForText')->name('event.search.text');
    Route::post('events', 'getEvents')->name('events.get_all'); // Запрос ивентов с фильтрами
    Route::get('events-for-author', 'getEventsForAuthor')->name('events.get_for_author')->middleware('auth:sanctum'); // Запрос ивентa для автора
    Route::post('events/update-vk-likes', 'updateVkLikes')->name('events.update_vk_like'); //для страницы мероприятия
    Route::post('events/set-event-user-liked', 'setEvenUserLiked')->middleware('auth:sanctum'); //для страницы мероприятия
    Route::get('events/{id}', 'show');

    Route::get("events/{id}/history-contents", "getHistoryContent");
    Route::get('events/{id}/organization', 'getOrganizationOfEvent');

    Route::get('events/{id}/check-user-liked', 'checkLiked')->middleware('auth:sanctum'); // Проверяем лайкал ли юзер ивент
    Route::get('events/{id}/check-user-favorite', 'checkFavorite')->middleware('auth:sanctum'); // Проверяем добавил ли юзер в избранное

    Route::post('events/create', 'create')->middleware('auth:sanctum');
    Route::put('updateEvent/{id}/', 'updateEvent')->middleware('moderator');

    Route::get('events/{id}/liked-users', 'getEventUserLikedIds');
    Route::get('events/{id}/favorites-users', 'getEventUserFavoritesIds');
    Route::post('events/{id}/statuses/', 'addStatusToEvent')->middleware('auth:sanctum')->name('event.status');
    Route::post('events/{id}/statuses/cookie', 'addStatusToEvent')->middleware('auth:moonshine')->name('event.status.cookie');
    Route::delete('events/{id}', 'delete')->middleware('auth:sanctum')->name('event.delete');

    Route::get('events/{id}/view', 'addViewEvent')->middleware('auth:sanctum')->name('event.view');
});

Route::controller(PlaceController::class)->group(function () {
    Route::get('places', 'getPlaces'); // Запрос маркеров с фильтрами
    Route::get('places/{id}', 'getPlacesIds'); // Запрос маркера по id
    Route::get('events/{id}/places', 'getPlacesAtEventIds'); // Запрос маркера по id ивента
});

Route::controller(SeanceController::class)->group(function () {
    Route::get('places/{id}/seances', 'getSeancesAtPlaceIds');
});
Route::controller(SightController::class)->group(function () {
    Route::get('sights/search/text', 'searchForText')->name('sight.search.text');
    Route::get('sights', 'getSights'); // Запрос достопримечательностей с фильтрами
    Route::get('sights-for-map', 'getSightsForMap'); // Запрос достопримечательностей с фильтрами для карты
    Route::get('sights-for-card/{id}', 'showForCard'); // Запрос достопримечательностей по id для карты
    Route::get('sights-for-author', 'getSightsForAuthor'); // Запрос места для автора
    //    Route::post('sights/update-vk-likes', 'updateVkLikes'); //для страницы мероприятия
    Route::put('sights/updateSight/{id}', 'updateSight')->middleware('moderator');
    Route::post('sights/set-sight-user-liked', 'setEvenUserLiked')->middleware('auth:sanctum'); //для страницы мероприятия
    Route::get('sights/{id}', 'show');
    Route::get("sights/{id}/events", "getEventsInSight");
    Route::get('sights/{id}/check-user-liked', 'checkLiked')->middleware('auth:sanctum'); // Проверяем лайкал ли юзер ивент
    Route::get('sights/{id}/check-user-favorite', 'checkFavorite')->middleware('auth:sanctum'); // Проверяем добавил ли юзер в избранное
    Route::post('sights/create', 'create')->middleware('auth:sanctum');
    Route::get('sights/{id}/liked-users', 'getSightUserLikedIds')->middleware('auth:sanctum');
    Route::get('sights/{id}/favorites-users', 'getSightUserFavoritesIds')->middleware('auth:sanctum');
    Route::get('sights/{id}/history-contents', "getHistoryContent");
});

Route::controller(CommentController::class)->group(function () {
    Route::get('events/{id}/comments', 'getCommentsForEventIds');
    Route::get('sights/{id}/comments', 'getCommentsForSightIds');
    Route::get('comment/{id}', 'showCommentId');
    Route::put('comment/{id}', 'update')->middleware('auth:sanctum');
    Route::post('comment', 'create')->middleware('auth:sanctum');
    Route::delete('comment/{id}', 'delete')->middleware('auth:sanctum');
});

Route::controller(EventTypeController::class)->group(function () {
    Route::get('event-types', 'getTypes');
    Route::get('event-types/{id}', 'getTypesId');
    Route::post('event-types/{event_id}/{type_id}', 'addTypeEvent')->middleware('moderator');
    Route::put('event-types/{event_id}/{type_id}', 'updateTypeEvent')->middleware('moderator');
    //Route::delete('events/deleteTypeEvent/{event_id}/{type_id}', 'deleteTypeUser')->middleware('');
});

Route::controller(SightTypeController::class)->group(function () {
    Route::get('sight-types', 'getTypes');
    Route::get('sight-types/{id}', 'getTypesId');
    Route::post('sight-types/{sight_id}/{type_id}', 'addTypeSight')->middleware('moderator');
    Route::put('sight-types/{sight_id}/{type_id}', 'updateTypeSight')->middleware('moderator');
    //Route::delete('sights/deleteTypeSight/{sight_id}/{type_id}', 'deleteTypeSight');
});

Route::controller(StatusController::class)->group(function () {
    Route::get('statuses', 'getStatuses');
    Route::get('statuses/{id}', 'getStatusId');
    // Для событий
    Route::post('events/statuses', 'addStatusEvent')->middleware('moderator');
    //Для достопримечательностей
    Route::post('sights/statuses', 'addStatusSight')->middleware('moderator');
});

Route::controller(RoleController::class)->group(function () {
    Route::get('roles', 'allRole')->middleware('moderator');
    Route::get('roles/{id}', 'getRole')->middleware('moderator');
    Route::post('roles', 'addRole')->middleware('root');
    Route::put('roles/{id}', 'updateRole')->middleware('root');
    Route::delete('roles/{id}', 'deleteRole')->middleware('root');

    Route::post('users/role/{user_id}/{role_id}', 'addRoleUser')->middleware('root');
    Route::put('users/role/{user_id}/{role_id}', 'updateRoleUser')->middleware('root');
    Route::delete('users/role/{user_id}/{role_id}', 'deleteRoleUser')->middleware('root');
});

Route::controller(LocationController::class)->group(function () {
    Route::get('location/{id}', 'getLocationsIds');
    Route::get('location/name/{name}', 'getLocationsName');
    Route::get('locations', 'getLocationsAll');
    Route::get('locationWithRegion', 'getLocationsAndRegion');
    Route::get('locations/search/coords', 'searchLocationByCoords');
    Route::get('locations/favorities', "getFavoriteCities");
});

Route::controller(HistoryContentController::class)->group(function () {
    Route::get("history-content", 'getHistoryContent')->middleware('moderator');
    Route::get("history-content/{id}", "getHistoryContentForIds")->middleware('moderator');
    Route::post("history-content", "createHistoryContent")->middleware('auth:sanctum');
    Route::patch("history-content", "acceptHistoryContent")->middleware('moderator');
    Route::get("history-content/{type}/{id}", "getHistoryContentForIdsContent");
});

Route::controller(OrganizationController::class)->group(function () {
    Route::post("organizations", "store");
    Route::post("organizations/{organizationId}/users/{userId}/", "addUserToOrganization")->middleware("orgPerm:add_user");
    Route::get("organizations/users/organizations/", "userOrganizations")->middleware('auth:sanctum');
    Route::post("organizations/{organizationId}/users/{userId}/permissions/{permId}", "addOrDeletePermissionToUser");
    Route::get("organizations/{organizationId}/users/{userId}/permissions/", "getPermissionsOfUser")->middleware("orgPerm:update_permissions");
    Route::delete("organizations/{id}", "delete")->middleware("auth:sanctum")->name('organization.delete');

    Route::get("organizations/{id}", "show");
    Route::get("organizations/", "index");
    Route::get("organizations/{organizationId}/users/", "getUsersOfOrganization");

    Route::get("organizations/{organizationId}/events", "getEvents");

    Route::post("organizations/{organizationId}/transfer/user/{userId}", "organizationTransferUser")->name('organization.transfer.user');
});

Route::controller(OrganizationInviteController::class)->group(function () {
    Route::get("organizations/invite/accept", "acceptInvite")->name("organizationInvite.accept");
    // Route::post("organizations/{organization_id}/permissions/{permission_id}/users/{user_id}", "organizationAddUserPermission");
});

Route::controller(PermissionController::class)->group(function () {
    Route::post("permissions", "store")->middleware('root');
    Route::get("permissions/{id}", "show")->middleware('auth:sanctum');;
    Route::get("permissions/", "index")->middleware('auth:sanctum');;
    Route::patch("permissions/", "update")->middleware('root');
    Route::delete("permissions/{id}", "delete")->middleware('root');
});

Route::controller(FeedbackController::class)->group(function () {
    Route::post("feedback/user", "sendUserFeedback")->name('feedback.user');
});

Route::controller(PasswordRecoveryController::class)->group(function () {
    Route::get("recovery/password", "sendMailRecoveryPasswordUrl");
    Route::post("recovery/password", "recoveryPasswordByCode");
});
