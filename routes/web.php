<?php

use App\Http\Controllers\admin\adminController;
use App\Http\Controllers\admin\dashbordController;
use App\Http\Controllers\Ajax\getProductsData;
use App\Http\Controllers\Ajax\getUserInfoController;
use App\Http\Controllers\Bancario\BancarioController;
use App\Http\Controllers\Cart\CartController;
use App\Http\Controllers\Fornecedor\fornecedorController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Kits\kitsController as KitsKitsController;
use App\Http\Controllers\kitsController;
use App\Http\Controllers\Logo\logoController;
use App\Http\Controllers\Marketing\BannerAutoKmController;
use App\Http\Controllers\Marketing\BannerController;
use App\Http\Controllers\Marketing\BannerPremiumController;
use App\Http\Controllers\Orders\orderscontroller;
use App\Http\Controllers\Payment\PaymentController;
use App\Http\Controllers\Products\productsController;
use App\Http\Controllers\Report\reportController;
use App\Http\Controllers\Status\StatusController;
use App\Http\Controllers\Store\StoreController;
use App\Http\Controllers\subcategoria\SubCategoriaController;
use App\Http\Controllers\User\UserController;
use App\Http\Middleware\AdminAccess;
use App\Models\Orders;
use App\Models\Products;
use Illuminate\Support\Facades\Auth;
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

Route::resource('store', 'App\Http\Controllers\Store\StoreController')->names('stores');

