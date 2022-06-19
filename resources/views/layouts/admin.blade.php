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
    <title>@yield('title', 'Admin - Online Store')</title>
</head>

<body>
    <div class="row g-0">
        <!-- sidebar -->
        <div class="p-3 col fixed text-dark ">
            <a href="{{ route('admin.index') }}" class="text-dark text-decoration-none">
                <span class="fs-4">Admin Panel</span>
            </a>
            <hr />
            <ul class="nav flex-column">
                <li><a href="{{route('orders.index')}}" class="nav-link text-dark">- Admin - Vendas</a></li>
                <li><a href="{{route('products.index')}}" class="nav-link text-dark">- Admin - Produtos</a></li>
                <li><a href="{{route('payment.index')}}" class="nav-link text-dark">- Admin - Pagamentos</a></li>
                <li>
                <li>
                    <a href="{{ route('stores.index') }}" class="mt-2 btn bg-primary text-white">Go back to the home
                        page</a>
                </li>
            </ul>
        </div>
        <!-- sidebar -->
        <div class="col content-grey">
            <nav class="p-3 shadow text-end">
                <span class="profile-font">Admin</span>
                <img class="img-profile rounded-circle" src="{{ asset('/img/undraw_profile.svg') }}">
            </nav>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
</body>
</html>
