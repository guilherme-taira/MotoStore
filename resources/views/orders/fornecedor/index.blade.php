@extends('layouts.app')
@section('title', $viewData['title'])
@section('subtitle', $viewData['subtitle'])
<meta name="csrf-token" content="{{ csrf_token() }}">
@section('conteudo')

    <style>
        /* Aplica border-radius aos itens do accordion */
        #advancedFilterAccordion .accordion-item {
            border: 1px solid #dee2e6;
            /* Exibe a borda para evidenciar o arredondamento */
            border-radius: 10px;
            margin-bottom: 10px;
            /* Espaço entre os itens */
            overflow: hidden;
            /* Garante que o conteúdo não ultrapasse o arredondamento */
        }

        /* Se desejar, arredonde também o botão do accordion */
        #advancedFilterAccordion .accordion-button {
            border-radius: 10px;
        }

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

        .card {
            border: none;
            border-radius: 15px;
            background: linear-gradient(145deg, #f5f5f5, #ffffff);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
        }

        body.modal-open .card:hover {
            transform: none;
        }

        .card-body {
            padding: 20px;
        }

        .icon-container {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: rgba(0, 123, 255, 0.1);
        }

        .icon-container i {
            font-size: 2rem;
        }

        h4,
        p {
            margin: 0;
        }

        .count-number {
            font-size: 2.2rem;
            font-weight: bold;
            color: #343a40;
        }

        .seller-dashboard {
            background: linear-gradient(135deg, #007bff, #00c6ff);
            color: white;
            padding: 20px 30px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            display: flex;
            align-items: center;
            justify-content: space-between;
            animation: fadeInSlide 1s ease-out;
            position: relative;
            overflow: hidden;
        }

        .seller-dashboard::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: rgba(255, 255, 255, 0.1);
            transform: rotate(45deg);
            animation: moveBg 6s infinite linear;
        }

        @keyframes moveBg {
            0% {
                transform: translateX(-50%) rotate(45deg);
            }

            50% {
                transform: translateX(50%) rotate(45deg);
            }

            100% {
                transform: translateX(-50%) rotate(45deg);
            }
        }

        @keyframes fadeInSlide {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .seller-dashboard h3 {
            font-size: 2rem;
            margin: 0;
            font-weight: bold;
            z-index: 1;
        }

        .seller-dashboard .bi-person-circle {
            font-size: 3rem;
            z-index: 1;
        }

        .seller-dashboard .highlight {
            background: rgba(255, 255, 255, 0.2);
            padding: 5px 10px;
            border-radius: 8px;
            font-weight: bold;
        }

        .accordion-button {
            background: linear-gradient(145deg, #007bff, #0056b3);
            color: white;
            font-weight: bold;
        }

        .accordion-button:focus {
            box-shadow: none;
        }

        #toastContainer .toast {
            min-width: 350px;
            /* Ajuste para a largura desejada */
        }
    </style>

    <script>
        $(document).ready(function() {

            function showToast(message, type) {
                // Define a cor de fundo com base no tipo: "success" ou "error"
                var bgColor = (type === 'success') ? 'bg-success' : 'bg-danger';

                var toastHTML = `
                    <div class="toast align-items-center text-white ${bgColor} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                        ${message}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    </div>`;

                var $toast = $(toastHTML);
                $("#toastContainer").append($toast);

                // Inicializa o Toast com delay de 3000ms (3 segundos)
                var toast = new bootstrap.Toast($toast[0], {
                    delay: 3000
                });
                toast.show();

                // Após o tempo, remove o toast para não acumular no DOM
                setTimeout(function() {
                    $toast.remove();
                }, 3500);
            }

            $(document).on('submit', '.informacoesForm', function(e) {
                e.preventDefault();
                console.log("Submit interceptado.");

                var $form = $(this);

                $.ajax({
                    url: $form.attr('action'),
                    type: $form.attr('method'),
                    data: $form.serialize(),
                    success: function(response) {
                        if (response.success) {
                            showToast(response.message, 'success');
                            // Fecha o modal (Bootstrap 5)
                            $form.closest('.modal').modal('hide');
                            // Atualiza a página ou redireciona
                            // window.location.reload();
                        }
                    },
                    error: function(xhr) {
                        showToast("Erro ao atualizar informações. Tente novamente.", 'error');
                    }
                });

                return false;
            });
        });
    </script>


    <div class="container">

        <!-- Botão Flutuante -->
        <button type="button" id="merge-labels-button" class="btn">
            <i class="bi bi-printer"></i> Unir Etiquetas Selecionadas
        </button>


        <div class="container my-4">
            <div class="seller-dashboard">
                <h3><i class="bi bi-person-circle me-3"></i>Central do Vendedor: <span
                        class="highlight">{{ Auth::user()->name }}</span></h3>
            </div>
        </div>


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

        <div aria-live="polite" aria-atomic="true" class="position-fixed top-0 start-0 translate-middle-y p-3 mt-4"
            style="z-index: 1055; max-width: 600px; width: 100%;">
            <div id="toastContainer" class="toast-container"></div>
        </div>

        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <a href="{{ route('bancario.index') }}"><button class="btn btn-success me-md-2" type="button">Dados Bancários
                    <i class="bi bi-patch-plus"></i></button></a>

            <a href="{{ route('allProductsByFornecedor') }}"><button class="btn btn-primary me-md-2" type="button"> Meus
                    Produtos <i class="bi bi-archive"></i></button></a>
        </div>
        <hr>
        {{-- START ACCORDION DATA --}}
        <div class="container py-5">
            <div class="row g-4">
                <div class="col-xl-6 col-md-12">
                    <div class="card shadow">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="icon-container me-3">
                                    <i class="bi bi-cart-check text-success"></i>
                                </div>
                                <div>
                                    <h4>Aguardando Pagamento</h4>
                                    <p class="text-muted">Mês atual</p>
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="count-number" data-target="{{ $viewData['totalAguardando'] }}">
                                    R$: {{ number_format($viewData['totalAguardando'], 2, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6 col-md-12">
                    <div class="card shadow">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="icon-container me-3">
                                    <i class="bi bi-cash-coin text-warning"></i>
                                </div>
                                <div>
                                    <h4>Valor Pago Dia</h4>
                                    <p class="text-muted">Total pago</p>
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="count-number" data-target="{{ $viewData['pago'] }}">R$: {{ number_format($viewData['pago'], 2, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion mt-2" id="accordionExample">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button text-white" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Dashboard de Valores a Receber
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                        data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <div class="row g-4">
                                <div class="col-xl-4 col-sm-6">
                                    <div class="card shadow">
                                        <div class="card-body d-flex justify-content-between align-items-center">
                                            <div class="icon-container bg-primary bg-opacity-25 me-3">
                                                <i class="bi bi-bag-check text-primary"></i>
                                            </div>
                                            <div class="text-end">
                                                <div class="count-number" data-target="{{ $viewData['haPagar'] }}">
                                                    {{ $viewData['haPagar'] }}</div>
                                                <p class="text-muted mb-0">Vendas</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-4 col-sm-6">
                                    <div class="card shadow clickable-card" data-url="{{ route('imprimir.contas') }}">
                                        <div class="card-body d-flex justify-content-between align-items-center">
                                            <div class="icon-container bg-success bg-opacity-25 me-3">
                                                <i class="bi bi-printer text-success"></i>
                                            </div>
                                            <div class="text-end">
                                                <div class="count-number" data-target="{{ $viewData['contasDia'] }}">
                                                    {{ $viewData['contasDia'] }}</div>
                                                <p class="text-muted mb-0">A Imprimir</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-xl-4 col-sm-6">
                                    <div class="card shadow">
                                        <div class="card-body d-flex justify-content-between align-items-center">
                                            <div class="icon-container bg-warning bg-opacity-25 me-3">
                                                <i class="bi bi-archive-fill text-warning"></i>
                                            </div>
                                            <div class="text-end">
                                                <div class="count-number" data-target="{{ $viewData['contasAtrasada'] }}">
                                                    {{ $viewData['contasAtrasada'] }}</div>
                                                <p class="text-muted mb-0">Já Impressa</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- End Row -->
                        </div>
                    </div>
                </div>
            </div>
        </div>


        @php
            // Se algum dos filtros estiver preenchido, abrimos o accordion
            $openAccordion =
                request('npedido') ||
                request('nome') ||
                request('codigoafiliado') ||
                request('cpf') ||
                request('cnpj') ||
                request('datainicial') ||
                request('datafinal') ||
                request('status');
        @endphp

        <div class="accordion accordion-flush py-2" id="advancedFilterAccordion">
            <div class="accordion-item">
                <h2 class="accordion-header" id="advancedFilterHeading">
                    <button class="accordion-button {{ $openAccordion ? '' : 'collapsed' }} text-white" type="button"
                        data-bs-toggle="collapse" data-bs-target="#advancedFilterCollapse"
                        aria-expanded="{{ $openAccordion ? 'true' : 'false' }}" aria-controls="advancedFilterCollapse">
                        Filtros Avançados
                    </button>
                </h2>
                <div id="advancedFilterCollapse" class="accordion-collapse collapse {{ $openAccordion ? 'show' : '' }}"
                    aria-labelledby="advancedFilterHeading" data-bs-parent="#advancedFilterAccordion">
                    <div class="accordion-body">
                        <form id="filterForm" action="{{ route('fornecedor.index') }}" method="get">
                            <div class="row">
                                <div class="col-md-2">
                                    <label for="npedido" class="form-label">Número do Pedido</label>
                                    <input type="text" name="npedido" class="form-control" id="npedido"
                                        value="{{ request('npedido') }}">
                                </div>
                                <div class="col-md-4">
                                    <label for="nome" class="form-label">Nome do Cliente (Mercado Livre)</label>
                                    <input type="text" name="nome" class="form-control" id="nome"
                                        value="{{ request('nome') }}">
                                </div>
                                <div class="col-md-2">
                                    <label for="codigoafiliado" class="form-label">Código do Afiliado</label>
                                    <input type="text" name="codigoafiliado" class="form-control" id="codigoafiliado"
                                        value="{{ request('codigoafiliado') }}">
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-2">
                                    <label for="datainicial" class="form-label">Data Inicial</label>
                                    <input type="date" name="datainicial" class="form-control" id="datainicial"
                                        value="{{ request('datainicial') }}">
                                </div>
                                <div class="col-md-2">
                                    <label for="datafinal" class="form-label">Data Final</label>
                                    <input type="date" name="datafinal" class="form-control" id="datafinal"
                                        value="{{ request('datafinal') }}">
                                </div>
                                <div class="col-md-2">
                                    <label for="status" class="form-label">Status do Pedido</label>
                                    <select class="form-select" name="status" id="status">
                                        <option value="">Selecione...</option>
                                        <option value="" {{ request('status') == '1' ? 'selected' : '' }}>Aguardando
                                            Envio</option>
                                        <option value="1" {{ request('status') == '2' ? 'selected' : '' }}>A Enviar
                                        </option>
                                        <option value="2" {{ request('status') == '3' ? 'selected' : '' }}>Enviado
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 mt-4">
                                <button class="btn btn-primary" type="submit">Pesquisar</button>
                                <button type="button" class="btn btn-secondary ms-2" onclick="clearFilter()">Limpar
                                    Formulário</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Script para limpar o formulário -->
        <script>
            function clearFilter() {
                // Limpa os inputs de texto e data
                $('#filterForm input[type="text"], #filterForm input[type="date"]').val('');
                // Reseta os selects para a primeira opção
                $('#filterForm select').prop('selectedIndex', 0);
            }
        </script>



        {{-- END ACCORDION DATA --}}

        <div class="card mt-2">
            <div class="card-header">{{ $viewData['subtitle'] }}</div>
            <div class="card-body">
                <h5 class="card-title">Clientes / Fornecedores</h5>
                <div id="merge-labels-form" class="mb-5">
                    <div class="container">
                        <div id="merge-labels-form" class="mb-5">
                            @foreach ($viewData['orders'] as $order)
                                <!-- Definindo a cor do card com base no status_envio -->
                                @php
                                    $cardColorClass = '';
                                    if ($order->status_envio == 1) {
                                        $cardColorClass = 'bg-warning'; // Laranja
                                    } elseif ($order->status_envio == 2) {
                                        $cardColorClass = 'bg-success'; // Verde
                                    } else {
                                        $cardColorClass = 'bg-primary'; // Verde
                                    }
                                @endphp

                                <div class="card mt-4 shadow-lg border-0 {{ $cardColorClass }}">
                                    <div
                                        class="card-header {{ $cardColorClass }} text-white d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">Venda Nº: {{ $order->id_venda }}</h5>
                                        <!-- Botão no canto superior direito -->
                                        <button class="btn btn-light btn-sm text-primary" data-bs-toggle="modal"
                                            data-bs-target="#trackingModal-{{ $order->id }}">
                                            <i class="bi bi-truck"></i> Rastrear
                                        </button>
                                        <div class="card shadow-sm border-0 rounded">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <!-- Badge com Ícone -->
                                                <span class="badge bg-primary text-white d-flex align-items-center"
                                                    style="font-size: 0.9rem; padding: 5px 10px;">
                                                    <i class="fas fa-truck me-2"></i> Status

                                                    <!-- Status mais recente -->
                                                    <div class="latest-status fw-bold text-white"
                                                        style="font-size: 0.9rem;"> Carregando...
                                                    </div>
                                                </span>
                                            </div>
                                            <input type="hidden" class="shipping-id" value="{{ $order->shipping_id }}">
                                            <div class="modal-tracking-details text-muted mt-2" style="display: none;">
                                            </div>
                                        </div>


                                    </div>
                                    <div class="card-body bg-light">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1">
                                                    <div class="text-center">
                                                        <div class="d-flex gap-2">
                                                            @if (!empty($order->nota_fiscal))
                                                                <a href="{{ asset('storage/public/' . $order->nota_fiscal) }}"
                                                                    target="_blank"
                                                                    class="btn btn-success btn-sm d-flex align-items-center justify-content-center gap-2 shadow-lg"
                                                                    style="border-radius: 8px; margin-bottom:30px; background: linear-gradient(135deg, #00C853, #1B5E20); transition: all 0.3s ease-in-out;">
                                                                    <i class="bi bi-file-earmark-pdf"
                                                                        style="font-size: 1.2rem;"></i>
                                                                    <span class="fw-bold">Ver Nota Fiscal</span>
                                                                </a>
                                                            @else
                                                                <form action="/upload-nf/{{ $order->financeiroId }}"
                                                                    method="POST" enctype="multipart/form-data">
                                                                    @csrf
                                                                    <label for="{{ $order->financeiroId }}"
                                                                        class="p-3 d-block"
                                                                        style="border: 2px dashed gray; border-radius: 10px; padding: 10px; cursor: pointer;">
                                                                        <i class="bi bi-file-earmark-plus"
                                                                            style="font-size: 24px;"></i>
                                                                        <p class="mb-0 fw-bold">Anexar NF</p>
                                                                    </label>
                                                                    <input type="file" name="nota_fiscal"
                                                                        id="{{ $order->financeiroId }}" class="d-none"
                                                                        onchange="this.form.submit()">
                                                                </form>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <strong class="mt-2">Cliente:</strong> {{ $order->cliente }}
                                                </h6>
                                                <p class="mb-0 text-muted">
                                                    <strong>Data da Venda:</strong>
                                                    {{ $order->dataVenda }}<br>
                                                </p>
                                            </div>
                                            <div>
                                                <h6 class="mb-1 text-end">

                                                    <strong class="mt-4">Afiliado :</strong> {{ $order->name }} /
                                                    <strong>Código :</strong> {{ $order->id_user }}
                                                    <select name="status_envio"
                                                        class="form-select form-select-sm mt-4 status-envio-select"
                                                        data-id="{{ $order->financeiroId }}"
                                                        data-shipping-id="{{ $order->shipping_id }}">
                                                        <option value="">Selecione</option>
                                                        @foreach ($viewData['statusApp'] as $status)
                                                            <option value="{{ $status->id }}"
                                                                {{ $order->status_envio == $status->id ? 'selected' : '' }}>
                                                                {{ $status->nome }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </h6>

                                                <hr>
                                                <h6 class="mb-1 text-end">
                                                    <span class="badge text-bg-info"><strong>
                                                            {{ $order->informacaoadicional }}</strong></span>
                                                    <hr>
                                                    <!-- Botão para abrir o modal de atualização -->
                                                    <button type="button" class="btn btn-sm btn-outline-primary ms-2"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#informacoesModal-{{ $order->financeiroId }}">
                                                        Criar Observação
                                                    </button>
                                                </h6>

                                            </div>
                                        </div>

                                        <!-- Imagem e detalhes do produto -->
                                        <div class="d-flex align-items-center my-3">
                                            <div>
                                                <!-- Imagem do produto -->
                                                <img src="{{ $order->image }}" alt="Imagem do Produto"
                                                    class="img-thumbnail" style="width: 70px; height: auto;">
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

                                        <div class="d-flex justify-content-between align-items-center">
                                            @if ($order->statusf == 3)
                                                <span class="badge bg-danger p-2">Aguardando Pagamento</span>
                                            @endif

                                            @if ($order->statusf == 4)
                                                @if ($order->isPrinted == 0)
                                                    <span class="badge bg-success p-2">Pagamento Realizado</span>
                                                    <div class="form-check">
                                                        <input class="form-check-input pdf-checkbox" type="checkbox"
                                                            value="{{ $order->shipping_id }}" id="checkbox-{{ $order->id }}">
                                                        <label class="form-check-label" for="checkbox-{{ $order->id }}">
                                                            Etiqueta para o pedido {{ $order->order_id }}
                                                        </label>
                                                    </div>
                                                @else
                                                    <span class="badge bg-success p-2">Pagamento Realizado</span>
                                                    <div class="form-check">
                                                        <input class="form-check-input bg-warning pdf-checkbox" type="checkbox"
                                                            value="{{ $order->shipping_id }}" id="checkbox-{{ $order->id }}">
                                                        <label class="form-check-label" for="checkbox-{{ $order->id }}">
                                                            Etiqueta Já Impressa data: {{ $order->updated_at }}
                                                        </label>
                                                    </div>
                                                @endif
                                            @endif

                                            @if ($order->statusf == 5)
                                                <span class="badge bg-danger p-2">Pagamento Cancelado</span>
                                            @endif


                                            <a href="{{ route('orders.show', ['id' => $order->order_id]) }}"
                                                class="btn btn-outline-primary btn-sm">
                                                <i class="bi bi-eye"></i> Ver Detalhes
                                            </a>
                                        </div>
                                    </div>
                                </div>


                                <!-- Modal -->
                                <div class="modal fade" id="trackingModal-{{ $order->id }}" tabindex="-1"
                                    aria-labelledby="trackingModalLabel-{{ $order->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="trackingModalLabel-{{ $order->id }}">
                                                    Detalhes do Rastreio</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body modal-tracking-details-{{ $order->shipping_id }}">

                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <!-- Modal de Atualização de Informações -->
                                <div class="modal fade" id="informacoesModal-{{ $order->financeiroId }}" tabindex="-1"
                                    aria-labelledby="informacoesModalLabel-{{ $order->financeiroId }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form id="informacoesForm" class="informacoesForm"
                                                action="{{ route('orders.updateInformacoes', $order->financeiroId) }}"
                                                method="POST">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title"
                                                        id="informacoesModalLabel-{{ $order->financeiroId }}">Atualizar
                                                        Informações</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Fechar"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="informacaoadicional-{{ $order->financeiroId }}"
                                                            class="form-label">Informações Adicionais</label>
                                                        <textarea class="form-control" id="informacaoadicional-{{ $order->financeiroId }}" name="informacaoadicional"
                                                            rows="4">{{ $order->informacoes }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Cancelar</button>
                                                    <button type="submit" class="btn btn-primary">Atualizar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex">
            <!-- Container da paginação -->
            <div class="pagination-container">
                {!! $viewData['orders']->links() !!}
            </div>
        </div>
    </div>
    </div>
    </div>




    <script>
        var orderShowRoute = "{{ route('orders.show', ['id' => ':id']) }}";
    </script>


    <script>
        $(document).ready(function() {

            $(".clickable-card").click(function() {
                let url = $(this).data("url"); // Obtém a URL da rota
                let userId = "{{ Auth::user()->id }}"; // Captura o ID do usuário autenticado

                $.ajax({
                    url: url,
                    type: "POST",
                    data: {
                        user: userId, // Envia o ID do usuário
                        _token: "{{ csrf_token() }}" // Proteção CSRF
                    },
                    beforeSend: function() {
                        $("#merge-labels-form").html(
                            '<p class="text-center">Carregando...</p>'
                        ); // Placeholder de carregamento
                    },
                    success: function(response) {
                        let html = '';
                        console.log(response);
                        if (response.data.length > 0) {
                            $.each(response.data, function(index, order) {
                                let cardColorClass = order.status_envio == 1 ?
                                    'bg-warning' : (order.status_envio == 2 ?
                                        'bg-success' : 'bg-primary');

                                html += `
                            <div class="card mt-4 shadow-lg border-0 ${cardColorClass}">
                                <div class="card-header ${cardColorClass} text-white d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Venda Nº: ${order.id_venda}</h5>
                                    <button class="btn btn-light btn-sm text-primary" data-bs-toggle="modal" data-bs-target="#trackingModal-${order.id}">
                                        <i class="bi bi-truck"></i> Rastrear
                                    </button>
                                    <div class="card shadow-sm border-0 rounded">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="badge bg-primary text-white d-flex align-items-center"
                                                style="font-size: 0.9rem; padding: 5px 10px;">
                                                <i class="fas fa-truck me-2"></i> Status
                                                <div class="latest-status fw-bold text-white" style="font-size: 0.9rem;"> Carregando...</div>
                                            </span>
                                        </div>
                                        <input type="hidden" class="shipping-id" value="${order.shipping_id}">
                                        <div class="modal-tracking-details text-muted mt-2" style="display: none;"></div>
                                    </div>
                                </div>
                                <div class="card-body bg-light">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">
                                                <div class="text-center">
                                                    <div class="d-flex gap-2">
                                                        ${order.nota_fiscal ? `
                                                                                        <a href="/storage/public/${order.nota_fiscal}" target="_blank"
                                                                                            class="btn btn-success btn-sm d-flex align-items-center justify-content-center gap-2 shadow-lg"
                                                                                            style="border-radius: 8px; margin-bottom:30px; background: linear-gradient(135deg, #00C853, #1B5E20); transition: all 0.3s ease-in-out;">
                                                                                            <i class="bi bi-file-earmark-pdf" style="font-size: 1.2rem;"></i>
                                                                                            <span class="fw-bold">Ver Nota Fiscal</span>
                                                                                        </a>
                                                                                    ` : `
                                                                                        <form action="/upload-nf/${order.financeiroId}" method="POST" enctype="multipart/form-data">
                                                                                            <label for="${order.financeiroId}" class="p-3 d-block"
                                                                                                style="border: 2px dashed gray; border-radius: 10px; padding: 10px; cursor: pointer;">
                                                                                                <i class="bi bi-file-earmark-plus" style="font-size: 24px;"></i>
                                                                                                <p class="mb-0 fw-bold">Anexar NF</p>
                                                                                            </label>
                                                                                            <input type="file" name="nota_fiscal" id="${order.financeiroId}" class="d-none">
                                                                                        </form>
                                                                                    `}
                                                    </div>
                                                </div>
                                                <strong class="mt-2">Cliente:</strong> ${order.cliente}
                                            </h6>
                                            <p class="mb-0 text-muted">
                                                <strong>Data da Venda:</strong> ${order.dataVenda}<br>
                                            </p>
                                        </div>
                                        <div>
                                            <h6 class="mb-1 text-end">
                                                <strong class="mt-4">Afiliado :</strong> ${order.name}
                                                <select name="status_envio" class="form-select form-select-sm mt-4 status-envio-select"
                                                    data-id="${order.financeiroId}" data-shipping-id="${order.shipping_id}">
                                                    <option value="">Selecione</option>
                                                    ${order.statusOptions}
                                                </select>
                                            </h6>
                                            ${order.informacaoadicional ? `
                                                                            <hr>
                                                                            <h6 class="mb-1 text-end">
                                                                                <span class="badge text-bg-info"><strong>Observação: ${order.informacaoadicional}</strong></span>
                                                                                <span class="badge text-bg-warning"><strong>Prazo de Envio: ${order.estimated_handling_limit}</strong></span>
                                                                            </h6>
                                                                        ` : `
                                                                            <span class="badge text-bg-warning mt-4"><strong>Prazo de Envio: ${order.estimated_handling_limit}</strong></span>
                                                                        `}
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center my-3">
                                        <div>
                                            <img src="${order.image}" alt="Imagem do Produto"
                                                class="img-thumbnail" style="width: 70px; height: auto;">
                                        </div>
                                        <div class="ms-3">
                                            <h6 class="mb-1">
                                                <strong>Produto:</strong> ${order.nome}
                                            </h6>
                                            <p class="mb-0 text-muted">
                                                <strong>SKU:</strong> ${order.codigo}<br>
                                                <strong>Quantidade:</strong> ${order.quantidade}
                                            </p>
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="d-flex justify-content-between align-items-center">
                                        ${order.statusf == 3 ? `<span class="badge bg-danger p-2">Aguardando Pagamento</span>` : ''}
                                        ${order.statusf == 4 ? `
                                        <span class="badge bg-success p-2">Pagamento Realizado</span>
                                        <div class="form-check">
                                            <input class="form-check-input ${order.isPrinted ? 'bg-warning' : ''} pdf-checkbox" type="checkbox"
                                                value="${order.shipping_id}" id="checkbox-${order.id}">
                                            <label class="form-check-label" for="checkbox-${order.id}">
                                                ${order.isPrinted ? `Etiqueta Já Impressa data: ${order.updated_at}` : `Etiqueta para o pedido ${order.order_id}`}
                                            </label>
                                        </div>
                                         ` : ''}
                                        <a href="${orderShowRoute.replace(':id', order.order_id)}" class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-eye"></i> Ver Detalhes
                                        </a>
                                    </div>
                                </div>
                            </div>
                        `;
                            });
                        } else {
                            html = '<p class="text-center">Nenhum pedido encontrado.</p>';
                        }

                        $("#merge-labels-form").html(html);
                        $(".pagination-container").html(response
                            .pagination); // Atualiza paginação
                    },
                    error: function(xhr, status, error) {
                        console.error("Erro:", xhr.responseText);
                        alert("Erro ao buscar os pedidos.");
                    }
                });
            });


            // Função para animar números do zero até o valor final
            document.addEventListener('DOMContentLoaded', function() {
                const counters = document.querySelectorAll('.count-number');

                counters.forEach(counter => {
                    const updateCount = () => {
                        const target = +counter.getAttribute('data-target');
                        let count = +counter.innerText.replace(/\D/g, '');
                        const increment = target / 200;

                        if (count < target) {
                            counter.innerText = 'R$: ' + (count + increment).toLocaleString(
                                'pt-BR', {
                                    minimumFractionDigits: 2
                                });
                            setTimeout(updateCount, 15);
                        } else {
                            counter.innerText = 'R$: ' + target.toLocaleString('pt-BR', {
                                minimumFractionDigits: 2
                            });
                        }
                    };
                    updateCount();
                });
            });


            $('.status-envio-select').on('change', function() {
                const statusEnvio = $(this).val();
                const orderId = $(this).data('id');

                $.ajax({
                    url: `/financeiro/${orderId}/update-status-envio`,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        status_envio: statusEnvio,
                    },
                    success: function(response) {
                        if (response.success) {
                            alert(response.message);
                        } else {
                            alert('Falha ao atualizar o status.');
                        }
                    },
                    error: function() {
                        alert('Erro ao atualizar o status.');
                    }
                });
            });

            $(".shipping-id").each(function() {
                const shippingId = $(this).val();
                const cardElement = $(this).closest(".card"); // Salva o elemento do card associado
                const apiUrl = `api/v1/get-shipping-status/${shippingId}`;

                $.ajax({
                    url: apiUrl,
                    method: "GET",
                    success: function(data) {
                        if (data.length > 0) {
                            const latestStatus = data[data.length - 1].substatus;

                            // Atualiza o status mais recente no card correspondente
                            cardElement.find(".latest-status").text(": " + latestStatus);

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

                                $(`.modal-tracking-details-${item.shipping_id}`).append(
                                    cardHtml);
                            });
                        } else {
                            cardElement.find(".latest-status").text(": Sem atualização.");
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(`Erro ao carregar status para shipping_id ${shippingId}:`,
                            error);
                    },
                });
            });
        });


        document.querySelectorAll('button[data-bs-toggle="modal"]').forEach(button => {
            button.addEventListener('click', function(event) {
                // event.preventDefault(); // Previne a ação padrão
                // // Aqui você pode adicionar qualquer lógica necessária

                // // Abre o modal manualmente
                // const targetModalId = button.getAttribute('data-bs-target');
                // const modalElement = document.querySelector(targetModalId);
                // const modalInstance = new bootstrap.Modal(modalElement);
                // modalInstance.show();
            });
        });


        document.getElementById('merge-labels-button').addEventListener('click', function() {
            const selectedCheckboxes = document.querySelectorAll('.pdf-checkbox:checked');
            const pdfLinks = Array.from(selectedCheckboxes).map(checkbox => checkbox.value);

            if (pdfLinks.length === 0) {
                alert('Selecione pelo menos uma etiqueta para unir.');
                return;
            }

            // Itera sobre cada checkbox selecionado
            selectedCheckboxes.forEach(checkbox => {
                const shippingId = checkbox.value; // Valor do shipping_id associado ao checkbox

                // Encontra o select correspondente ao pedido
                const selectElement = document.querySelector(
                    `.status-envio-select[data-shipping-id='${shippingId}']`);

                if (selectElement) {
                    selectElement.value = '1'; // Altera para o valor correspondente a "Em preparação"
                    selectElement.dispatchEvent(new Event('change')); // Dispara o evento de mudança
                }
            });

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

        document.querySelectorAll('.status-envio-select').forEach(select => {
            select.addEventListener('change', function() {
                const selectedValue = this.value;

                // Selecionar o card inteiro
                const card = this.closest('.card');

                // Selecionar o card-header
                const cardHeader = card.querySelector('.card-header');

                // Remover cores anteriores do card e do header
                card.classList.remove('bg-warning', 'bg-success');
                cardHeader.classList.remove('bg-warning', 'bg-success');

                // Adicionar a nova cor com base no valor selecionado
                if (selectedValue == 1) {
                    card.classList.add('bg-warning');
                    cardHeader.classList.add('bg-warning');
                } else if (selectedValue == 2) {
                    card.classList.add('bg-success');
                    cardHeader.classList.add('bg-success');
                }
            });
        });
    </script>
@endsection
