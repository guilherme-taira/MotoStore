@extends('layouts.app')
@section('title', $viewData['title'])
@section('subtitle', $viewData['subtitle'])
@section('conteudo')
    <div class="container">
        <section class="gradient-custom">
            <div class="container">
                <div class="row d-flex justify-content-center  h-100">
                    <div class="col-lg-12 col-xl-12">
                        <div class="card" style="border-radius: 10px;">
                            <div class="card-header px-4 py-5">
                                <h5 class="text-muted mb-0">Cliente: <span style="color: #a8729a;">{{$viewData['order'][0]->name}},</span>  Pedido: {{$viewData['order'][0]->order_id}}
                                </h5>
                            </div>
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <p class="lead fw-normal mb-0" style="color: #a8729a;">Produtos do Pedido</p>
                                    <p class="small text-muted mb-0">Receipt Voucher : 1KAU9-84UIL</p>
                                </div>

                                @foreach ($viewData['pedidos'] as $order)
                                    <div class="card shadow-0 border mb-4">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-2">

                                                    @if ($order['produto']->imageJson)
                                                        <img class="img-fluid img-thumbnail" alt=""
                                                            style="width: 100px; height: 100px;"
                                                            src="{{ json_decode($order['produto']->imageJson)[0]->url }}">
                                                    @else
                                                        <img class="img-fluid img-thumbnail" alt=""
                                                            style="width: 100px; height: 100px;"
                                                            src="{{ Storage::disk('s3')->url('produtos/' . $order['produto']->id . '/' . $order['produto']->image) }}">
                                                    @endif

                                                </div>
                                                <div
                                                    class="col-md-2 text-center d-flex justify-content-center align-items-center">
                                                    <p class="text-muted mb-0">{{$order['produto']->title}}</p>
                                                </div>
                                                <div
                                                    class="col-md-2 text-center d-flex justify-content-center align-items-center">
                                                    <p class="text-muted mb-0 small"> {{ isset($order['produto']->qtdbaixa) ? $order['produto']->qtdbaixa * $order['venda']->quantidade: 1 * $order['venda']->quantidade}} Unidade(s)</p>
                                                </div>
                                                <div
                                                    class="col-md-2 text-center d-flex justify-content-center align-items-center">
                                                    <p class="text-muted mb-0 small">Valor Unitário: {{$order['produto']->price}}</p>
                                                </div>
                                            </div>
                                            <hr class="mb-4" style="background-color: #e0e0e0; opacity: 1;">
                                            <div class="row d-flex align-items-center">
                                                <div class="col-md-2">
                                                    <p class="text-muted mb-0 small">Track Order</p>
                                                </div>
                                                <div class="col-md-10">
                                                    <div class="progress" style="height: 6px; border-radius: 16px;">
                                                        <div class="progress-bar" role="progressbar"
                                                            style="width: 20%; border-radius: 16px; background-color: #a8729a;"
                                                            aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                    <div class="d-flex justify-content-around mb-1">
                                                        <p class="text-muted mt-1 mb-0 small ms-xl-5">Out for delivary</p>
                                                        <p class="text-muted mt-1 mb-0 small ms-xl-5">Delivered</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                <div class="d-flex justify-content-between pt-2">
                                    <p class="fw-bold mb-0">Detalhes do Pedido</p>
                                    <p class="text-muted mb-0"><span class="fw-bold me-4">Total</span> {{$viewData['order'][0]->valorVenda}}</p>
                                </div>

                                <div class="d-flex justify-content-between pt-2">
                                    <p class="text-muted mb-0">Número Pedido: {{$viewData['order'][0]->order_id}}</p>
                                    <p class="text-muted mb-0"><span class="fw-bold me-4">Desconto:</span> R$ 0.00</p>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <p class="text-muted mb-0">Data do Pedido: {{$viewData['order'][0]->created_at}}</p>
                                </div>

                                <div class="d-flex justify-content-between mb-5">
                                    <p class="text-muted mb-0">Recepits Voucher : 18KU-62IIK</p>
                                    <p class="text-muted mb-0"><span class="fw-bold me-4">Frete: </span> R$: {{$viewData['order'][0]->valorFrete}}</p>
                                </div>
                                {{-- DADOS DO USUARIO --}}
                                <hr>
                                <div class="d-flex justify-content-between pt-2">
                                    <p class="fw-bold mb-0">Dados do Cliente {{$viewData['order'][0]->name}}</p>
                                </div>

                                <div class="">
                                    <p class="text-muted mb-0">Endereço: {{$viewData['order'][0]->address}}, Nº {{$viewData['order'][0]->numero}}</p>
                                    <p class="text-muted mb-0">Cidade: {{$viewData['order'][0]->cidade}}, Bairro: {{$viewData['order'][0]->bairro}}, Complemento: {{$viewData['order'][0]->complemento}}, Cep: {{$viewData['order'][0]->cep}}</p>
                                    <p class="text-muted mb-0">CPF: {{$viewData['order'][0]->cpf}}</p>
                                </div>
                            </div>
                            <div class="card-footer border-0 px-4 py-5"
                                style="background-color: #a8729a; border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">
                                <h5 class="d-flex align-items-center justify-content-end text-white text-uppercase mb-0">
                                    Total
                                    Pago: <span class="h2 mb-0 ms-2">R${{$viewData['order'][0]->valorVenda + $viewData['order'][0]->valorFrete}}</span></h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>
@endsection
