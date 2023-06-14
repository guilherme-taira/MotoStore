<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.3/font/bootstrap-icons.css">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
    <!-- MDB -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/4.2.0/mdb.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.zingchart.com/zingchart.min.js"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/jquery.min.js') }}"></script>

    <link href="{{ asset('/css/app.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('js-plugin-circliful-master/dist/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/owl.theme.default.min.css') }}">

    <title>@yield('title', 'Online Store')</title>


    <script type="text/javascript">
        //	window.addEventListener("resize", function() {
        //		"use strict"; window.location.reload();
        //	});

        document.addEventListener("DOMContentLoaded", function() {
            /////// Prevent closing from click inside dropdown
            document.querySelectorAll('.dropdown-menu').forEach(function(element) {
                element.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            })

            // make it as accordion for smaller screens
            if (window.innerWidth < 992) {

                // close all inner dropdowns when parent is closed
                document.querySelectorAll('.navbar .dropdown').forEach(function(everydropdown) {
                    everydropdown.addEventListener('hidden.bs.dropdown', function() {
                        // after dropdown is hidden, then find all submenus
                        this.querySelectorAll('.submenu').forEach(function(everysubmenu) {
                            // hide every submenu as well
                            everysubmenu.style.display = 'none';
                        });
                    })
                });

                document.querySelectorAll('.dropdown-menu a').forEach(function(element) {
                    element.addEventListener('click', function(e) {

                        let nextEl = this.nextElementSibling;
                        if (nextEl && nextEl.classList.contains('submenu')) {
                            // prevent opening link if link needs to open dropdown
                            e.preventDefault();
                            console.log(nextEl);
                            if (nextEl.style.display == 'block') {
                                nextEl.style.display = 'none';
                            } else {
                                nextEl.style.display = 'block';
                            }

                        }
                    });
                })
            }
            // end if innerWidth

        });
        // DOMContentLoaded  end
    </script>
</head>

