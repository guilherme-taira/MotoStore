@extends('layouts.admin')
@section('title', $viewData['title'])
@section('conteudo')
    <div class="card-body">
        {{-- DASHBOARD --}}
        <div class="row">
            <div class="col-12 mt-3 mb-1">
                <h5 class="text-uppercase">Métricas de Vendas -> </h5>
                <p>Dados de vendas e do financeiro</p>
            </div>
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
                                    <h4><a href="#">Total
                                            Vendas</a></h4>
                                    <p class="mb-0">Mês atual</p>
                                </div>
                            </div>
                            <div class="align-self-center">
                                <h2 class="h1 mb-0 valueMounth">R$: {{$viewData['totalMonth']}} </h2>
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
                                <h2 class="h1 mb-0 ">R$: {{$viewData['totalDay']}}</h2>
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
                                    <h2 class="h1 mb-0 me-4">R$: 8000,00</h2>
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
            @foreach ($viewData['orders'] as $order)
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Vendas Aprovadas</h5>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">{{ $order->id }} - Valor: R$ {{ $order->total }} Data do Pedido:
                            {{ $order->created_at }} <a href="{{route('user.show',['id' => $order->user_id])}}">Cliente</a>
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button class="btn btn-success me-md-2 btn-sm" type="button"><i class="bi bi-eye-fill"></i>
                                    Ver Pedido</button>
                            </div>
                        </li>
                    </ul>
                </div>
            @endforeach
        </div>
    </div>

    </div>
@endsection
