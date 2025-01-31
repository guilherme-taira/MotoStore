<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Afilidrop</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="{{ asset('/css/styles.css') }} " rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/37.0.1/classic/ckeditor.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <!-- DateRangePicker CSS -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/min/moment.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- jQuery Mask Plugin -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

    @if(Auth::check())
        <meta name="user-id" content="{{ auth()->user()->id }}">
    @endif

    <style>
        body {
            /* padding: 20px; */
        }

        .imagemCs {
            width: 400px;
        }

        .spinner-big {
            width: 6rem;
            height: 6rem;
        }

        .spinner-overlay {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: rgba(0, 0, 0, 0.5);
            /* Cor de fundo semi-transparente */
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            /* Garante que o overlay fique acima de outros elementos */
        }

        /* CSS personalizado para garantir que o modal de progresso fique visível */
        #progressModal {
            z-index: 1055; /* Valor ligeiramente maior que o padrão para Bootstrap modals */
        }

        .nav-link .fa-bell {
        position: absolute;
        font-size: 18px;
        }

        .nav-link .badge {
            position: relative;
            top: -5px;
            right: -10px;
            background-color: red;
            color: white;
            padding: 2px 6px;
            border-radius: 50%;
            font-size: 10px;
        }

        .image-container {
            display: flex;
            flex-wrap: wrap;
        }

        .image-container-preview img {
            width: 100px;
            height: 100px;
            margin: 5px;
            object-fit: cover;
            border: 2px solid #cecece;
        }

        .image-container {
            display: flex;
            flex-wrap: wrap;
        }

        .image-item {
            position: relative;
            margin: 5px;
            display: inline-block;
        }

        .image-item img {
            width: 100px;
            height: 100px;
            object-fit: cover;
        }

        .remove-icon {
            position: absolute;
            top: 5px;
            right: 5px;
            color: white;
            cursor: pointer;
            z-index: 1;
        }

        .dropdown-menu {
            width: 500px; /* Ajusta a largura do dropdown */
            max-height: 400px; /* Define uma altura máxima */
            overflow-y: auto; /* Adiciona rolagem caso tenha muitas notificações */
        }

        .dropdown-menu h6 {
            font-weight: bold;
            color: #333;
        }

        .dropdown-item {
            font-size: 14px;
        }

        .dropdown-item.text-center {
            font-weight: bold;
        }

        .notification-content {
            display: flex;
            align-items: center;
        }

        .notification-image {
            width: 50px;
            height: 100px;
            margin-right: 10px;
        }

        .notification-text {
            display: flex;
            flex-direction: column;
        }

        .notification-title {
            font-weight: bold;
            color: #000;
        }

        .notification-subtitle {
            font-weight: bold;
            color: #348dfa;
        }

        .notification-details {
            font-size: 12px;
            color: #555;
            white-space: normal; /* Permite que o texto quebre em várias linhas */
        }

        @keyframes blink {
            0%, 100% {
                background-color: rgb(255, 50, 50); /* Cor inicial/final */
            }
            50% {
                background-color: rgb(255, 255, 255); /* Cor no meio do piscar */
            }
        }

        .blink-tab {
            animation: blink 1.5s linear infinite; /* Duração de 1s, pisca continuamente */
            border: 1px solid red; /* Borda destacada */
        }



    </style>

