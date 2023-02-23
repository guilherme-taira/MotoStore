@extends('layouts.app')
@section('title', $viewData['title'])
@section('subtitle', $viewData['subtitle'])
@section('conteudo')
    <div class="container">
        <h3>Contas a Receber</h3>

        @if (session()->get('msg'))
            <div class="alert alert-success" role="alert">
                {{ session()->get('msg') }}
            </div>
        @endif
        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <a href="{{ route('cart.index') }}"><button class="btn btn-success me-md-2" type="button">Incluir Nova Conta <i
                        class="bi bi-patch-plus"></i></button></a>
        </div>
        <hr>

        {{-- START ACCORDION DATA --}}
        <div class="accordion" id="accordionExample">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button" type="button" data-mdb-toggle="collapse"
                        data-mdb-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        Dashboard de Valores a Receber
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                    data-mdb-parent="#accordionExample">
                    <div class="accordion-body">
                        <div class="row">
                            <div class="col-xl-3 col-sm-6 col-12 mb-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between px-md-1">
                                            <div class="align-self-center">
                                                <i class="fas fa-mug-hot text-success fa-3x"></i>
                                            </div>
                                            <div class="text-end">
                                                <h3>{{ $viewData['contasDia'] }}</h3>
                                                <p class="mb-0">Contas em Dia
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-sm-6 col-12 mb-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between px-md-1">
                                            <div class="align-self-center">
                                                <i class="far fa-clock text-danger fa-3x"></i>
                                            </div>
                                            <div class="text-end">
                                                <h3>{{ $viewData['contasAtrasada'] }}</h3>
                                                <p class="mb-0">Contas Atrasadas</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- END ACCORDION DATA --}}
                        <div class="container">
                            <div class="card">
                                <div class="card-header">{{ $viewData['subtitle'] }}</div>
                                <div class="card-body">
                                    <h5 class="card-title">Clientes / Fornecedores</h5>
                                    @foreach ($viewData['orders'] as $order)
                                        <div class="card mt-4">
                                            <div class="my-3 p-3 bg-body rounded shadow-sm" id="result">
                                                <h6 class="pb-2 mb-0">Vendas Recentes</h6>

                                                <div class="card-body"><a href="{{ route('orders.show', ['id' => $order->order_id]) }}"
                                                        class="text-decoration-none"><strong>Cliente:
                                                            {{ $order->cliente }}
                                                        </strong>,
                                                        Venda
                                                        Nº:{{ $order->id }}, Data da Venda: {{ $order->datavenda }},
                                                        Data da Venda: {{ $order->created_at }},
                                                        <hr> Status: <span
                                                            class="badge bg-primary">{{ $order->value_status }} </span>
                                                        <span><strong> Valor: R$ {{ $order->valor }} </strong> </span>
                                                        <embed src="{{ $order->link }}" width="200" height="100"
                                                            class="float-end">
                                                    </a>

                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="d-flex">
                                    {!! $viewData['orders']->links() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                @endsection
