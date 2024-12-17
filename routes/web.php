<?php

use App\Events\sendProduct;
use App\Http\Controllers\admin\adminController;
use App\Http\Controllers\admin\dashbordController;
use App\Http\Controllers\Ajax\getProductsData;
use App\Http\Controllers\Ajax\getUserInfoController;
use App\Http\Controllers\aliexpress\AlieExpressController;
use App\Http\Controllers\aliexpress\implementadorAuthController;
use App\Http\Controllers\ApiBlingProductsController;
use App\Http\Controllers\Bancario\BancarioController;
use App\Http\Controllers\BlingController;
use App\Http\Controllers\Cart\CartController;
use App\Http\Controllers\categoriaFornecedor\categoriasFornecedor;
use App\Http\Controllers\categoriaFornecedor\subcategoriaFornecedor;
use App\Http\Controllers\Configuracao\configuracaoController;
use App\Http\Controllers\email\sendEmail;
use App\Http\Controllers\Fornecedor\fornecedorController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\IntegracaoBlingController;
use App\Http\Controllers\Kits\kitsController as KitsKitsController;
use App\Http\Controllers\kitsController;
use App\Http\Controllers\Logo\logoController;
use App\Http\Controllers\Marketing\BannerAutoKmController;
use App\Http\Controllers\Marketing\BannerController;
use App\Http\Controllers\Marketing\BannerPremiumController;
use App\Http\Controllers\MercadoLivre\CategoryTest;
use App\Http\Controllers\nft\nftcontroller;
use App\Http\Controllers\Notification\NotificationSistemaController;
use App\Http\Controllers\notificationController;
use App\Http\Controllers\Orders\orderscontroller;
use App\Http\Controllers\Payment\PaymentController;
use App\Http\Controllers\planos\planosController;
use App\Http\Controllers\Products\ProductByFornecedor;
use App\Http\Controllers\Products\productsController;
use App\Http\Controllers\Report\reportController;
use App\Http\Controllers\SaiuPraEntrega\PackageController;
use App\Http\Controllers\SaiuPraEntrega\SaiuPraEntregaMainController;
use App\Http\Controllers\ShopifyController;
use App\Http\Controllers\Status\StatusController;
use App\Http\Controllers\Store\StoreController;
use App\Http\Controllers\subcategoria\SubCategoriaController;
use App\Http\Controllers\Test\testController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Usuarios\FornecedorController as UsuariosFornecedorController;
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
    return redirect()->route('home');
});

route::view('/brod','brod');

// Aplicação do Middleware `auth` às rotas protegidas
Route::middleware('auth')->group(function () {
Route::get('/UpdateNewPayment/{id}',[orderscontroller::class,'UpdateNewPayment'])->name('renovarpagamento');
// Route::get('/categorias/{categoryId}',[SubCategoriaController::class,'getProductByCategory'])->name('categoryById');
Route::get('/promocoes',[productsController::class,'GetPromotionProducts'])->name('GetPromotionProducts');
Route::get('/kitspublic',[productsController::class,'GetProductsKits'])->name('GetProductsKits');
// ROTA PREMIUM
Route::get('/produtosPremium',[productsController::class,'GetPremiumProducts'])->name('GetPremiumProducts');
// ROTA AUTOKM
Route::get("/planos",[planosController::class,"index"])->name("planos");
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
Route::post('/package/create', [PackageController::class, 'createPackage']);
Route::post('/IntegrarProduto',[productsController::class,'IntegrarProduto'])->name('IntegrarProduto');
Route::get('/imprimirEtiqueta/{shipping_id}',[orderscontroller::class,'ImprimirEtiqueta'])->name('imprimir');
Route::get('/allProductsByFornecedor',[productsController::class,'todosProdutos'])->name('allProductsByFornecedor');
Route::get('/checkout',[CartController::class,'checkout'])->name('cart.checkout');


// Rota para listar todas as notificações
Route::get('/vernotificacoes', [NotificationSistemaController::class, 'index'])->name('notifications');
// NOTIFICAO MARCAR LIDO
Route::post('/notificacao/marcar-como-lida/{id}', function($id) {
    $notification = Auth::user()->unreadNotifications->where('id', $id)->first();

    if ($notification) {
        $notification->markAsRead();
    }

    return response()->json(['success' => true]);
})->name('notificacao.marcar_como_lida');
});


