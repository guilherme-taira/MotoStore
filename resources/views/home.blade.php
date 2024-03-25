@extends('layouts.app')
@section('title', $viewData['title'])
@section('conteudo')
    <div class="card-body width-home-page-content">

        @if (session()->get('msg_warning'))
            <div class="alert alert-danger text-center" role="alert">
                {{ session()->get('msg_warning') }}
            </div>
        @endif

        <div id="publico">
            <h5>Canal Publico</h5>
        </div>

        <script>
            var publico = document.getElementById("publico");
            Echo.channel('channel-produto').listen('channel-produto', (e) => {
                publico.innerHTML += "<div class='alert alert-success'>"+ e.mensagem +"</div>";
            });
        </script>

        {{-- DASHBOARD --}}
        <div class="row">
            <div class="col-6 mt-3 mb-1">
                <h5 class="text-uppercase">Métricas de Vendas </h5>
                <p>Dados de vendas e do financeiro</p>
            </div>

            @if (!isset($viewData['mercadolivre']))
                <div class="col-3 mt-3 mb-1">
                    <a
                        href="https://auth.mercadolivre.com.br/authorization?response_type=code&client_id=3029233524869952&redirect_uri=https://melimaximo.com.br/thankspage"><button
                            class="btn btn-warning me-md-2" type="button">Integrar Mercado
                            Livre <i class="bi bi-basket2"></i></button></a>
                </div>
            @else
                <div class="col-3 mt-3 mb-1">
                    <button class="btn btn-success me-md-2" type="button">Integrado em:
                        {{ $viewData['mercadolivre']->created_at }}<i class="bi bi-basket2"></i></button>
                </div>
            @endif

        </div>
        <div class="row">
            <div class="col-xl-6 col-md-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between p-md-1">
                            <div class="d-flex flex-row">
                                <div class="align-self-center">
                                    <i class="bi-cart-check fa-3x me-4"
                                        style="font-size: 2rem; color: rgb(2, 196, 76);"></i>
                                </div>
                                <div>
                                    <h4><a href="{{ route('orders.index') }}">Total
                                            Vendas</a></h4>
                                    <p class="mb-0">Mês atual</p>
                                </div>
                            </div>
                            <div class="align-self-center">
                                <h2 class="h1 mb-0 valueMounth">R$:
                                    {{ number_format($viewData['totalMonth'], 2, ',', '.') }} </h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-md-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between p-md-1">
                            <div class="d-flex flex-row">
                                <div class="align-self-center">
                                    <i class="bi bi-cash-coin fa-3x me-4"
                                        style="font-size: 2rem; color: rgb(221, 245, 7);"></i>
                                </div>
                                <div>
                                    <h4>Total Dia</h4>
                                    <p class="mb-0">Valor total do dia</p>
                                </div>
                            </div>
                            <div class="align-self-center">
                                <h2 class="h1 mb-0 ">R$: {{ number_format($viewData['totalDay'], 2, ',', '.') }}
                                </h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-6 col-md-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between p-md-1">
                            <div class="d-flex flex-row">
                                <div class="align-self-center">
                                    <h2 class="h2 mb-0 me-2">R$: </h2>
                                </div>
                                <div>
                                    <h4>Comissões </h4>
                                    <p class="mb-2">Total a ser Pago em comissões (5%)</p>
                                </div>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-coin fa-1x me-2" style="font-size: 2rem; color: rgb(255, 6, 52);"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-md-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between p-md-1">
                            <div class="d-flex flex-row">
                                <div class="align-self-center">
                                    <h2 class="h1 mb-0 me-4">R$:0,00</h2>
                                </div>
                                <div>
                                    <h4>Total Líquido</h4>
                                    <p class="mb-0">Total Líquido do Mês</p>
                                </div>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-currency-dollar text-success fa-3x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </section>
    </div>
    </div>

    <div class="card">
        <div class="card-header">
            Últimas Vendas
        </div>
        <div class="card-body">
            {{-- ULTIMAS 5 VENDAS --}}
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Vendas Aprovadas</h5>
                </div>

                @foreach ($viewData['orders'] as $order)
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"> <strong> Cliente: </strong> {{ $order->name }} <strong> Número
                                do Pedido: </strong> {{ $order->id }} - <strong>Valor: R$
                                {{ number_format($order->valorVenda, 2, ',', '.') }} </strong> Data do Pedido:
                            {{ $order->created_at }}
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                               <a href="{{route('orders.show',['id' => $order->id])}}"><button class="btn btn-success me-md-2 btn-sm" type="button"><i class="bi bi-eye-fill"></i>
                                    Ver Pedido</button></a>
                            </div>
                        </li>
                    </ul>
                @endforeach
            </div>
        </div>
    </div>
    </div>
@endsection
