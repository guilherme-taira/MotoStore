<?php

use App\Events\sendProduct;
use App\Http\Controllers\aliexpress\authController;
use App\Http\Controllers\ApiBlingProductsController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Cart\CartController;
use App\Http\Controllers\Categorias\categorias;
use App\Http\Controllers\email\sendEmail;
use App\Http\Controllers\Firebase\FirebaseService;
use App\Http\Controllers\Kits\kitsController;
use App\Http\Controllers\Marketing\BannerController;
use App\Http\Controllers\MercadoLivre\GetTokenForApi;
use App\Http\Controllers\MercadoPago\Pagamento\MercadoPagoNotification;
use App\Http\Controllers\Orders\orderscontroller;
use App\Http\Controllers\Products\productsController;
use App\Http\Controllers\SalesReportController;
use App\Http\Controllers\Store\StoreController;
use App\Http\Controllers\Usuarios\FornecedorController;
use App\Models\Products;
use App\Models\ShippingNotification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Kreait\Firebase\Firestore;

/*
|--------------------------------------------------------------------------
| API Routes
|-------------------------------------------------------------------------
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->group(function () {
    // GET ROUTES
    route::post('updateItem/{id}',function($id){
        broadcast(new sendProduct($id));
    });

    Route::get('/get-shipping-status/{shipping_id}', function ($shipping_id) {

        return ShippingNotification::where('shipping_id', $shipping_id)->get();
    });

    Route::post('/process-sale', [SalesReportController::class, 'processSale'])->name('sales.process');
    Route::post('/bling/orders', [ApiBlingProductsController::class, 'createOrder'])->name('bling.createOrder');
    Route::get('/productsBling', [ApiBlingProductsController::class, 'index'])->name('productsBling');
    Route::post('/notification',[MercadoPagoNotification::class,'notification']);
    Route::post('/notificationBling',[MercadoPagoNotification::class,'notificationBling']);
    Route::post('/notificationTraking',[MercadoPagoNotification::class,'notificationTraking']);
    Route::post('/notificationTrakingMelhorEnvio',[MercadoPagoNotification::class,'notificationTrakingMelhorEnvio']);
    Route::post('/notificationShopify',[MercadoPagoNotification::class,'notificationShopify']);
    Route::post("/sendEmail",[sendEmail::class,'sendEmail']);
    Route::get('/fornecedores',[FornecedorController::class,'filtrarPorNome']);
    Route::post('trataErroMl',[GetTokenForApi::class,'trataError']);
    Route::get("/getTokenMl",[GetTokenForApi::class,'show']);
    Route::get("/getUserID",[GetTokenForApi::class,'getUserID']);
    Route::post("/getAttributesById",[productsController::class,'getAttributes']);
    Route::post("/tradeCategoria",[productsController::class,'tradeCategoria']);
    Route::post("/tradeCategoriaApi",[productsController::class,'tradeCategoriaApiNew']);
    Route::post('/getHistory',[productsController::class,'getHistory']);
    Route::post('/kitsAddProduct',[productsController::class,'addProduct']);
    Route::get('products', [productsController::class, 'getAllProduct']);
    Route::get('products/search', [productsController::class, 'getAllProductSearch']);
    Route::get('getVisits', [productsController::class, 'getVisits']);
    Route::post('getAttributesForVariations',[productsController::class,'getAttributesForVariations']);
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
    Route::get('getProductsApi',[productsController::class,'getProducts']);
    Route::get('getValueGraphic15days',[StoreController::class,'getValueGraphic15days']);
    Route::get('getValueGraphic6Mounth',[StoreController::class,'getValueGraphic6Mounth']);
    Route::post('deleteFoto',[productsController::class,'destroyFotoS3']);
    Route::post('code',[StoreController::class,'getCode']);
    Route::post('fotoPreview',[productsController::class,'fotoPreview']);
    Route::get('getPedidos',[productsController::class,'getPedidos']);
    Route::get('getPedidosById',[productsController::class,'getPedidosById']);
    Route::post('dataHome',[productsController::class,'dataHome']);
    Route::get('/sales-data', [productsController::class, 'getSalesData']);

    Route::get('/teste2',[productsController::class,'teste']);
    // ROTAS DE API PARA PAGAMENTOS
    Route::get('payment',[CartController::class,'createPayment']);
    Route::get('/produtos', [productsController::class, 'getProdutosPaginados']);
    Route::get('/produtosWithImages', [productsController::class, 'getProdutosPaginadosImages']);
    // ROTAS DE API PARA O APLICATIVO
    Route::post('integrarProdutoviaApi',[productsController::class,'integrarProdutoviaApi']);
    Route::post('produtosIntegradosViaApi',[productsController::class,'produtosIntegradosViaApi']);
    Route::post('produtosIntegradosMLApi',[productsController::class,'produtosIntegradosMLApi']);
    Route::post('EnviarDadosIntegradosMLApi',[productsController::class,'EnviarDadosIntegradosMLApi']);
    Route::get('getVendasApi',[orderscontroller::class,'getVendasApi']);
    Route::get('getOrderjoinApiDespacharApp',[orderscontroller::class,'getOrderjoinApiDespacharApp']);
    Route::post('/loginApi', [LoginController::class, 'loginApi']);
    Route::post('/cadastraUserApi', [LoginController::class, 'cadastraUserApi']);
    Route::post('/financeiro/update-status-envio', [fornecedorController::class, 'updateStatusEnvio']);
    Route::post('/getDataCentralFornecedor',[FornecedorController::class,'getDataCentralFornecedor']);
    Route::post('/getDevolucoesByFornecedor',[FornecedorController::class,'getDevolucoesByFornecedor']);
    Route::post('/getMessageMediation',[FornecedorController::class,'getMessageMediation']);
    Route::post('/setShippingMediation',[FornecedorController::class,'setShippingMediation']);
    Route::post('getSalesLast7Days',[orderscontroller::class,'getSalesLast7Days']);
    Route::post('getSalesLastMont',[orderscontroller::class,'getSalesLastMont']);
    Route::post('saveTokenPhoneAuth',[FirebaseService::class,'saveTokenPhoneAuth']);
    Route::post('getKitsByOwner', [productsController::class, 'getKitsByOwner']);
    Route::post('getInformacoesAdicionais', [productsController::class, 'getInformacoesAdicionais']);
    Route::post('getComposicaoKit', [productsController::class, 'getComposicaoKit']);
    // financeiro::contareceber(Auth::user()->id);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
