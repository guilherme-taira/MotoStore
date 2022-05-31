<?php

use App\Http\Controllers\admin\adminController;
use App\Http\Controllers\Ajax\getProductsData;
use App\Http\Controllers\Cart\CartController;
use App\Http\Controllers\Products\productsController;
use App\Http\Controllers\Store\StoreController;
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
    return redirect()->route('stores.index');
});

Route::resource('store','App\Http\Controllers\Store\StoreController')->names('stores');
Route::resource('product','App\Http\Controllers\Products\productsController')->names('products')->parameters(['product' => 'id']);
Route::resource('admin','App\Http\Controllers\admin\adminController')->names('admin')->parameters(['admin' => 'id']);

Route::get('cart',[CartController::class,'indexCart'])->name('cart.index');
Route::get('cart/delete/{id}',[CartController::class,'delete'])->name('cart.delete');
Route::post('cart/add/{id}',[CartController::class,'add'])->name('cart.add');

// ROUTE AJAX
Route::get('/getInfoProduct',[getProductsData::class,'infoSearch'])->name('ajax.getInfo');
