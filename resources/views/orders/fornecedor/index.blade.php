@extends('layouts.app')
@section('title', $viewData['title'])
@section('subtitle', $viewData['subtitle'])
<meta name="csrf-token" content="{{ csrf_token() }}">
@section('conteudo')

    <style>
        /* Estilo para o botão flutuante */
        #merge-labels-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
            border-radius: 50px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
            padding: 12px 20px;
            font-size: 16px;
            background-color: #28a745;
            color: white;
            transition: all 0.3s ease;
        }

        #merge-labels-button:hover {
            background-color: #218838;
            transform: scale(1.1);
        }
    </style>

    <div class="container">

        <!-- Botão Flutuante -->
        <button type="button" id="merge-labels-button" class="btn">
            <i class="bi bi-printer"></i> Unir Etiquetas Selecionadas
        </button>

        <h3>Central do Vendedor : {{ Auth::user()->name }}</h3>


        @if (session('error'))
            <div class="alert alert-danger" role="alert">
                {{ session()->get('error') }}
            </div>
        @endif

        @if (session()->get('msg'))
            <div class="alert alert-success" role="alert">
                {{ session()->get('msg') }}
            </div>
        @endif

        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <a href="{{ route('bancario.index') }}"><button class="btn btn-success me-md-2" type="button">Dados Bancários
                    <i class="bi bi-patch-plus"></i></button></a>

            <a href="{{ route('allProductsByFornecedor') }}"><button class="btn btn-primary me-md-2" type="button"> Meus
                    Produtos <i class="bi bi-archive"></i></button></a>
        </div>
        <hr>
        {{-- START ACCORDION DATA --}}
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
                                    <h4>Aguardando Pagamento</h4>
                                    <p class="mb-0">Mês atual</p>
                                </div>
                            </div>
                            <div class="align-self-center">
                                <h2 class="h1 mb-0 valueMounth">R$: {{ $viewData['totalAguardando'] }}
                                </h2>
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
                                    <h4>Valor Pago Dia</h4>
                                    <p class="mb-0">total pago</p>
                                </div>
                            </div>
                            <div class="align-self-center">
                                <h2 class="h1 mb-0 ">R$: {{ $viewData['pago'] }}
                                </h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="accordion" id="accordionExample">
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button" type="button" data-mdb-toggle="collapse" data-mdb-target="#collapseOne"
                    aria-expanded="true" aria-controls="collapseOne">
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
                                            <i class="fas bi-bag-check text-primary fa-3x"></i>
                                        </div>
                                        <div class="text-end">
                                            <h3>{{ $viewData['haPagar'] }}</h3>
                                            <p class="mb-0">Vendas
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
                                            <i class="fas bi-printer text-success fa-3x"></i>
                                        </div>
                                        <div class="text-end">
                                            <h3>{{ $viewData['contasDia'] }}</h3>
                                            <p class="mb-0">A Imprimir
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
                                            <i class="far bi-archive-fill text-warning fa-3x"></i>
                                        </div>
                                        <div class="text-end">
                                            <h3>{{ $viewData['contasAtrasada'] }}</h3>
                                            <p class="mb-0">Já Impressa</p>
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
                                <form id="merge-labels-form" class="mb-5">
                                    <div class="container">
                                        <div class="card">
                                            <div class="card-header">{{ $viewData['subtitle'] }}</div>
                                            <div class="card-body">
                                                <h5 class="card-title">Clientes / Fornecedores</h5>
                                                <form id="merge-labels-form" class="mb-5">
                                                    @foreach ($viewData['orders'] as $order)

                                                        <div class="card mt-4 shadow-lg border-0">
                                                            <div
                                                                class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                                                <h5 class="mb-0">Venda Nº: {{ $order->id_venda }}</h5>
                                                                <!-- Botão no canto superior direito -->
                                                                <button class="btn btn-light btn-sm text-primary"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#trackingModal-{{ $order->id }}">
                                                                <i class="bi bi-truck"></i> Rastrear
                                                                </button>
                                                                <div class="card shadow-sm border-0 rounded" style="background-color: #56caff; padding: 3px;">
                                                                    <div class="d-flex justify-content-between align-items-center">
                                                                        <!-- Badge com Ícone -->
                                                                        <span class="badge bg-primary text-white d-flex align-items-center" style="font-size: 0.9rem; padding: 5px 10px;">
                                                                            <i class="fas fa-truck me-2"></i> Status

                                                                        <!-- Status mais recente -->
                                                                        <div class="latest-status fw-bold text-white" style="font-size: 0.9rem;"> Carregando...</div>
                                                                      </span>
                                                                    </div>
                                                                    <input type="hidden" class="shipping-id" value="{{ $order->shipping_id }}">
                                                                    <div class="modal-tracking-details text-muted mt-2" style="display: none;"></div>
                                                                </div>


                                                            </div>
                                                            <div class="card-body bg-light">
                                                                <div
                                                                    class="d-flex justify-content-between align-items-center">
                                                                    <div>
                                                                        <h6 class="mb-1">
                                                                            <strong>Cliente:</strong> {{ $order->cliente }}
                                                                        </h6>
                                                                        <p class="mb-0 text-muted">
                                                                            <strong>Data da Venda:</strong>
                                                                            {{ $order->dataVenda }}<br>
                                                                        </p>
                                                                    </div>
                                                                    <div>
                                                                        <h6 class="mb-1 text-end">
                                                                            <strong>Afiliado :</strong> {{ $order->name }}
                                                                        </h6>
                                                                        @if($order->informacaoadicional)
                                                                        <hr>
                                                                        <h6 class="mb-1 text-end">
                                                                            <span class="badge text-bg-warning"><strong>Observacão :{{ $order->informacaoadicional }}</strong></span>
                                                                        </h6>
                                                                        @endif
                                                                    </div>
                                                                </div>

                                                                <!-- Imagem e detalhes do produto -->
                                                                <div class="d-flex align-items-center my-3">
                                                                    <div>
                                                                        <!-- Imagem do produto -->
                                                                        <img src="{{ $order->image }}"
                                                                            alt="Imagem do Produto" class="img-thumbnail"
                                                                            style="width: 70px; height: auto;">
                                                                    </div>
                                                                    <div class="ms-3">
                                                                        <h6 class="mb-1">
                                                                            <strong>Produto:</strong> {{ $order->nome }}
                                                                        </h6>
                                                                        <p class="mb-0 text-muted">
                                                                            <strong>SKU:</strong> {{ $order->codigo }}<br>
                                                                            <strong>Quantidade:</strong>
                                                                            {{ $order->quantidade }}
                                                                        </p>
                                                                    </div>
                                                                </div>

                                                                <hr>

                                                                <div
                                                                    class="d-flex justify-content-between align-items-center">
                                                                    @if ($order->statusf == 3)
                                                                        <span class="badge bg-danger p-2">Aguardando
                                                                            Pagamento</span>
                                                                    @endif
                                                                    @if ($order->statusf == 4)
                                                                        @if ($order->isPrinted == 0 && $order->statusf == 4)
                                                                            <span class="badge bg-success p-2">Pagamento
                                                                                Realizado</span>
                                                                            <div class="form-check">
                                                                                <input
                                                                                    class="form-check-input pdf-checkbox"
                                                                                    type="checkbox"
                                                                                    value="{{ $order->shipping_id }}"
                                                                                    id="checkbox-{{ $order->id }}">
                                                                                <label class="form-check-label"
                                                                                    for="checkbox-{{ $order->id }}">
                                                                                    Etiqueta para o pedido
                                                                                    {{ $order->order_id }}
                                                                                </label>
                                                                            </div>
                                                                        @else
                                                                            <span class="badge bg-success p-2">Pagamento
                                                                                Realizado</span>
                                                                            <div class="form-check">
                                                                                <input
                                                                                    class="form-check-input bg-warning pdf-checkbox"
                                                                                    type="checkbox"
                                                                                    value="{{ $order->shipping_id }}"
                                                                                    id="checkbox-{{ $order->id }}">
                                                                                <label class="form-check-label"
                                                                                    for="checkbox-{{ $order->id }}">
                                                                                    Etiqueta Já Impressa data:
                                                                                    {{ $order->updated_at }}
                                                                                </label>
                                                                            </div>
                                                                        @endif
                                                                    @endif
                                                                    <a href="{{ route('orders.show', ['id' => $order->order_id]) }}"
                                                                        class="btn btn-outline-primary btn-sm">
                                                                        <i class="bi bi-eye"></i> Ver Detalhes
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>

                                                         <!-- Modal -->
                                                        <div class="modal fade" id="trackingModal-{{ $order->id }}" tabindex="-1" aria-labelledby="trackingModalLabel-{{ $order->id }}" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-right">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="trackingModalLabel-{{ $order->id }}">Detalhes do Rastreio</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body modal-tracking-details-{{$order->shipping_id}}">

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex">
                        {!! $viewData['orders']->links() !!}
                    </div>
                </div>
            </div>
        </div>

        <script>

            $(document).ready(function () {
                $(".shipping-id").each(function () {
                    const shippingId = $(this).val();
                    const cardElement = $(this).closest(".card"); // Salva o elemento do card associado
                    const apiUrl = `api/v1/get-shipping-status/${shippingId}`;

                    $.ajax({
                        url: apiUrl,
                        method: "GET",
                        success: function (data) {
                            if (data.length > 0) {
                                const latestStatus = data[data.length - 1].substatus;

                                // Atualiza o status mais recente no card correspondente
                                cardElement.find(".latest-status").text(": "+ latestStatus);

                                data.forEach((item) => {
                                const cardHtml = `
                                    <div class="col-12">
                                        <div class="card shadow-sm border-0">
                                            <div class="card-header bg-primary text-white">
                                                <strong>Rastreio ID: ${item.shipping_id}</strong>
                                            </div>
                                            <div class="card-body">
                                                <p><strong>Status: </strong> ${item.substatus}</p>
                                                <p><strong>Código de Rastreio :</strong> ${item.tracking_number}</p>
                                                <p><strong>Transportadora :</strong> ${item.tracking_method}</p>
                                                <p><strong>Entrega estimada :</strong> ${item.estimated_delivery_extended}</p>
                                                <p><strong>última atualização:</strong> ${item.updated_at}</p>
                                            </div>
                                        </div>
                                    </div>
                                `;

                                $(`.modal-tracking-details-${item.shipping_id}`).append(cardHtml);
                            });
                            } else {
                                cardElement.find(".latest-status").text(": Sem atualização.");
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error(`Erro ao carregar status para shipping_id ${shippingId}:`, error);
                        },
                    });
                });
            });


            document.querySelectorAll('button[data-bs-toggle="modal"]').forEach(button => {
                button.addEventListener('click', function(event) {
                    event.preventDefault(); // Previne a atualização da página
                    // Lógica adicional pode ser adicionada aqui
                });
            });

            document.getElementById('merge-labels-button').addEventListener('click', function() {
                const selectedCheckboxes = document.querySelectorAll('.pdf-checkbox:checked');
                const pdfLinks = Array.from(selectedCheckboxes).map(checkbox => checkbox.value);

                if (pdfLinks.length === 0) {
                    alert('Selecione pelo menos uma etiqueta para unir.');
                    return;
                }

                console.log(pdfLinks);

                fetch('/merge-pdfs', {
                        method: 'POST',
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Content-Type": "application/json",
                        },
                        body: JSON.stringify({
                            pdf_links: pdfLinks
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Erro ao unir os PDFs.');
                        }
                        return response.blob();
                    })
                    .then(blob => {
                        const url = window.URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        a.href = url;
                        a.download = 'etiquetas_unificadas.pdf';
                        document.body.appendChild(a);
                        a.click();
                        a.remove();
                    })
                    .catch(error => {
                        console.error('Erro:', error);
                        alert('Ocorreu um erro ao unir as etiquetas.');
                    });
            });
        </script>
    @endsection
