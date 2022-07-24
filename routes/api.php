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
    Route::post('/customer/update', [\App\Http\Controllers\ApiController::class, 'updateCustomer']);
    Route::post('/customer/delete', [\App\Http\Controllers\ApiController::class, 'deleteCustomer']);

    Route::get('/supplier', [\App\Http\Controllers\ApiController::class, 'getSuppliers']);
    Route::get('/supplier/{id}', [\App\Http\Controllers\ApiController::class, 'getSupplier']);
    Route::post('/supplier/store', [\App\Http\Controllers\ApiController::class, 'storeSupplier']);
    Route::post('/supplier/update', [\App\Http\Controllers\ApiController::class, 'updateSupplier']);
    Route::post('/supplier/delete', [\App\Http\Controllers\ApiController::class, 'deleteSupplier']);

    Route::get('/purchase', [\App\Http\Controllers\ApiController::class, 'getPurchases']);
    Route::get('/purchase/{id}', [\App\Http\Controllers\ApiController::class, 'getPurchase']);
    Route::post('/purchase/store', [\App\Http\Controllers\ApiController::class, 'storePurchase']);
    Route::post('/purchase/update', [\App\Http\Controllers\ApiController::class, 'updatePurchase']);
    Route::post('/purchase/delete', [\App\Http\Controllers\ApiController::class, 'deletePurchase']);

    Route::get('/product', [\App\Http\Controllers\ApiController::class, 'getProducts']);
    Route::get('/product/{id}', [\App\Http\Controllers\ApiController::class, 'getProduct']);
    Route::post('/product/store', [\App\Http\Controllers\ApiController::class, 'storeProduct']);
    Route::post('/product/update', [\App\Http\Controllers\ApiController::class, 'updateProduct']);
    Route::post('/product/delete', [\App\Http\Controllers\ApiController::class, 'deleteProduct']);
});