</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="{{ route('home') }}">Afilidrop</a>

        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>

        <!-- Navbar-->
        <ul class="navbar-nav ms-auto md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
            <!-- Notification Icon with Dropdown -->
            <li class="nav-item dropdown">
                @if(Auth::check())
                <a class="nav-link" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-bell"></i>
                    <span class="badge bg-danger">{{ count(Auth::user()->unreadNotifications) }}</span> <!-- Número de notificações -->
                </a>
                @endif
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown">
                    <li><h6 class="dropdown-header">Notificações</h6></li>
                    @if(Auth::check())
                    @if(isset(Auth::user()->unreadNotifications))
                        @foreach (Auth::user()->unreadNotifications as $notification)
                            <li>
                                <a class="dropdown-item notification-item"
                                   @if(isset($notification->data['link'])) href="{{ $notification->data['link'] }}"
                                   @elseif(isset($notification->data['orderid'])) href="{{ route('orders.show', ['id' => $notification->data['orderid']]) }}"
                                   @else onclick="marcarComoLido('{{ $notification->id }}')"
                                   @endif>
                                    <div class="notification-content mt-2">
                                        @if(isset($notification->data['id']) && isset($notification->data['image']))
                                            <img src="{!! Storage::disk('s3')->url('produtos/' . $notification->data['id'] . '/' . $notification->data['image']) !!}"
                                                 alt="Produto" class="notification-image" style="width: 30%">
                                        @else
                                            <img src="/default-image.png" alt="Notificação" class="notification-image" style="width: 30%">
                                        @endif

                                        <div class="notification-text">
                                            <span class="notification-title">
                                                @if(isset($notification->data['type']) && $notification->data['type'] == "produto")
                                                    Produto Atualizado <i class="bi bi-chat-left-text-fill"></i>
                                                @else
                                                    Notificação Geral <i class="bi bi-bell-fill"></i>
                                                @endif
                                            </span>

                                            <span class="notification-details">
                                                {{ $notification->data['mensagem'] ?? 'Detalhes não disponíveis.' }}
                                            </span>

                                            @if(isset($notification->data['ml_id']))
                                                <span class="notification-subtitle">
                                                    ID da Plataforma - {{ $notification->data['ml_id'] }}
                                                </span>
                                            @endif

                                            @if(isset($notification->data['oldPrice']) && isset($notification->data['newPrice']))
                                                <span class="notification-subtitle bg-dark text-white px-2">
                                                    <i class="bi bi-coin"></i> De: R${{ $notification->data['oldPrice'] }} ~ Para: R${{ $notification->data['newPrice'] }}
                                                </span>
                                            @endif

                                            <span class="notification-date text-muted d-block mt-1" style="font-size: 0.85em;">
                                                {{ $notification->created_at->format('d/m/Y H:i') }}
                                            </span>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    @endif
                @endif

                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-center" href="{{ route('notifications') }}">Ver todas as notificações</a></li>
                </ul>
            </li>

            <!-- User Icon -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button"
                    data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="#!">Settings</a></li>
                    <li><a class="dropdown-item" href="#!">Activity Log</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                                     document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>


    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Inicio</div>
                        <a class="nav-link" href="{{ route('home') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Painel
                        </a>
                        <a class="nav-link" href="{{ route('orders.index') }}">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-cart-shopping"></i></div>
                            Vendas
                        </a>
                        <div class="sb-sidenav-menu-heading">Serviços</div>
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                            data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                            <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                            Produtos
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne"
                            data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="{{ route('products.index') }}">Catálogo de Produtos</a>
                                <a class="nav-link" href="{{ route('products.exclusivos') }}">Produtos Exclusivos
                                <a class="nav-link" href="{{ route('allProductsByFornecedor') }}">Meus Produtos</a>
                                <a class="nav-link" href="{{ route('kits.index') }}">Meus Kits</a>
                                <a class="nav-link" href="{{ route('categorias.index') }}">Categorias</a>
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="{{ route('subcategorias.index') }}">Subcategorias</a>
                                </nav>
                            </nav>
                        </div>
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                            data-bs-target="#collapsePages" aria-expanded="false" aria-controls="collapsePages">
                            <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                            Integrações
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapsePages" aria-labelledby="headingTwo"
                            data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">
                                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                                    data-bs-target="#pagesCollapseAuth" aria-expanded="false"
                                    aria-controls="pagesCollapseAuth">
                                    Plataformas
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>
                                <div class="collapse" id="pagesCollapseAuth" aria-labelledby="headingOne"
                                    data-bs-parent="#sidenavAccordionPages">
                                    <nav class="sb-sidenav-menu-nested nav">
                                        <a class="nav-link" href="{{ route('integracaoml') }}">Mercado Livre</a>
                                        <a class="nav-link" href="">Shopee</a>
                                        <a class="nav-link" href="{{route('shopify.create')}}">Shopify</a>
                                        <a class="nav-link" href="{{route('bling.index')}}">Bling</a>
                                    </nav>
                                </div>
                                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                                    data-bs-target="#pagesCollapseError" aria-expanded="false"
                                    aria-controls="pagesCollapseError">
                                    Produtos
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>
                                <div class="collapse" id="pagesCollapseError" aria-labelledby="headingOne"
                                    data-bs-parent="#sidenavAccordionPages">
                                    <nav class="sb-sidenav-menu-nested nav">
                                        <a class="nav-link" href="{{ route('integrados') }}">Mercado Livre</a>
                                    </nav>
                                </div>
                            </nav>
                        </div>
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                            data-bs-target="#collapseFrete" aria-expanded="false" aria-controls="collapseFrete">
                            <div class="sb-nav-link-icon"><i class="bi bi-truck"></i></div>
                            Fretes
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseFrete" aria-labelledby="headingOne"
                            data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="{{route('fretes.index')}}">Listar Fretes</a>
                            </nav>
                        </div>
                        <div class="sb-sidenav-menu-heading">Planos</div>
                        <a class="nav-link" href="{{ route('planos') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                            Ver Planos
                        </a>
                        <a class="nav-link" href="{{ route('fornecedor.index') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-chart-money"></i></div>
                            Central Vendedor
                        </a>
                    </div>
                </div>
                @if(Auth::check())
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                     {{Auth::user()->name}}
                </div>
                @endif
            </nav>
        </div>

        <div id="layoutSidenav_content">
            <main>
                @php
                    use App\Models\GlobalMessage;
                    $activeMessages = GlobalMessage::where('start_at', '<=', now())
                                                ->where('end_at', '>=', now())
                                                ->get();
                @endphp

                @if($activeMessages->isNotEmpty())
                    @foreach($activeMessages as $message)
                        <div class="alert alert-info text-center">
                            <strong>{{ $message->title }}</strong><br>
                            {{ $message->content }}
                        </div>
                    @endforeach
                @endif
                {{-- CONTEUDO DO SITE  --}}
                @yield('conteudo')
            </main>
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted text-center">Copyright &copy; Afilidrop {{date('Y')}}</div>
                        {{-- <div>
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms &amp; Conditions</a>
                        </div> --}}
                    </div>
                </div>
            </footer>
        </div>
    </div>


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="{{ asset('js/chart-area-demo.js') }}"></script>
    <script src="{{ asset('js/chart-bar-demo.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
        crossorigin="anonymous"></script>
    <script src="{{ asset('js/datatables-simple-demo.js') }}"></script>

</body>

</html>
