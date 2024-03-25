<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/37.0.1/classic/ckeditor.js"></script>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/4.2.0/mdb.min.css" rel="stylesheet" />
    <!-- Styles -->
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
    <!-- MDB -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/4.2.0/mdb.min.css" rel="stylesheet" />
    <link href="{{ asset('/css/bootstrap_css.css') }}" rel="stylesheet">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js" type="text/javascript"></script>
    <script src="{{ asset('mascara/src/jquery.maskMoney.js') }}" type="text/javascript"></script>
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-orange shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        <li class="nav-item dropdown">
                            @guest
                                @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#"
                                    data-mdb-toggle="dropdown" aria-expanded="false">
                                    {{ Auth::user()->name }}
                                </a>
                                <!-- Dropdown menu -->
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                   document.getElementById('logout-form').submit();">
                                            {{ __('Logout') }}
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                            class="d-none">
                                            @csrf
                                        </form>
                                    </li>
                                    <li><a class="dropdown-item" href="{{route('settings')}}"> Configurações </a></li>
                                </ul>
                            </li>
                        @endguest
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="{{ route('stores.index') }}">Home</a>
                        </li>
                        <!-- Navbar dropdown -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                data-mdb-toggle="dropdown" aria-expanded="false">
                                Produtos
                            </a>
                            <!-- Dropdown menu -->
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li>
                                    <a class="dropdown-item" href="{{ route('products.index') }}">Produtos</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('integrados') }}">Integrados</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('kits.index') }}">Kits</a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider" />
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('categorias.index') }}">Categorias</a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                data-mdb-toggle="dropdown" aria-expanded="false">
                                Fornecedores
                            </a>
                            <!-- Dropdown menu -->
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li>
                                    <a class="dropdown-item" href="{{ route('categoriasfornecedor.index') }}">Cadastrar Região <i class="bi bi-person-add"></i></a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider" />
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('fornecedores.index') }}">Cadastrar Fornecedores <i class="bi bi-person-fill-gear"></i></a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('subcategoriafornecedor.index') }}">Cadastrar SubCategorias <i class="bi bi-person-fill-gear"></i></a>
                                </li>
                            </ul>
                        </li>
                        <!--- MENU PRODUTOS FINAL --->

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                data-mdb-toggle="dropdown" aria-expanded="false">
                                Marketing
                            </a>
                            <!-- Dropdown menu -->
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li>
                                    <a class="dropdown-item" href="{{ route('banner.index') }}">Banners</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('logos.index') }}">Logo</a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('orders.index') }}">Vendas</a>
                        </li>
                        <!-- Navbar dropdown -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                data-mdb-toggle="dropdown" aria-expanded="false">
                                Finanças
                            </a>
                            <!-- Dropdown menu -->
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li>
                                    <a class="dropdown-item" href="{{route('fornecedor.index')}}">Página do Fornecedor</a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider" />
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('orders.areceber') }}">Contas a Receber</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('orders.areceber') }}">Contas a Pagar</a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider" />
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#">Relatórios</a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider" />
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('payment.index') }}">Meio de
                                        Pagamento</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('status.index') }}">Status de Pagamento</a>
                                </li>
                            </ul>
                </div>
            </div>
        </nav>

        <div class="g-0 m-5">
            @yield('conteudo')
        </div>

        <!-- MDB -->
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/4.2.0/mdb.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
        </script>
    </div>
</body>

</html>
