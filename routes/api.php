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
    Route::post('logout', 'logout')->middleware('auth:sanctum');
});

Route::controller(UserController::class)->group(function() {
    Route::get('listUsers/', 'listUsers'); // Для админ панели(поиск юзера по фильтрам)
    Route::put('updateUsers/{id}/', 'updateUsers'); // Для админ панели(изменить инфу о юзере)
    Route::delete('deleteUsers/{id}', 'deleteUsers'); //  Для админ панели(удалить юзера)
    Route::get('users/{id}', 'getUser');
    Route::get('users/{id}/social-account', 'getSocialAccountByUserId')->middleware('auth:sanctum');
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
    //Route::put('events/{id}', 'update')->middleware('auth:sanctum');
    //Route::delete('events/{id}', 'delete')->middleware('auth:sanctum');
});

Route::controller(SightController::class)->group(function() {
    Route::get('sights', 'getSights'); // Запрос достопримечательностей с фильтрами
});

Route::controller(EventTypeController::class)->group(function() {
    Route::get('event-types', 'getTypes');
});

Route::controller(SightTypeController::class)->group(function() {
    Route::get('sight-types', 'getTypes');
});

Route::controller(StatusController::class)->group(function() {
    Route::get('statuses', 'getStatuses');
});

Route::controller(RoleController::class)->group(function() {
    Route::get('getRole', 'getRole');
    Route::post('addRole', 'addRole');
    Route::put('updateRole', 'updateRole');
    Route::delete('deleteRole', 'deleteRole');
});