<body>
    <!-- header -->
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-primary fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('stores.index') }}"><img class="logo-width"
                    src="{{ Storage::disk('s3')->url('LogoEmbaleme/' .app(App\Models\logo::class)->getLogo()->getId() .'/' .app(App\Models\logo::class)->getLogo()->getImage()) }}"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive"
                aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav">
                    <!-- Navbar dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" data-bs-toggle="dropdown"> Outras
                            Categorias </a>
                        <ul class="dropdown-menu bg-secondary dropdown-menu-right">
                            <li class="bg-warning"><a class="dropdown-item text-dark" href="{{route('nfts.index')}}">NFT's</a></li>
                            <hr>
                            <li class="bg-warning"><a class="dropdown-item text-dark" href="#">Todos Produtos</a></li>
                            @foreach ($viewData['categorias'] as $categoria)
                                @if (count($categoria['subcategory']) > 0)
                                    <li><a class="dropdown-item text-white" href="#">{{ $categoria['nome'] }}</a>
                                        <ul class="submenu submenu-right dropdown-menu">
                                            <div class="div-sub-menu">
                                                <h5>{{ $categoria['nome'] }}</h5>
                                                <hr>
                                                @foreach ($categoria['subcategory'] as $sub)
                                                    <li><a class="dropdown-item"
                                                            href="{{ route('categoryById', ['categoryId' => $sub->id]) }}">{{ $sub->name }}</a>
                                                    </li>
                                                @endforeach
                                            </div>
                                        </ul>
                                    </li>
                                @else
                                    <li><a class="dropdown-item text-white" href="#">{{ $categoria['nome'] }}</a>
                                    </li>
                                @endif
                            @endforeach
                    </li>
                </ul>
                </li>

                <!--- MENU PRODUTOS FINAL --->
                <li class="nav-item active">
                    <a class="nav-link text-white" href="{{ route('GetProductsLancamentos') }}">Lançamentos</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link text-white" href="{{ route('GetPromotionProducts') }}">Promoções</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ route('GetAutoKM') }}">Alto KM</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ route('GetProductsKits') }}">Kits</a>
                </li>
                <li class="nav-item bg-danger" style="border-radius: 10px;">
                    <a class="nav-link text-white" href="{{ route('GetPremiumProducts') }}">Categoria Premium</a>
                </li>
                 <!-- Navbar dropdown -->
                 <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" data-bs-toggle="dropdown"> Fornecedores </a>
                    <ul class="dropdown-menu bg-secondary dropdown-menu-right">
                        @foreach ($viewData['subcategorias'] as $categoria)
                            @if (count($categoria['subcategory']) > 0)
                                <li><a class="dropdown-item text-white" href="#">{{ $categoria['nome'] }}</a>
                                    <ul class="submenu submenu-right dropdown-menu">
                                        <div class="div-sub-menu">
                                            <h5 class="bg-warning">{{ $categoria['nome'] }}</h5>
                                            <hr>
                                            @foreach ($categoria['subcategory'] as $sub)
                                                <li><a class="dropdown-item"
                                                        href="{{ route('getAllproductByForncedor', ['id' => $sub->id]) }}">{{ $sub->name }}</a>
                                                </li>
                                            @endforeach
                                        </div>
                                    </ul>
                                </li>
                            @else
                                <li><a class="dropdown-item text-white" href="#">{{ $categoria['nome'] }}</a>
                                </li>
                            @endif
                        @endforeach
                </li>
            </ul>
            </li>
                @if (!Auth::user())
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('login') }}">Entrar Cadastrar</a>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('login') }}">Olá {{ Auth::user()->name }}</a>
                    </li>
                @endif
                <li>
                    <!-- Cart -->
                    @if (session()->get('carrinho'))
                        <div class="dropdown text-danger">
                            <a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                                <i class="fa fa-shopping-cart fa-2x text-danger"></i>
                            </a>
                            <div class="cart-dropdown">
                                <p>Carrinho {{ Auth::user()->name }}</p>
                                <div class="cart-list">
                                    @foreach (session()->get('carrinho') as $carrinho)
                                        <div class="product-widget">
                                            <div class="product-img">
                                                <img src="{{ Storage::disk('s3')->url('produtos/' . $carrinho['produto'] . '/' . $carrinho['image']) }}"
                                                    alt="">
                                            </div>
                                            <div class="product-body">
                                                <h3 class="product-name"><a href="{{ route('products.show', ['id' => $carrinho['produto']])}}">{{ $carrinho['name'] }}</a>
                                                </h3>
                                                <h4 class="product-price"><span
                                                        class="qty">{{ $carrinho['quantidade'] }}X </span>{{ $carrinho['price'] }}
                                                </h4>
                                            </div>
                                           <a href="{{route('cart.deleteCarrinho',['id' => $carrinho['produto']])}}"><button class="delete"><i class="fa fa-close"></i></button></a>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="cart-summary">
                                    <small>{{count(session()->get('carrinho'))}} Item(s) Selecionados</small>
                                </div>
                                <div class=" col-12">
                                    <a href="{{route('cart.checkout')}}">Checkout <i class="fa fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                        </div>
                    @endif
                    <!-- /Cart -->
                </li>
                </ul>
            </div>
        </div>
    </nav>

    <header class="masthead bg-danger text-white text-center py-2 mt-4">
        <div class="container d-flex align-items-center flex-column mt-4">
        </div>
    </header>
    <!-- header -->
    <div class="container my-4" id="conteudo">
        @yield('conteudo')
    </div>
    <!-- footer -->
    <div class="copyright py-2 text-center fixed-bottom text-white mt-4" id="rodape">
        <div class="container">
            <small>
                Copyright {{ date('Y') }} - <a class="text-reset fw-bold text-decoration-none" target="_blank"
                    href="https://twitter.com/danielgarax">
                    Máximo Company
                </a> - CNPJ: 48.930.389-0001-09</b>
                <br>
                <small>Todos os Direitos Reservados</small>
            </small>
        </div>
    </div>
    <!-- footer -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/4.2.0/mdb.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.3/font/bootstrap-icons.css">
</body>

</html>
