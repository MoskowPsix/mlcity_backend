<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AuthSocialController;
use App\Http\Controllers\Api\LogApiController;
use App\Http\Controllers\Api\PlaceController;
use App\Http\Controllers\Api\SeanceController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\EventTypeController;
use App\Http\Controllers\Api\SightController;
use App\Http\Controllers\Api\StatusController;
use App\Http\Controllers\Api\SightTypeController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\HistoryContentController;
use App\Http\Controllers\Api\ViewController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\OrganizationController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\OrganizationInviteController;
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
// Route::get('/email/verify', function () {
//     return view('auth.verify-email');
// })->middleware('auth')->name('verification.notice');

// Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
//     $request->fulfill();
//     return redirect('/home');
// })->middleware(['auth', 'signed'])->name('verification.verify');

// Route::post('/email/verification-notification', function (Request $request) {
//     $request->user()->sendEmailVerificationNotification();
//     return back()->with('message', 'Verification link sent!');
// })->middleware(['auth', 'throttle:6,1'])->name('verification.send');


Route::controller(AuthController::class)->group(function() {
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::post('logout', 'logout')->middleware('auth:sanctum');
    Route::put('reset_password', 'resetPassword')->middleware('auth:sanctum');
    Route::put('admin/reset_password', 'resetPasswordForAdmin')->middleware('auth:sanctum');
    Route::post('set_password', 'resetPasswordTokens')->middleware('auth:sanctum');

    // Методы манипуляций с почтой
    Route::post('verificationEmail', 'verificationCodeEmail')->middleware('auth:sanctum');
    Route::post('verificationUserEmail','verificationEmail')->middleware('auth:sanctum');
    Route::put('resetEmail','resetEmail')->middleware('auth:sanctum');

    // Методы манипуляций с телефоном
    Route::post('verificationPhone/{code}', 'verificationCodePhone')->middleware('auth:sanctum');
    Route::post('verificationUserPhone','verificationPhone')->middleware('auth:sanctum');
    Route::put('resetPhone','resetPhone')->middleware('auth:sanctum');
});


Route::controller(UserController::class)->group(function() {
    Route::get('admin/users/', 'listUsers')->middleware('admin'); // Для админ панели(поиск юзера по фильтрам)
    Route::put('admin/users/{id}/', 'updateUsers')->middleware('admin'); // Для админ панели(изменить инфу о юзере)
    Route::delete('admin/users/{id}', 'deleteUsers')->middleware('admin'); //  Для админ панели(удалить юзера)

    Route::get('users', 'getUser')->middleware('auth:sanctum');
    Route::get('users/{id}/social-account', 'getSocialAccountByUserId')->middleware('auth:sanctum');
    Route::post('profile/users','updateUser')->middleware('auth:sanctum');

    Route::get('users/{id}/favorite-events', 'getUserFavoriteEventsIds')->middleware('auth:sanctum');
    Route::get('users/{id}/liked-events', 'getUserLikedEventsIds')->middleware('auth:sanctum');
    Route::get('users/{id}/favorite-sights', 'getUserFavoriteSightsIds')->middleware('auth:sanctum');
    Route::get('users/{id}/liked-sights', 'getUserLikedSightsIds')->middleware('auth:sanctum');

    Route::post('users/favorite-event-toggle', 'toggleFavoriteEvent')->middleware('auth:sanctum');// добавляем убираем в избранное
    Route::post('users/like-event-toggle', 'toggleLikedEvent')->middleware('auth:sanctum');// добавляем убираем лайк
    Route::post('users/favorite-sight-toggle', 'toggleFavoriteSight')->middleware('auth:sanctum');// добавляем убираем в избранное
    Route::post('users/like-sight-toggle', 'toggleLikedSight')->middleware('auth:sanctum');// добавляем убираем лайк

    Route::get('users/name/check/{name}', 'chekUserName');
    Route::get('users/email/check/{email}', 'chekUserEmail');
    Route::get('users/number/check/{number}', 'checkUserNumber');

    Route::post("users/{usr_id}/organizations", "addOrganization")->middleware('auth:sanctum');
    Route::get("users/{usr_id}/organizations", "getOrganizations")->middleware("auth:sanctum");
});

