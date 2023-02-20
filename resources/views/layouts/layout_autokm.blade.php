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

    <link href="{{ asset('/css/app_autokm.css') }}" rel="stylesheet" />
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
                        <a class="nav-link dropdown-toggle text-white" href="#" data-bs-toggle="dropdown"> Outras Categorias </a>
                        <ul class="dropdown-menu bg-secondary dropdown-menu-right">
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
                                                    <li><a class="dropdown-item" href="{{route('categoryById',['categoryId' => $sub->id]) }}">{{ $sub->name }}</a></li>
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
                    <a class="nav-link text-white" href="{{route('GetAutoKM')}}">Alto KM</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{route('GetProductsKits')}}">Kits</a>
                </li>
                <li class="nav-item bg-danger" style="border-radius: 10px;">
                    <a class="nav-link text-white" href="{{route('GetPremiumProducts')}}">Categoria Premium</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ route('login') }}">Central do Vendedor</a>
                </li>
                @if(!Auth::user())
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ route('login') }}">Entrar Cadastrar</a>
                </li>
                @endif
                </ul>
            </div>
        </div>
    </nav>

    <header class="masthead bg-primary text-white text-center py-4">
        <div class="container d-flex align-items-center flex-column">
            <h2>@yield('subtitle', $viewData['subtitle'])</h2>
        </div>
    </header>
    <!-- header -->
    <div class="container my-4">
        @yield('conteudo')
    </div>
    <!-- footer -->
    <div class="copyright py-2 text-center fixed-bottom text-white">
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
