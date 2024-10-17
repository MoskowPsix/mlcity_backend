<?php

use App\Http\Controllers\Api\EventController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Telescope\Http\Middleware\Authorize;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::controller(EventController::class)->group(function () {
    Route::post('events/{id}/statuses/cookie', 'addStatusToEvent')->middleware('auth:session')->name('event.status.cookie');
});
// Route::get('/', function () {
//     return view('welcome');
// })->name('');

// Route::get('/{any}', function () {
//      return view('app');
// })->where('any', '.*');

//Route::get('/{any}', function () {
//     return view('app');
//})->where('any', '.*');

// Route::get('/test', function () {
//      return view('test');
// });
