<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware'=>'auth'],function () {
    Route::resource('categories', App\Http\Controllers\CategoryController::class);

    Route::post('events/delete-image', [App\Http\Controllers\EventController::class, 'deleteImage'])
    ->name('events.deleteImage');
    Route::resource('events', App\Http\Controllers\EventController::class);
});
