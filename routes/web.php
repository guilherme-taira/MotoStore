<?php

use App\Http\Controllers\admin\adminController;
use App\Http\Controllers\admin\dashbordController;
use App\Http\Controllers\Ajax\getProductsData;
use App\Http\Controllers\Ajax\getUserInfoController;
use App\Http\Controllers\Cart\CartController;
use App\Http\Controllers\Products\productsController;
use App\Http\Controllers\Store\StoreController;
use App\Http\Controllers\User\UserController;
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
Route::resource('user','App\Http\Controllers\User\UserController')->names('user')->parameters(['user'=>'id']);
Route::resource('dashboard','App\Http\Controllers\admin\dashbordController')->names('panel')->parameters(['dashboard' => 'id']);

Route::get('cart',[CartController::class,'indexCart'])->name('cart.index');
Route::get('cart/delete/{id}',[CartController::class,'delete'])->name('cart.delete');
Route::get('productcart/{id}',[productsController::class,'CartShow'])->name('product.cartshow');
Route::post('cart/add/{id}',[CartController::class,'add'])->name('cart.add');
Route::post('/user/order',[StoreController::class,'setUser'])->name('setUser.add');

Route::get('/cart/status',[CartController::class,'status'])->name('cart.status');
Route::get('/cart/delete',[CartController::class,'delete'])->name('cart.delete');
// ROUTE AJAX
Route::get('/getInfoProduct',[getProductsData::class,'infoSearch'])->name('ajax.getInfo');
Route::get('/getInfoUser',[getUserInfoController::class,'infoSearch'])->name('ajax.getUser');
// FINALIZANDO O PEDIDO
Route::get('/cart/purchase',[CartController::class,'purcharse'])->name('cart.purchase');

