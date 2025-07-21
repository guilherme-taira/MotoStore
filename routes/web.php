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
use App\Http\Controllers\ContatoController;
use App\Http\Controllers\email\sendEmail;
use App\Http\Controllers\Firebase\FirebaseService;
use App\Http\Controllers\Fornecedor\fornecedorController;
use App\Http\Controllers\GlobalMessageController;
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
use App\Http\Controllers\NotaFiscalController;
use App\Http\Controllers\Notification\NotificationSistemaController;
use App\Http\Controllers\notificationController;
use App\Http\Controllers\Orders\orderscontroller;
use App\Http\Controllers\PaginationController;
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
use App\Http\Controllers\TikTokOAuthController;
use App\Http\Controllers\TikTokWebhookController;
use App\Http\Controllers\TreinamentosController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Usuarios\FornecedorController as UsuariosFornecedorController;
use App\Http\Middleware\AdminAccess;
use App\Models\financeiro;
use App\Models\Orders;
use App\Models\Products;
use AWS\CRT\HTTP\Request;
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
Route::post('/IntegrarProdutoVariation',[productsController::class,'IntegrarProdutoVariation'])->name('IntegrarProdutoVariation');
Route::get('/imprimirEtiqueta/{shipping_id}',[orderscontroller::class,'ImprimirEtiqueta'])->name('imprimir');
Route::get('/allProductsByFornecedor',[productsController::class,'todosProdutos'])->name('allProductsByFornecedor');
Route::get('/allProductsByFornecedorVariation',[productsController::class,'todosProdutosWithVariation'])->name('allProductsByFornecedorVariation');
Route::get('/checkout',[CartController::class,'checkout'])->name('cart.checkout');
Route::post('/merge-pdfs', [orderscontroller::class, 'mergeLabels'])->name('merge.pdfs');
Route::get('/exclusivos',[productsController::class,'exclusivos'])->name('products.exclusivos')->middleware('restrict.route');
Route::post('/salvar-ordem-imagens', [productsController::class, 'salvarOrdem'])->name('salvar.ordem.imagens');
Route::post('storeWithVariations',[productsController::class,'storeWithVariations'])->name('storeWithVariations');
Route::delete('/kits/deleteProduct/{productId}/{kitId}', [KitsKitsController::class, 'deleteProduct'])
    ->name('kits.deleteProduct');

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


// PAGINA DE DISPONIVEL EM BREVE
Route::get('/breve', function () {
    return view('admin.breve');
})->name('breve');

// Aplicação do Middleware `auth` às rotas protegidas
Route::middleware('auth')->group(function () {
Route::post('/upload-nf/{order_id}', [NotaFiscalController::class, 'uploadNotaFiscal'])->name('upload.nf');
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
Route::post('/updateQuantidadeNoKit/{productId}/{kitId}', [KitsKitsController::class, 'updateQuantidadeNoKit'])->name('updateQuantidadeNoKit');
// ROTA DE EMAIL
Route::get('/sendEmail',[sendEmail::class,'sendEmail']);
// ROTAS DE FILA
Route::get('queueYapay',[PaymentController::class,'getQueueData']);
Route::get('queueMercadoPago',[PaymentController::class,'getQueueDataMercadoPago']);
// ROTAS AUTENTICADAS
});