Route::controller(AuthSocialController::class)->group(function() {
    Route::get('social-auth/{provider}', 'index')->name('auth.social');
    Route::get('social-auth/{provider}/callback', 'callback')->name('auth.social.callback');
});

Route::controller(EventController::class)->group(function() {
    Route::get('events', 'getEvents'); // Запрос ивентов с фильтрами
    Route::get('events-for-card', 'showForCard'); // Запрос ивентa для карты
    Route::get('events-for-author', 'getEventsForAuthor'); // Запрос ивентa для автора
    Route::post('events/update-vk-likes', 'updateVkLikes');//для страницы мероприятия
    Route::post('events/set-event-user-liked', 'setEvenUserLiked')->middleware('auth:sanctum');//для страницы мероприятия
    Route::get('events/{id}', 'show');

    Route::get('events/{id}/check-user-liked', 'checkLiked')->middleware('auth:sanctum');// Проверяем лайкал ли юзер ивент
    Route::get('events/{id}/check-user-favorite', 'checkFavorite')->middleware('auth:sanctum');// Проверяем добавил ли юзер в избранное

    Route::post('events/create', 'create')->middleware('auth:sanctum');
    Route::put('updateEvent/{id}/', 'updateEvent')->middleware('moderator');

    Route::get('events/{id}/liked-users', 'getEventUserLikedIds')->middleware('auth:sanctum');
    Route::get('events/{id}/favorites-users', 'getEventUserFavoritesIds')->middleware('auth:sanctum');
    //Route::delete('events/{id}', 'delete')->middleware('auth:sanctum');
});

Route::controller(PlaceController::class)->group(function() {
    Route::get('places', 'getPlaces'); // Запрос маркеров с фильтрами
    Route::get('places/{id}', 'getPlacesIds'); // Запрос маркера по id
    Route::get('events/{id}/places', 'getPlacesAtEventIds'); // Запрос маркера по id ивента
});

Route::controller(SeanceController::class)->group(function() {
    Route::get('places/{id}/seances', 'getSeancesAtPlaceIds');
});
Route::controller(SightController::class)->group(function() {
    Route::get('sights', 'getSights'); // Запрос достопримечательностей с фильтрами
    Route::get('sights-for-map','getSightsForMap'); // Запрос достопримечательностей с фильтрами для карты
    Route::get('sights-for-card/{id}','showForCard'); // Запрос достопримечательностей по id для карты
    Route::get('sights-for-author', 'getSightsForAuthor'); // Запрос места для автора
    Route::post('sights/update-vk-likes', 'updateVkLikes');//для страницы мероприятия
    Route::put('sights/updateSight/{id}', 'updateSight')->middleware('moderator');
    Route::post('sights/set-sight-user-liked', 'setEvenUserLiked')->middleware('auth:sanctum');//для страницы мероприятия
    Route::get('sights/{id}', 'show');
    Route::get('sights/{id}/check-user-liked', 'checkLiked')->middleware('auth:sanctum');// Проверяем лайкал ли юзер ивент
    Route::get('sights/{id}/check-user-favorite', 'checkFavorite')->middleware('auth:sanctum');// Проверяем добавил ли юзер в избранное
    Route::post('sights/create', 'create')->middleware('auth:sanctum');
    Route::get('sights/{id}/liked-users', 'getSightUserLikedIds')->middleware('auth:sanctum');
    Route::get('sights/{id}/favorites-users', 'getSightUserFavoritesIds')->middleware('auth:sanctum');
});

Route::controller(CommentController::class)->group(function() {
    Route::get('events/{id}/comments','getCommentsForEventIds');
    Route::get('sights/{id}/comments','getCommentsForSightIds');
    Route::get('comment/{id}', 'showCommentId');
    Route::put('comment/{id}', 'update')->middleware('auth:sanctum');
    Route::post('comment', 'create')->middleware('auth:sanctum');
    Route::delete('comment/{id}', 'delete')->middleware('auth:sanctum');
});

