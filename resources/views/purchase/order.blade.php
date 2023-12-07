@extends('layouts.layout')
@section('title', $viewData['title'])
@section('subtitle', $viewData['subtitle'])
@section('conteudo')
    <div class="card">
        <div class="card-header">
            Venda Finalizada
        </div>
        <div class="card-body">
            <div class="alert alert-success" role="alert">
                <h1>Checkout de Pagamento</h1>
                <div class="cho-container" id="wallet_container"></div>
                <input type="hidden" name="pref" id="pref" value="{{ session()->get('pref') }}">
            </div>
        </div>
    </div>

    {{-- AJAX JQUERY SEARCH --}}

    <script src="https://sdk.mercadopago.com/js/v2"></script>
    <script>

    const mp = new MercadoPago('APP_USR-4f55dc1d-3b2f-4f41-96bb-578b28ad37ad');
    const bricksBuilder = mp.bricks();

    mp.bricks().create("wallet", "wallet_container", {
        initialization: {
            preferenceId: $("#pref").val(),
            redirectMode: "modal"
        },
    });

    </script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.theme.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.js"></script>
@endsection
