<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

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
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('css/notification.css') }}"></script>

    <style>
        .botao-notificacao {
            /* width: auto; */
            padding: 0px, 10px;
            border: none;
            border-radius: 5px;
            transition: all 0.3s;
            cursor: pointer;
            background: none;
            font-size: 1.4em;
            font-weight: 550;
            font-family: 'Montserrat', sans-serif;
            border: 1px solid #4f4f4f;
        }

        .botao-notificacao:hover {
            background: #000000;
            color: orange;
            font-size: 1.5em;

        }
    </style>

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
            </div>
        </nav>

        <div id="publico">

        </div>

        <script>
            var publico = document.getElementById("publico");
            Echo.channel('channel-produto')
                .listen('.App\\Events\\sendProduct', (e) => {
                    publico.innerHTML += "<div class='alert alert-success text-center'>" + e.data + "</div>";
                });
        </script>


        <div class="g-0 m-5">
            @yield('conteudo')
        </div>

        <!-- MDB -->
        <script src="{{ asset('assets_nft/vendor/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('assets_nft/vendor/bootstrap/js/bootstrap.min.js') }}"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/4.2.0/mdb.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
        </script>
        <script>
            function marcarLido(id){
                return $.ajax({
                    url: "{{ route('marcar.lido') }}",
                    method: 'GET',
                    data: {
                        id: id
                    },
                    success: function (response) {

                    }
                });
            }

            $(function(){
                $('.marcar-lido').click(function (e) {
                    console.log($(this).data('id'));
                    let request = marcarLido($(this).data('id'));
                    request.done(() => {
                        $(this).parents('div.alert').remove();
                    });
                });
            })
        </script>
    </div>
</body>

</html>