Route::controller(EventTypeController::class)->group(function() {
    Route::get('event-types', 'getTypes');
    Route::get('event-types/{id}', 'getTypesId');
    Route::post('event-types/{event_id}/{type_id}', 'addTypeEvent')->middleware('moderator');
    Route::put('event-types/{event_id}/{type_id}', 'updateTypeEvent')->middleware('moderator');
    //Route::delete('events/deleteTypeEvent/{event_id}/{type_id}', 'deleteTypeUser')->middleware('');
});

Route::controller(SightTypeController::class)->group(function() {
    Route::get('sight-types', 'getTypes');
    Route::get('sight-types/{id}', 'getTypesId');
    Route::post('sight-types/{sight_id}/{type_id}', 'addTypeSight')->middleware('moderator');
    Route::put('sight-types/{sight_id}/{type_id}', 'updateTypeSight')->middleware('moderator');
    //Route::delete('sights/deleteTypeSight/{sight_id}/{type_id}', 'deleteTypeSight');
});

Route::controller(StatusController::class)->group(function() {
    Route::get('statuses', 'getStatuses');
    Route::get('statuses/{id}', 'getStatusId');
    // Для событий
    Route::post('events/statuses', 'addStatusEvent')->middleware('moderator');
    //Для достопримечательностей
    Route::post('sights/statuses', 'addStatusSight')->middleware('moderator');

});

Route::controller(RoleController::class)->group(function() {
    Route::get('roles', 'allRole')->middleware('moderator');
    Route::get('roles/{id}', 'getRole')->middleware('moderator');
    Route::post('roles', 'addRole')->middleware('root');
    Route::put('roles/{id}', 'updateRole')->middleware('root');
    Route::delete('roles/{id}', 'deleteRole')->middleware('root');

    Route::post('users/role/{user_id}/{role_id}', 'addRoleUser')->middleware('root');
    Route::put('users/role/{user_id}/{role_id}', 'updateRoleUser')->middleware('root');
    Route::delete('users/role/{user_id}/{role_id}', 'deleteRoleUser')->middleware('root');
});

Route::controller(ViewController::class)->group(function() {
    Route::post('view','addView')->middleware('auth:sanctum');
});

Route::controller(LocationController::class)->group(function() {
    Route::get('location/{id}','getLocationsIds');
    Route::get('location/name/{name}','getLocationsName');
    Route::get('locations','getLocationsAll');
    Route::get('locationWithRegion','getLocationsAndRegion');
    Route::get('locations/search/coords' , 'searchLocationByCoords');
});

Route::controller(LogApiController::class)->group(function() {
    Route::get('logs', 'getLogs')->middleware('root');
});

Route::controller(HistoryContentController::class)->group(function() {
    Route::get("history-content", 'getHistoryContent')->middleware('moderator');
    Route::get("history-content/{id}","getHistoryContentForIds")->middleware('moderator');
    Route::post("history-content","createHistoryContent")->middleware('auth:sanctum');
    Route::patch("history-content", "acceptHistoryContent")->middleware('moderator');
});

Route::controller(OrganizationController::class)->group(function (){
    Route::post("organizations", "store");
    Route::post("organizations/{organizationId}/users/{userId}/", "addUserToOrganization");

    Route::get("organizations/{id}", "show");
    Route::get("organizations/", "index");
    Route::get("organizations/{organizationId}/users/", "getUsersOfOrganization");
});

Route::controller(OrganizationInviteController::class)->group(function (){
    Route::get("organizations/invite/accept/{token}","acceptInvite")->name("organizationInvite.accept");
    Route::post("organizations/{organization_id}/permissions/{permission_id}/users/{user_id}", "organizationAddUserPermission");
});

Route::controller(PermissionController::class)->group(function (){
    Route::post("permissions", "store")->middleware('root');
    Route::get("permissions/{id}", "show")->middleware('auth:sanctum');;
    Route::get("permissions/", "index")->middleware('auth:sanctum');;
    Route::patch("permissions/", "update")->middleware('root');
    Route::delete("permissions/{id}", "delete")->middleware('root');
});