// Aplicação do Middleware `auth` às rotas protegidas
Route::middleware('auth')->group(function () {
Route::get('/produtosintegrados', [productsController::class, 'integrados'])->name('integrados');
Route::get('/cart/status', [CartController::class, 'status'])->name('cart.status');
Route::get('/cart/delete', [CartController::class, 'delete'])->name('cart.delete');
Route::get('/cart/deleteOne/{id}', [CartController::class, 'deleteOneCarrinho'])->name('cart.deleteCarrinho');
// ROUTE AJAX
Route::get('/getInfoProduct', [getProductsData::class, 'infoSearch'])->name('ajax.getInfo');
Route::get('/getInfoUser', [getUserInfoController::class, 'infoSearch'])->name('ajax.getUser');
Route::get('/getOrderUser', [getUserInfoController::class, 'infoOrders'])->name('ajax.getOrders');
// FINALIZANDO O PEDIDO
Route::get('/cart/purchase', [CartController::class, 'purcharse'])->name('cart.purchase');
Route::get('/cart/orderfinished',[CartController::class,'orderFinished'])->name('purchase.order');
// ROUTE PDF
Route::get('/createPDF', [reportController::class, 'generatePDF'])->name('generatepdf');
Route::get('/relatorios', [reportController::class, 'generateReporter'])->name('generate');
Route::get('/gerador', [reportController::class, 'generating'])->name('GeradorRelatorio');
Route::get('/geradorProdutos', [reportController::class, 'generatingProduct'])->name('GeradorRelatorioperProduct');
//ROTAS DE SESSAO
Route::get('/setSessionRoute',[KitsKitsController::class,'setSessionRoute'])->name('setSessionRoute');
Route::get('/getProductByName',[KitsKitsController::class,'getProductByName'])->name('getProductByName');
Route::get('/DeleteOrderSessionRoute/{id}',[KitsKitsController::class,'DeleteOrderSessionRoute'])->name('deleteSessionRoute');
Route::post('/adicionarQuantidade',[KitsKitsController::class,'adicionarQuantidade'])->name('adicionarQuantidade');
Route::post('/adicionarQuantidadeNoKit',[KitsKitsController::class,'adicionarQuantidadeNoKit'])->name('adicionarQuantidadeNoKit');
// ROTA DE EMAIL
Route::get('/sendEmail',[sendEmail::class,'sendEmail']);
// ROTAS DE FILA
Route::get('queueYapay',[PaymentController::class,'getQueueData']);
Route::get('queueMercadoPago',[PaymentController::class,'getQueueDataMercadoPago']);
// ROTAS AUTENTICADAS
});

// Aplicação do Middleware `auth` às rotas protegidas
Route::middleware('auth')->group(function () {
Route::middleware('admin')->group(function () {
    Route::middleware('admin_msg')->group(function () {
        Route::post('/storeBling', [ApiBlingProductsController::class, 'storeBling'])->name('storeBling');
        Route::get('marcar.lido',[notificationController::class,'readNotification'])->name('marcar.lido');
        Route::get('/test',[testController::class,'teste']);
        Route::get('/feedback',[orderscontroller::class,'feedback']);
        Route::post('/productIntegrado',[ProductByFornecedor::class,'update'])->name('productIntegrado');
        Route::get('/ProductByFornecedor/{id}',[ProductByFornecedor::class,'getProductsByFornecedor'])->name('getAllproductByForncedor');
        Route::get("/categoriasMercadolivre",[CategoryTest::class,'index'])->name("categoryML");
        Route::get("/categorias2",[CategoryTest::class,'categoria']);
        Route::get('configuracao',[configuracaoController::class,'configuracoes'])->name('settings');
        Route::get('endereco',[configuracaoController::class,'address'])->name('address');
        Route::get('addEndereco',[configuracaoController::class,'create'])->name('addEndereco');
        Route::get('editEndereco/{id}',[configuracaoController::class,'edit'])->name('editEndereco');
        Route::get('atualizarEndereco/{id}',[configuracaoController::class,'atualizar'])->name('atualizarEndereco');
        Route::get('editarPerfil',[configuracaoController::class,'editarPerfil'])->name('editProfile');
        Route::delete('deleteEndereco/{id}',[configuracaoController::class,'deletar'])->name('deletarEndereco');
        Route::post('storeEndereco',[configuracaoController::class,'store'])->name('cadastrarEndereco');
        Route::get('/getTokenAliexpress',[AlieExpressController::class,'getToken']);
        Route::resource('bling', IntegracaoBlingController::class);
        Route::get('/blingAutenticate', [BlingController::class, 'authenticate'])->name('blingAutenticate');
        Route::resource('fretes','App\Http\Controllers\SaiuPraEntrega\SaiuPraEntregaMainController')->names('fretes');
        Route::resource('shopify','App\Http\Controllers\ShopifyController')->names('shopify');
        Route::resource('store', 'App\Http\Controllers\Store\StoreController')->names('stores');
        Route::resource('subcategoriafornecedor','App\Http\Controllers\categoriaFornecedor\subcategoriaFornecedor')->names("subcategoriafornecedor");
        Route::resource('categoriasfornecedor','App\Http\Controllers\categoriaFornecedor\categoriasFornecedor')->names('categoriasfornecedor');
        Route::resource('fornecedores','App\Http\Controllers\Usuarios\FornecedorController')->names('fornecedores')->parameters(["fornecedore" => "id"]);
        Route::resource('nfts','App\Http\Controllers\nft\nftcontroller')->names('nfts')->parameters(["nft" => "id"]);
        Route::resource('bancario','App\Http\Controllers\Bancario\BancarioController')->names('bancario')->parameters(['bancario' => 'id']);
        Route::resource('subcategoria','App\Http\Controllers\subcategoria\SubCategoriaController')->names('subcategorias')->parameters(['subcategorium' => 'id']);
        Route::resource('categorias', 'App\Http\Controllers\Categorias\categorias')->names('categorias')->parameters(['categorias' => 'id']);
        // Route::resource('product', 'App\Http\Controllers\Products\productsController')->names('products')->parameters(['product' => 'id'])->middleware('checkCadastro/{id}');
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
        Route::get('integracaomeli',[configuracaoController::class,'integracaoMeli'])->name('integracaoml');
    });
});

Route::post('/clear-session-messages', function () {
    session()->forget('success');
    session()->forget('error');
    return response()->json(['message' => 'Mensagens removidas com sucesso']);
})->name('clear.session.messages');

 Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});

Auth::routes();
