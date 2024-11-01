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
                        @foreach ($viewData['dados']->order_items as $key => $product)
                        <div>
                            <h6 class="mb-0">{{$viewData['order'][0]->cliente}}</h6>
                            <small class="text-muted">{{ $viewData['order'][0]->cliente }} | {{$viewData['dados']->buyer->first_name}} </small>
                        </div>
                    </div>
                    <hr>

                    <div class="d-flex justify-content-between font-weight-bold">
                        <img src="{{$order['produto']->image}}" class="mr-3" alt="Lanterna" style="width: 50px;">
                        <div class="media-body">
                            <h6 class="mt-0">{{$product->item->title}}</h6>
                            <span>SKU : {{$product->item->seller_sku}}</span>
                        </div>
                        <div class="text-right">
                            <p class="mb-0">R$ {{number_format($product->full_unit_price,2)}}</p>
                            <small class="text-muted">{{$product->quantity}} Unidade(s)</small>
                        </div>
                    </div>
                    <hr>

                    <div class="alert alert-info" role="alert">
                        <h3>Status da Entrega: </h3>
                        @php
                        function traduzirSubstatusML($substatus) {
                            $substatuses = [
                                'in_warehouse' => 'No Armazém',
                                'ready_to_pack' => 'Pronto para Embalar',
                                'packed' => 'Embalado',
                                'in_packing_list' => 'Na Lista de Embalagem',
                                // Adicione outras traduções conforme necessário
                            ];

                            return $substatuses[strtolower($substatus)] ?? $substatus; // Se não houver tradução, retorna o valor original
                        }
                        @endphp

                        @if (isset($viewData['shipping']->substatus_history) && is_array($viewData['shipping']->substatus_history))
                        @foreach ($viewData['shipping']->substatus_history as $history)
                            <div>
                                <p><strong> Data: {{ \Carbon\Carbon::parse($history->date)->translatedFormat('d \d\e F \d\e Y, H:i') }}</strong></p>
                                <p class="alert alert-secondary">Substatus: {{ traduzirSubstatusML($history->substatus) }}</p>
                            </div>
                        @endforeach
                        @else
                        <p class="alert alert-warning">Nenhum histórico encontrado.</p>
                        @endif

                    </div>

                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    @php
                        function traduzirStatusPagamento($status) {
                            $statuses = [
                                'approved' => 'Aprovado',
                                'pending' => 'Pendente',
                                'in_process' => 'Em processamento',
                                'rejected' => 'Rejeitado',
                                'refunded' => 'Reembolsado',
                                'charged_back' => 'Estornado',
                                'cancelled' => 'Cancelado',
                                'authorized' => 'Autorizado',
                                'paid' => 'Aprovado',
                                // Adicione outros status de pagamento conforme necessário
                            ];

                            return $statuses[$status] ?? $status; // Se não houver tradução, retorna o valor original
                        }
                    @endphp

                    <h6>Pagamento {{ traduzirStatusPagamento($viewData['dados']->status) }}</h6>
                    <p class="text-muted">#{{$viewData['dados']->id}} |
                    @php
                        // Converte a string da data em um objeto Carbon
                        echo \Carbon\Carbon::parse($viewData['dados']->date_created)->translatedFormat('d \d\e F');
                    @endphp
                    </p>
                    <hr>

                    @foreach ($viewData['dados']->order_items as $product)

                        <div class="d-flex justify-content-between">
                            <p>Preço do Produto</p>
                            <p>R$ {{$product->full_unit_price}}</p>
                        </div>
                        <div class="d-flex justify-content-between">
                            <p>Tarifas de venda</p>
                            <p>R$ - {{$product->sale_fee}}</p>
                        </div>
                        <div class="d-flex justify-content-between font-weight-bold">
                            <p>Frete</p>
                            <p>R$ - {{$viewData['shipping_cost']}}</p>
                        </div>
                        <hr>
                        @endforeach

                        @foreach ($viewData['dados']->payments as $payment)

                        <h3>Mercado Livre</h3>
                        <div class="d-flex justify-content-between font-weight-bold">
                            <p>Total Pago</p>
                            <p>R$ {{$payment->total_paid_amount}}</p>
                        </div>
                        <div class="d-flex justify-content-between font-weight-bold">
                            <p class="strong">Líquido</p>
                            <p>R$ {{$payment->total_paid_amount - $payment->marketplace_fee - $viewData['shipping_cost']}}</p>
                        </div>

                        {{-- DADOS DA PLATAFORMA --}}
                        <hr>

                        @if($order['venda']->detalhes_transacao)
                        @php
                          $totalFees = 0; // Inicializa a variável para somar as taxas
                        @endphp

                        <p>{{json_decode($order['venda']->detalhes_transacao)->id}}</p>
                        <h3>Afilidrop</h3>

                        @foreach (json_decode($order['venda']->detalhes_transacao)->fee_details as $fee)

                        @php
                            $totalFees += $fee->amount; // Soma o valor da taxa
                            $liquido = $payment->total_paid_amount - $payment->marketplace_fee - $viewData['shipping_cost'];
                        @endphp

                        <div class="d-flex justify-content-between font-weight-bold">
                            <p class="strong">Taxa {{$fee->type}}</p>
                            <p>R$ {{$fee->amount}}</p>
                        </div>
                        @endforeach

                        @php
                            $final =  ($liquido - ($totalFees + $fee->amount));
                        @endphp
                        @endif
                        @if(isset($final))
                        <div class="d-flex justify-content-between font-weight-bold">
                            <p class="strong">Líquido</p>
                           <p>R$: {{ number_format($final,2) }}</p>
                        </div>
                        @endif
                        @endforeach

                        @if (isset($viewData['dados']->cancel_detail))
                        <div class="alert alert-danger">
                            <h5>Pedido Cancelado</h5>
                            @php
                                // Converte a string da data em um objeto Carbon e formata para exibir em português
                                echo "<strong>" . \Carbon\Carbon::parse($viewData['dados']->cancel_detail->date)->translatedFormat('d \d\e F');
                            @endphp
                            <p>Motivo: {{ $viewData['dados']->cancel_detail->group }}</p>
                            <hr>
                            <p>Descrição: {{ $viewData['dados']->cancel_detail->description }}</p>
                        </div>
                    @endif

            </div>
        </div>
    </div>
</div>

@endforeach

@endsection
