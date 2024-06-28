@extends('layouts.app')
@section('title', $viewData['title'])
@section('subtitle', $viewData['subtitle'])
@section('conteudo')
<div class="container mt-4">
    <div class="row">
        @foreach ($viewData['pedidos'] as $order)
        <div class="col-12 mb-3">
            <h4>{{ $order['produto']->nome }}</h4>
            <p class="text-muted">Venda #{{ $viewData['order'][0]->numeropedido }} | {{ $viewData['order'][0]->dataVenda }}</p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div>
                            <h6 class="mb-0">{{$viewData['order'][0]->cliente}}</h6>
                            <small class="text-muted">{{ $viewData['order'][0]->cliente }} | CPF 00318123096 | Tel  </small>
                        </div>
                    </div>
                    <hr>
                    <div class="alert alert-warning" role="alert">
                        <strong>Envio pendente</strong>
                        <p>Entre em contato com o seu comprador para entregar o produto. Se já o entregou, avise-nos.</p>
                        <button class="btn btn-primary">Entreguei o produto</button>
                    </div>
                    <div class="media mb-3">
                        <img src="{{$order['produto']->image}}" class="mr-3" alt="Lanterna" style="width: 50px;">
                        <div class="media-body">
                            <h6 class="mt-0">Kit Super Lanterna Led Ultra Potência Original Profissional</h6>
                            <span class="badge badge-secondary">Venda por publicidade</span>
                        </div>
                        <div class="text-right">
                            <p class="mb-0">R$ 279</p>
                            <small class="text-muted">1 unidade</small>
                        </div>
                    </div>
                    <hr>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h6>Pagamento aprovado</h6>
                    <p class="text-muted">#81166006441 | 26 de junho</p>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <p>Preço do produto</p>
                        <p>R$ {{$viewData['order'][0]->valorVenda}}</p>
                    </div>
                    <div class="d-flex justify-content-between">
                        <p>Tarifas de venda</p>
                        <p>- {{$viewData['order'][0]->fee}}</p>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between font-weight-bold">
                        <p>Total</p>
                        <p>R$ {{$viewData['order'][0]->valorVenda - $viewData['order'][0]->fee}}</p>
                    </div>
                    <hr>
                    <div>
                        <p class="text-muted">O dinheiro desta venda estará disponível 5 dias depois que a pessoa que fez a compra confirmar que recebeu o pacote, ou 28 dias após a venda, caso ela não confirme o recebimento.</p>
                        <a href="#" class="btn btn-link">Ir para o Mercado Pago</a>
                        <a href="#" class="btn btn-link">Consultar prazos</a>
                    </div>
                    <hr>
                    <a href="#" class="btn btn-link">Ir para o detalhe de tarifas em "Faturamento"</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection
