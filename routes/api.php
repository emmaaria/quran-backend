<?php

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

Route::group(['middleware' => 'api'], function ($router) {
    Route::post('login', [\App\Http\Controllers\ApiController::class, 'login']);
    Route::get('/profile', [\App\Http\Controllers\ApiController::class, 'profile']);

    Route::get('/sura', [\App\Http\Controllers\ApiController::class, 'getSuras']);
    Route::get('/sura/{id}', [\App\Http\Controllers\ApiController::class, 'getSura']);
    Route::post('/sura/save', [\App\Http\Controllers\ApiController::class, 'storeSura']);
    Route::post('/sura/update', [\App\Http\Controllers\ApiController::class, 'updateSura']);
    Route::post('/sura/delete', [\App\Http\Controllers\ApiController::class, 'deleteSura']);

    Route::get('/{sura}/chapter', [\App\Http\Controllers\ApiController::class, 'getChapters']);
    Route::get('/chapter/{id}', [\App\Http\Controllers\ApiController::class, 'getChapter']);
    Route::post('/chapter/save', [\App\Http\Controllers\ApiController::class, 'storeChapter']);
    Route::post('/chapter/update', [\App\Http\Controllers\ApiController::class, 'updateChapter']);
    Route::post('/chapter/delete', [\App\Http\Controllers\ApiController::class, 'deleteChapter']);
    Route::post('/frontend/chapters', [\App\Http\Controllers\ApiController::class, 'getChapterBySura']);
    Route::post('/search/chapters', [\App\Http\Controllers\ApiController::class, 'searchChapters']);

    Route::get('/dua', [\App\Http\Controllers\ApiController::class, 'getDuas']);
    Route::get('/dua/{id}', [\App\Http\Controllers\ApiController::class, 'getDua']);
    Route::post('/dua/save', [\App\Http\Controllers\ApiController::class, 'storeDua']);
    Route::post('/dua/update', [\App\Http\Controllers\ApiController::class, 'updateDua']);
    Route::post('/dua/delete', [\App\Http\Controllers\ApiController::class, 'deleteDua']);

    Route::get('/post', [\App\Http\Controllers\ApiController::class, 'getPosts']);
    Route::get('/latest-post', [\App\Http\Controllers\ApiController::class, 'getLatestPosts']);
    Route::get('/post/{id}', [\App\Http\Controllers\ApiController::class, 'getPost']);
    Route::post('/post/save', [\App\Http\Controllers\ApiController::class, 'storePost']);
    Route::post('/post/update', [\App\Http\Controllers\ApiController::class, 'updatePost']);
    Route::post('/post/delete', [\App\Http\Controllers\ApiController::class, 'deletePost']);
});
