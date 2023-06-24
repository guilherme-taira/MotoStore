<?php

use App\Http\Controllers\Cart\CartController;
use App\Http\Controllers\Categorias\categorias;
use App\Http\Controllers\email\sendEmail;
use App\Http\Controllers\Marketing\BannerController;
use App\Http\Controllers\MercadoLivre\GetTokenForApi;
use App\Http\Controllers\Products\productsController;
use App\Http\Controllers\Store\StoreController;
use App\Models\Products;
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
    Route::post("/sendEmail",[sendEmail::class,'sendEmail']);
    Route::post('trataErroMl',[GetTokenForApi::class,'trataError']);
    Route::get("/getTokenMl",[GetTokenForApi::class,'show']);
    Route::post("/getAttributesById",[productsController::class,'getAttributes']);
    Route::get('products', [productsController::class, 'getAllProduct']);
    Route::get('getHistoryById',[productsController::class,'getHistoryById']);
    Route::get('product/{id}', [productsController::class,'getProduct']);
    Route::get('categorias', [categorias::class,'getAllCategories']);
    Route::get('categoria/{id}',[categorias::class,'getAllProductByCategorieID']);
    Route::get('banners',[BannerController::class,'getAllBanner']);
    Route::get('tipoanuncio/{id}',[productsController::class,'getAllProductByTipoAnuncio']);
    Route::get('product',[productsController::class,'getParametersByName']);
    Route::get('imageById/{id}',[Products::class,'productWithImageById']);
    Route::get('images/{id}',[productsController::class,'imagesByProduct']);
    Route::get('updateProducts',[productsController::class,'updateProduct']);
    Route::post('code',[StoreController::class,'getCode']);
    // ROTAS DE API PARA PAGAMENTOS
    Route::get('payment',[CartController::class,'createPayment']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
