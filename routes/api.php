<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AuthSocialController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\EventTypeController;
use App\Http\Controllers\Api\SightController;
use App\Http\Controllers\Api\StatusController;
use App\Http\Controllers\Api\SightTypeController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\CommentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::controller(AuthController::class)->group(function() {
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::post('logout/{id}', 'logout')->middleware('auth:sanctum');
});

Route::controller(UserController::class)->group(function() {
    Route::get('listUsers/', 'listUsers')->middleware('auth:sanctum'); // Для админ панели(поиск юзера по фильтрам)
    Route::put('updateUsers/{id}/', 'updateUsers')->middleware('auth:sanctum'); // Для админ панели(изменить инфу о юзере)
    Route::delete('deleteUsers/{id}', 'deleteUsers')->middleware('auth:sanctum'); //  Для админ панели(удалить юзера)
    Route::get('users/{id}', 'getUser');
    Route::get('users/{id}/social-account', 'getSocialAccountByUserId')->middleware('auth:sanctum');
    
    Route::get('users/{id}/favorite-events', 'getUserFavoriteEventsIds')->middleware('auth:sanctum');
    Route::get('users/{id}/liked-events', 'getUserLikedEventsIds')->middleware('auth:sanctum');
    Route::get('users/{id}/favorite-sights', 'getUserFavoriteSightsIds')->middleware('auth:sanctum');
    Route::get('users/{id}/liked-sights', 'getUserLikedSightsIds')->middleware('auth:sanctum');

    Route::post('users/favorite-event-toggle', 'toggleFavoriteEvent')->middleware('auth:sanctum');// добавляем убираем в избранное
    Route::post('users/like-event-toggle', 'toggleLikedEvent')->middleware('auth:sanctum');// добавляем убираем лайк
});

Route::controller(AuthSocialController::class)->group(function() {
    Route::get('social-auth/{provider}', 'index')->name('auth.social');
    Route::get('social-auth/{provider}/callback', 'callback')->name('auth.social.callback');
});

Route::controller(EventController::class)->group(function() {
    Route::get('events', 'getEvents'); // Запрос ивентов с фильтрами
    Route::post('events/update-vk-likes', 'updateVkLikes');//для страницы мероприятия
    Route::post('events/set-event-user-liked', 'setEvenUserLiked')->middleware('auth:sanctum');//для страницы мероприятия
    Route::get('events/{id}', 'show');
    Route::get('events/{id}/check-user-liked', 'checkLiked')->middleware('auth:sanctum');// Проверяем лайкал ли юзер ивент
    Route::get('events/{id}/check-user-favorite', 'checkFavorite')->middleware('auth:sanctum');// Проверяем добавил ли юзер в избранное
    Route::post('events/create', 'create')->middleware('auth:sanctum');
    Route::put('updateEvent/{id}/', 'updateEvent')->middleware('auth:sanctum');
    //Route::delete('events/{id}', 'delete')->middleware('auth:sanctum');
});

Route::controller(SightController::class)->group(function() {
    Route::get('sights', 'getSights'); // Запрос достопримечательностей с фильтрами
    Route::post('sights/update-vk-likes', 'updateVkLikes');//для страницы мероприятия
    Route::put('sights/updateSight/{id}', 'updateSight');
    Route::post('sights/set-sight-user-liked', 'setEvenUserLiked')->middleware('auth:sanctum');//для страницы мероприятия
    Route::get('sights/{id}', 'show');
    Route::get('sights/{id}/check-user-liked', 'checkLiked')->middleware('auth:sanctum');// Проверяем лайкал ли юзер ивент
    Route::get('sights/{id}/check-user-favorite', 'checkFavorite')->middleware('auth:sanctum');// Проверяем добавил ли юзер в избранное
    Route::post('sights/create', 'create')->middleware('auth:sanctum');
});

Route::controller(CommentController::class)->group(function() {
    Route::post('comment/create', 'create')->middleware('auth:sanctum');
    Route::delete('comment/{id}/delete', 'delete')->middleware('auth:sanctum');
});

Route::controller(EventTypeController::class)->group(function() {
    Route::get('event-types', 'getTypes');
    Route::get('events/getTypesId/{id}', 'getTypesId');
    Route::post('events/addTypeEvent/{event_id}/{type_id}', 'addTypeEvent')->middleware('auth:sanctum');
    Route::put('events/updateTypeEvent/{event_id}/{type_id}', 'updateTypeEvent')->middleware('auth:sanctum');
    Route::delete('events/deleteTypeEvent/{event_id}/{type_id}', 'deleteTypeUser')->middleware('auth:sanctum');
});

Route::controller(SightTypeController::class)->group(function() {
    Route::get('sight-types', 'getTypes');
    Route::get('sights/getTypesId/{id}', 'getTypesId');
    Route::post('sights/addTypeSight/{sight_id}/{type_id}', 'addTypeSight');
    Route::put('sights/updateTypeSight/{sight_id}/{type_id}', 'updateTypeSight');
    Route::delete('sights/deleteTypeSight/{sight_id}/{type_id}', 'deleteTypeSight');
});

Route::controller(StatusController::class)->group(function() {
    Route::get('statuses', 'getStatuses');
    Route::get('getStatusId/{id}', 'getStatusId');
    // Для событий
    Route::post('events/addStatusEvent', 'addStatusEvent')->middleware('auth:sanctum');
    Route::delete('events/deleteStatusEvent/{event_id}/{status_id}', 'deleteStatusEvent')->middleware('auth:sanctum');
    //Для достопримечательностей
    Route::post('sights/addStatusSight', 'addStatusSight');
    Route::delete('sights/deleteStatusSight/{sight_id}/{status_id}', 'deleteStatusSight');

});

Route::controller(RoleController::class)->group(function() {
    Route::get('allRole', 'allRole')->middleware('auth:sanctum');
    Route::get('getRole/{id}', 'getRole')->middleware('auth:sanctum');
    Route::post('addRole/', 'addRole')->middleware('auth:sanctum');
    Route::put('updateRole/{id}', 'updateRole')->middleware('auth:sanctum');
    Route::delete('deleteRole/{id}', 'deleteRole')->middleware('auth:sanctum');

    Route::post('addRoleUser/{user_id}/{role_id}', 'addRoleUser')->middleware('auth:sanctum');
    Route::put('updateRoleUser/{user_id}/{role_id}', 'updateRoleUser')->middleware('auth:sanctum');
    Route::delete('deleteRoleUser/{user_id}/{role_id}', 'deleteRoleUser')->middleware('auth:sanctum');
});


