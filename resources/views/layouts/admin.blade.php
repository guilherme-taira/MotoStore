<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous" />
    <link href="{{ asset('/css/admin.css') }}" rel="stylesheet" />
    <link href="{{ asset('/css/features.css') }}" rel="stylesheet" />
    <link href="{{ asset('/css/offcanvas.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.3/font/bootstrap-icons.css">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
    <!-- MDB -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/4.2.0/mdb.min.css" rel="stylesheet" />
    <title>@yield('title', 'Admin - Online Store')</title>
</head>

<body>
    <div class="row g-0">
        <div class="col content-grey">
            {{-- nav bar start --}}
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <!-- Container wrapper -->
                <div class="container-fluid">
                    <!-- Toggle button -->
                    <button class="navbar-toggler" type="button" data-mdb-toggle="collapse"
                        data-mdb-target="#navbarLeftAlignExample" aria-controls="navbarLeftAlignExample"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fas fa-bars"></i>
                    </button>

                    <!-- Collapsible wrapper -->
                    <div class="collapse navbar-collapse" id="navbarLeftAlignExample">
                        <!-- Left links -->
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="{{route('stores.index')}}">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('products.index')}}">Produtos</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('orders.index')}}">Vendas</a>
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
                                        <a class="dropdown-item" href="{{route('orders.areceber')}}">Contas a Receber</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{route('orders.areceber')}}">Contas a Pagar</a>
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
                                        <a class="dropdown-item" href="{{route('payment.index')}}">Meio de Pagamento</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                        <!-- Left links -->
                    </div>
                    <!-- Collapsible wrapper -->
                </div>
                <!-- Container wrapper -->
            </nav>
            {{-- nav bar end --}}
            <div class="g-0 m-5">
                @yield('conteudo')
            </div>
        </div>
    </div>
    <!-- footer -->
    <div class="copyright py-4 text-center text-white">
        <div class="container">
            <small>
                Copyright - <a class="text-reset fw-bold text-decoration-none" target="_blank"
                    href="https://twitter.com/danielgarax">
                    Guilherme Taira
                </a> - <b>Taira Solution</b>
            </small>
        </div>
    </div>
    <!-- footer -->
    <!-- MDB -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/4.2.0/mdb.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
</body>

</html>
