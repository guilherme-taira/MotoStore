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
    <script src="https://www.mercadopago.com/v2/security.js" view="checkout" output="deviceId"></script>
    <script src="https://sdk.mercadopago.com/js/v2"></script>
    <input type="hidden" id="deviceId">
    <script>
<<<<<<< HEAD

    const mp = new MercadoPago('APP_USR-4f55dc1d-3b2f-4f41-96bb-578b28ad37ad');
    const bricksBuilder = mp.bricks();

    mp.bricks().create("wallet", "wallet_container", {
        initialization: {
            preferenceId: $("#pref").val(),
            redirectMode: "modal"
        },
    });
=======
        const mp = new MercadoPago('APP_USR-2713a3c0-cb97-422f-a30e-b6bcf4799ad6');
        const bricksBuilder = mp.bricks();

        mp.bricks().create("wallet", "wallet_container", {
            initialization: {
                preferenceId: $("#pref").val(),
                redirectMode: "modal",

            },
            payment_methods: {
                excluded_payment_methods: [{
                    id: "visa"
                }],
                installments: 12
            }
>>>>>>> 959122204398de93c1a3ae192206df9993cc8186

    </script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.theme.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.js"></script>
@endsection