Route::get('/UpdateNewPayment/{id}',[orderscontroller::class,'UpdateNewPayment'])->name('renovarpagamento');
Route::get('/categorias/{categoryId}',[SubCategoriaController::class,'getProductByCategory'])->name('categoryById');
Route::get('/promocoes',[productsController::class,'GetPromotionProducts'])->name('GetPromotionProducts');
Route::get('/kitspublic',[productsController::class,'GetProductsKits'])->name('GetProductsKits');
// ROTA PREMIUM
Route::get('/produtosPremium',[productsController::class,'GetPremiumProducts'])->name('GetPremiumProducts');
// ROTA AUTOKM
Route::get('/autokm',[productsController::class,'GetAutoKM'])->name('GetAutoKM');
Route::get('/kitspublic',[productsController::class,'GetProductsKits'])->name('GetProductsKits');
Route::get('/lancamentos',[productsController::class,'GetProductsLancamentos'])->name('GetProductsLancamentos');
Route::get('/IntegrarProduto',[productsController::class,'IntegrarProduto'])->name('IntegrarProdutoML');
Route::get('/thankspage',[StoreController::class,'thanks'])->name('thanks');
Route::get('cart', [CartController::class, 'indexCart'])->name('cart.index');
Route::get('cart/delete/{id}', [CartController::class, 'delete'])->name('cart.delete');
Route::get('/contasReceber', [orderscontroller::class, 'areceber'])->name('orders.areceber');
Route::get('productcart/{id}', [productsController::class, 'CartShow'])->name('product.cartshow');
Route::get('/baixarVenda/{id}', [orderscontroller::class, 'baixarvenda'])->name('orders.baixarVenda');
Route::post('cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::post('/user/order', [StoreController::class, 'setUser'])->name('setUser.add');
Route::post('/cadastrarKit',[KitsKitsController::class,'addKit'])->name('kitadd');
Route::post('/IntegrarProduto',[productsController::class,'IntegrarProduto'])->name('IntegrarProduto');
Route::get('/imprimirEtiqueta/{shipping_id}',[orderscontroller::class,'ImprimirEtiqueta'])->name('imprimir');
Route::get('/allProductsByFornecedor',[productsController::class,'todosProdutos'])->name('allProductsByFornecedor');
Route::get('/checkout',[CartController::class,'checkout'])->name('cart.checkout');

Route::get('/cart/status', [CartController::class, 'status'])->name('cart.status');
Route::get('/cart/delete', [CartController::class, 'delete'])->name('cart.delete');
Route::get('/cart/deleteOne/{id}', [CartController::class, 'deleteOneCarrinho'])->name('cart.deleteCarrinho');
// ROUTE AJAX
Route::get('/getInfoProduct', [getProductsData::class, 'infoSearch'])->name('ajax.getInfo');
Route::get('/getInfoUser', [getUserInfoController::class, 'infoSearch'])->name('ajax.getUser');
Route::get('/getOrderUser', [getUserInfoController::class, 'infoOrders'])->name('ajax.getOrders');
// FINALIZANDO O PEDIDO
Route::get('/cart/purchase', [CartController::class, 'purcharse'])->name('cart.purchase');
// ROUTE PDF
Route::get('/createPDF', [reportController::class, 'generatePDF'])->name('generatepdf');
Route::get('/relatorios', [reportController::class, 'generateReporter'])->name('generate');
Route::get('/gerador', [reportController::class, 'generating'])->name('GeradorRelatorio');
Route::get('/geradorProdutos', [reportController::class, 'generatingProduct'])->name('GeradorRelatorioperProduct');
//ROTAS DE SESSAO
Route::get('/setSessionRoute',[KitsKitsController::class,'setSessionRoute'])->name('setSessionRoute');
Route::get('/getProductByName',[KitsKitsController::class,'getProductByName'])->name('getProductByName');
Route::get('/DeleteOrderSessionRoute/{id}',[KitsKitsController::class,'DeleteOrderSessionRoute'])->name('deleteSessionRoute');
Route::get('/adicionarQuantidade/{id}',[KitsKitsController::class,'adicionarQuantidade'])->name('adicionarQuantidade');

// ROTAS DE FILA
Route::get('queueYapay',[PaymentController::class,'getQueueData']);
// ROTAS AUTENTICADAS
Route::middleware('admin')->group(function () {
    Route::middleware('admin_msg')->group(function () {
        Route::resource('bancario','App\Http\Controllers\Bancario\BancarioController')->names('bancario')->parameters(['bancario' => 'id']);
        Route::resource('subcategoria','App\Http\Controllers\subcategoria\SubCategoriaController')->names('subcategorias')->parameters(['subcategorium' => 'id']);
        Route::resource('categorias', 'App\Http\Controllers\Categorias\categorias')->names('categorias')->parameters(['categorias' => 'id']);
        Route::resource('product', 'App\Http\Controllers\Products\productsController')->names('products')->parameters(['product' => 'id']);
        Route::resource('admin', 'App\Http\Controllers\admin\adminController')->names('admin')->parameters(['admin' => 'id']);
        Route::resource('user', 'App\Http\Controllers\User\UserController')->names('user')->parameters(['user' => 'id']);
        Route::resource('dashboard', 'App\Http\Controllers\admin\dashbordController')->names('panel')->parameters(['dashboard' => 'id']);
        Route::resource('payments', 'App\Http\Controllers\Payment\PaymentController')->names('payment')->parameters(['payments' => 'id']);
        Route::resource('orders', 'App\Http\Controllers\Orders\orderscontroller')->names('orders')->parameters(['orders' => 'id']);
        Route::resource('banners', 'App\Http\Controllers\Marketing\BannerController')->names('banner')->parameters(['banners' => 'id']);
        Route::resource('bannersAutokm','App\Http\Controllers\Marketing\BannerAutoKmController')->names('bannersAutokm')->parameters(['banners' => 'id']);
        Route::resource('bannersPremium','App\Http\Controllers\Marketing\BannerPremiumController')->names('bannersPremium')->parameters(['banners' => 'id']);
        Route::resource('logo', 'App\Http\Controllers\Logo\logoController')->names('logos')->parameters(['logo' => 'id']);
        Route::resource('kits','App\Http\Controllers\Kits\kitsController')->names('kits')->parameters(['kits' => 'id']);
        Route::resource('fornecedor', 'App\Http\Controllers\Fornecedor\fornecedorController')->names('fornecedor')->parameters(['fornecedor' => 'id'])->middleware('check_fornecedor');
        Route::resource('status','App\Http\Controllers\Status\StatusController')->names('status')->parameters(['status' => 'id']);
    });
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();
