<?php

use App\Http\Controllers\Categorias\categorias;
use App\Http\Controllers\Marketing\BannerController;
use App\Http\Controllers\Products\productsController;
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

Route::prefix('v1')->group(function () {
    // GET ROUTES
    Route::get('products', [productsController::class, 'getAllProduct']);
    Route::get('product/{id}', [productsController::class,'getProduct']);
    Route::get('categorias', [categorias::class,'getAllCategories']);
    Route::get('categoria/{id}',[categorias::class,'getAllProductByCategorieID']);
    Route::get('banners',[BannerController::class,'getAllBanner']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