// Aplicação do Middleware `auth` às rotas protegidas
Route::middleware(['auth', 'check.profile','admin','admin_msg'])->group(function () {

    // Route::middleware('admin_msg')->group(function () {
        Route::post('/aceitar-termos', [UserController::class, 'aceitarTermos'])->name('aceitarTermos');
        Route::post('/financeiro/{id}/update-status-envio', [fornecedorController::class, 'updateStatusEnvio']);
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
        Route::post('/imprimir/contas',[fornecedorController::class,'haImprimir'])->name('imprimir.contas');
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
        Route::resource('contatos','App\Http\Controllers\ContatoController')->names('contatos')->parameters(['contatos' => 'id']);
        // Route::resource('product', 'App\Http\Controllers\Products\productsController')->names('products')->parameters(['product' => 'id'])->middleware('checkCadastro/{id}');
        Route::resource('product', 'App\Http\Controllers\Products\productsController')->names('products')->parameters(['product' => 'id']);
        Route::resource('admin', 'App\Http\Controllers\admin\adminController')->names('admin')->parameters(['admin' => 'id']);
        Route::resource('user', 'App\Http\Controllers\User\UserController')->names('user')->parameters(['user' => 'id']);
        Route::resource('dashboard', 'App\Http\Controllers\admin\dashbordController')->names('panel')->parameters(['dashboard' => 'id']);
        Route::resource('payments', 'App\Http\Controllers\Payment\PaymentController')->names('payment')->parameters(['payments' => 'id']);

        Route::resource('banners', 'App\Http\Controllers\Marketing\BannerController')->names('banner')->parameters(['banners' => 'id']);
        Route::resource('bannersAutokm','App\Http\Controllers\Marketing\BannerAutoKmController')->names('bannersAutokm')->parameters(['banners' => 'id']);
        Route::resource('bannersPremium','App\Http\Controllers\Marketing\BannerPremiumController')->names('bannersPremium')->parameters(['banners' => 'id']);
        Route::resource('logo', 'App\Http\Controllers\Logo\logoController')->names('logos')->parameters(['logo' => 'id']);
        Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
        Route::resource('orders', 'App\Http\Controllers\Orders\orderscontroller')->names('orders')->parameters(['orders' => 'id']);
        Route::resource('product', 'App\Http\Controllers\Products\productsController')
        ->names('products')
        ->parameters(['product' => 'id'])
        ->middleware('check_fornecedor')
        ->except(['edit','update','index','show']);

        Route::resource('kits','App\Http\Controllers\Kits\kitsController')->names('kits')->parameters(['kits' => 'id']);
        Route::resource('fornecedor', 'App\Http\Controllers\Fornecedor\fornecedorController')->names('fornecedor')->parameters(['fornecedor' => 'id'])->middleware('check_fornecedor');
        Route::resource('status','App\Http\Controllers\Status\StatusController')->names('status')->parameters(['status' => 'id']);
        Route::resource('global_messages', 'App\Http\Controllers\GlobalMessageController')->names('global_messages')->parameters(['global_messages' => 'id']);
        Route::get('integracaomeli',[configuracaoController::class,'integracaoMeli'])->name('integracaoml');
        Route::post('/orders/update-informacoes/{id}', [orderscontroller::class, 'updateInformacoes'])->name('orders.updateInformacoes');
        Route::post('/orders/recadastrar', [productsController::class, 'recadastrar'])->name('orders.recadastrar');
        // web.php ou api.php
        Route::get('/meli/categories', [productsController::class, 'getCategories']);
        Route::get('/meli/subcategories/{category}', [productsController::class, 'getCategoryById']);
        Route::get('/meli/subcategories/attributes/{category}', [productsController::class, 'getCategoryAttributeById']);
        Route::get('/treinamentos', [TreinamentosController::class, 'index'])->name('treinamentos.index');
        Route::get('/meli/subcategories/attributes/variation/{category}', [productsController::class, 'getCategoryAttributeByVariation']);
    });

    Route::get('/tiktok/oauth/redirect', [TikTokOAuthController::class, 'redirect'])->name('tiktok.redirect');
    Route::get('/tiktok/oauth/callback', [TikTokOAuthController::class, 'callback'])->name('tiktok.callback');
    Route::post('/tiktok/webhook', [TikTokWebhookController::class, 'handleWebhook']);


    Route::post('/orders/recadastrarExtensao', [productsController::class, 'recadastrarExtensao']);
    Route::get('/conta-integrada', function () {
        return view('layouts.integracao');
    });

    Route::post('/clear-session-messages', function () {
        session()->forget('success');
        session()->forget('error');
        return response()->json(['message' => 'Mensagens removidas com sucesso']);
    })->name('clear.session.messages');

    // Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    // Auth::routes(['register' => true]);
    Auth::routes();
