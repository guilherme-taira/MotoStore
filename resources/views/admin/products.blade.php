@extends('layouts.app')
@section('title', $viewData['title'])
@section('conteudo')
    <link rel="stylesheet" href="{{ asset('css/layout_integracao.css') }}">

    @if ($errors->any())
        <div class="error-container">
            <div class="error-card">
                <div class="error-content">
                    <span class="close-btn" onclick="closeError()">×</span>
                    <h3>Erro ao Integrar o produto</h3>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <script>
            setTimeout(() => {
                closeError();
            }, 15000); // Fecha automaticamente em 15s

            function closeError() {
                let msg = document.querySelector(".error-container");
                if (msg) msg.style.display = "none";
            }
        </script>
    @endif


    @if (session('msg'))
        <div class="message-container">
            <div class="message-card">
                <div class="message-content">
                    <span class="close-btn" onclick="closeMessage()">×</span>
                    <h3>Sucesso!</h3>
                    <p>{{ session('msg') }}</p>
                </div>
            </div>
        </div>
        <script>
            setTimeout(() => {
                closeMessage();
            }, 5000); // Fecha automaticamente em 5s

            function closeMessage() {
                let msg = document.querySelector(".message-container");
                if (msg) msg.style.display = "none";
            }
        </script>
        {{ session()->forget('msg') }}
    @endif


    <script>
        function clearForm() {
            // Seleciona o formulário e redefine os campos
            const form = document.getElementById('filterForm');
            form.reset(); // Limpa todos os campos do formulário

            // Envia o formulário vazio
            form.submit();
        }
    </script>

    <div class="modal fade" id="exampleModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel"
        tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalToggleLabel">Integrações MarketPlace</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="accordion accordion-flush" id="accordionFlushExample">
                        {{-- FORMULÁRIO MERCADO LIVRE --}}
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-headingOne">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#flush-collapseOne" aria-expanded="false"
                                    aria-controls="flush-collapseOne">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value=""
                                            id="flexCheckMercadoLivre" checked>
                                        <label class="form-check-label" for="flexCheckMercadoLivre">
                                            Mercado Livre
                                        </label>
                                    </div>
                                </button>
                            </h2>

                            <div class="spinner-overlay loading-api-mercadolivre d-none"> {{-- Adicionei a classe e mantive ID para compatibilidade, mas a classe é preferível --}}
                                <div class="spinner-border spinner-big text-light" role="status">
                                    <span class="visually-hidden">Carregando...</span>
                                </div>
                            </div>

                            <div id="flush-collapseOne" class="accordion-collapse collapse"
                                aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">
                                    <form method="POST" action="{{ route('IntegrarProduto') }}"
                                        enctype="multipart/form-data" class="integracao-form"
                                        data-integration-type="mercadolivre">
                                        @csrf

                                        <div class="card mb-4 product-form-card">
                                            <div class="row g-0">
                                                <div class="col-md-4 text-center">
                                                    <img class="img-fluid rounded-start img_integracao_foto" alt="Produto">
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="card-body">
                                                        <h5 class="card-title img_integracao_title">Nome do Produto</h5>
                                                        <p class="card-text img_integracao_ean">EAN: 123456789</p>
                                                        <p class="card-text img_integracao_price">Preço: R$ 0,00</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <input type="hidden" class="form-control input-id-prodenv" name="id_prodenv">
                                        <div class="row">
                                            <div class="col-md-12">
                                                Título do Anúncio: <div class="contador text-end">0/60</div>
                                                <input type="text" class="form-control input-name" name="name"
                                                    placeholder="Digite o nome do produto">
                                                <div class="progress mt-2">
                                                    <div class="progress-bar-input progress-bar" role="progressbar"
                                                        style="width: 0%;" aria-valuenow="0" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row form-section">
                                            <div class="col-md-12">
                                                <label class="form-label" for="editor-mercadolivre">Descrição do
                                                    Anúncio</label>
                                                <textarea name="editor" id="editor-mercadolivre" rows="4" class="form-control textarea-editor"
                                                    placeholder="Digite a descrição"></textarea>
                                            </div>
                                        </div>

                                        <div class="row form-section gap-3">
                                            <div class="col-md-6 mb-4">
                                                <p class="col-lg-4 col-md-6 col-sm-12 col-form-label">Tipo de Anúncio</p>
                                                <div class="col">
                                                    <div class="mb-3 row">
                                                        <select name="tipo_anuncio"
                                                            class="form-control select-tipo-anuncio"
                                                            aria-label=".form-select-sm example" required>
                                                            <option value="gold_special">Clássico</option>
                                                            <option value="gold_pro">Premium</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-5 mb-4">
                                                <p class="col-lg-4 col-md-12 col-sm-12 col-form-label">Material de Apoio
                                                </p>
                                                <div class="col">
                                                    <a class="linkMaterial btn btn-success" target="_blank">Baixar
                                                        Material de Apoio</a>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row form-section">
                                            <div class="col-md-6">
                                                <label class="form-label">Valor Agregado</label>
                                                <select class="form-select select-valor-agregado" name="valor_tipo"
                                                    required>
                                                    <option value="">Selecione uma opção</option>
                                                    <option value="acrescimo_reais">Acréscimo R$</option>
                                                    <option value="acrescimo_porcentagem">Acréscimo %</option>
                                                    <option value="desconto_reais">Desconto R$</option>
                                                    <option value="desconto_porcentagem">Desconto %</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Valor</label>
                                                <input type="text" class="form-control input-valor-agregado"
                                                    name="valor_agregado" value="0" required>
                                            </div>
                                        </div>

                                        <div class="row form-section">
                                            <div class="row-md-2">
                                                <input type="checkbox" class="form-check-input checkbox-preco-fixo"
                                                    id="precoFixoCheckboxMercadoLivre" name="precoFixo">
                                                <label class="form-check-label" for="precoFixoCheckboxMercadoLivre">Ativar
                                                    Preço Fixo</label>
                                                <small class="form-text text-muted">Não use vírgula no preço, coloque ponto
                                                    ex: 35.90.</small>
                                            </div>

                                            <div class="col-md-3">
                                                <label for="precoFixoInputMercadoLivre" class="form-label">Preço
                                                    Fixo</label>
                                                <input type="text" class="form-control input-preco-fixo"
                                                    id="precoFixoInputMercadoLivre" name="precoFixo"
                                                    placeholder="Digite o preço fixo" required disabled>
                                            </div>

                                            <input type="hidden" class="input-is-porcem" name="isPorcem"
                                                value="0">
                                        </div>
                                        <div class="row form-section">
                                            <div class="col-md-4">
                                                <label class="form-label">Preço:</label>
                                                <input name="price" class="form-control input-preco-final"
                                                    type="text">
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label">Total:</label>
                                                <input name="totalInformado"
                                                    class="form-control input-valor-produto-display" type="text">
                                            </div>
                                        </div>

                                        <div class="col-md-6 mt-4">
                                            <div class="col">
                                                <div class="mb-3 row">
                                                    <div class="col-lg-8 col-md-6 col-sm-12">
                                                        <div class="form-check">
                                                            <input type="hidden" class="form-control input-category-id"
                                                                name="category_id">
                                                            <input class="form-check-input" type="checkbox"
                                                                name="category_default"
                                                                id="flexCheckCheckedMercadoLivreDefault" checked>
                                                            <label class="form-check-label"
                                                                for="flexCheckCheckedMercadoLivreDefault">
                                                                Usar Categoria Padrão Selecionada
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row form-section">
                                            <div class="col-md-6">
                                                <div class="col">
                                                    <div class="mb-3 row">
                                                        <label
                                                            class="col-lg-2 col-md-12 col-sm-12 col-form-label">Categorias:</label>
                                                        <div class="col-lg-8 col-md-12 col-sm-12">
                                                            <select class="form-select select-categorias"
                                                                aria-label="Default select example">
                                                                <option selected disabled>Selecionar</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="col">
                                                    <div class="mb-3 row">
                                                        <ol class="list-group list-group-numbered content_categorias">
                                                        </ol>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="formContainer"></div> {{-- Use classe aqui também se gerar campos dinâmicos --}}

                                            <input type="hidden" class="form-control input-id-categoria"
                                                name="id_categoria">
                                        </div>

                                        <div class="row form-section">
                                            <div class="col-md-12 text-end">
                                                <div class="spinner-border text-success loading-integracao d-none"
                                                    role="status">
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
                                                <button type="submit" class="btn btn-success botao_integracao">Finalizar
                                                    Integração</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <ul class="list-group">
                                    <li class="list-group-item active" aria-current="true">Histórico</li>
                                    <div class="adicionarHistorico"></div>
                                </ul>
                            </div>
                        </div>

                        {{-- FORMULÁRIO TIKTOK SHOP --}}
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-headingTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#flush-collapseTwo" aria-expanded="false"
                                    aria-controls="flush-collapseTwo">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value=""
                                            id="flexCheckTikTok" checked>
                                        <label class="form-check-label" for="flexCheckTikTok">
                                            TikTok Shop
                                        </label>
                                    </div>
                                </button>
                            </h2>
                            <div id="flush-collapseTwo" class="accordion-collapse collapse"
                                aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">
                                    <form enctype="multipart/form-data" method="GET"
                                        action="{{ route('IntegrarProdutoTikTok') }}" class="integracao-formtiktok"
                                        data-integration-type="tiktok">
                                        @csrf

                                        <div class="card mb-4 product-form-card">
                                            <div class="row g-0">
                                                <div class="col-md-4 text-center">
                                                    <img class="img-fluid rounded-start img_integracao_foto"
                                                        alt="Produto">
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="card-body">
                                                        <h5 class="card-title img_integracao_title">Nome do Produto</h5>
                                                        <p class="card-text img_integracao_ean">EAN: 123456789</p>
                                                        <p class="card-text img_integracao_price">Preço: R$ 0,00</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <input type="hidden" class="form-control input-id-prodenv" name="id_prodenv">
                                        <div class="row">
                                            <div class="col-md-12">
                                                Título do Anúncio: <div class="contador text-end">0/60</div>
                                                <input type="text" class="form-control input-name" name="name"
                                                    placeholder="Digite o nome do produto">
                                                <div class="progress mt-2">
                                                    <div class="progress-bar-input progress-bar" role="progressbar"
                                                        style="width: 0%;" aria-valuenow="0" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row form-section">
                                            <div class="col-md-12">
                                                <label class="form-label" for="editor-tiktok">Descrição do Anúncio</label>
                                                <textarea name="editor" id="editor-tiktok" rows="4" class="form-control textarea-editor"
                                                    placeholder="Digite a descrição"></textarea>
                                            </div>
                                        </div>

                                        <div class="row form-section gap-3 d-none">
                                            <div class="col-md-6 mb-4">
                                                <p class="col-lg-4 col-md-6 col-sm-12 col-form-label">Tipo de Anúncio</p>
                                                <div class="col">
                                                    <div class="mb-3 row">
                                                        <select name="tipo_anuncio"
                                                            class="form-control select-tipo-anuncio"
                                                            aria-label=".form-select-sm example" required>
                                                            <option value="gold_special">Clássico</option>
                                                            <option value="gold_pro">Premium</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-5 mb-4">
                                                <p class="col-lg-4 col-md-12 col-sm-12 col-form-label">Material de Apoio
                                                </p>
                                                <div class="col">
                                                    <a class="linkMaterial btn btn-success" target="_blank">Baixar
                                                        Material de Apoio</a>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row form-section">
                                            <div class="col-md-6">
                                                <label class="form-label">Valor Agregado</label>
                                                <select class="form-select select-valor-agregado" name="valor_tipo"
                                                    required>
                                                    <option value="">Selecione uma opção</option>
                                                    <option value="acrescimo_reais">Acréscimo R$</option>
                                                    <option value="acrescimo_porcentagem">Acréscimo %</option>
                                                    <option value="desconto_reais">Desconto R$</option>
                                                    <option value="desconto_porcentagem">Desconto %</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Valor</label>
                                                <input type="text" class="form-control input-valor-agregado"
                                                    name="valor_agregado" value="0" required>
                                            </div>
                                        </div>

                                        <div class="row form-section">
                                            <div class="row-md-2">
                                                <input type="checkbox" class="form-check-input checkbox-preco-fixo"
                                                    id="precoFixoCheckboxTikTok" name="precoFixo">
                                                <label class="form-check-label" for="precoFixoCheckboxTikTok">Ativar Preço
                                                    Fixo</label>
                                                <small class="form-text text-muted">Não use vírgula no preço, coloque ponto
                                                    ex: 35.90.</small>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="precoFixoInputTikTok" class="form-label">Preço Fixo</label>
                                                <input type="text" class="form-control input-preco-fixo"
                                                    id="precoFixoInputTikTok" name="precoFixo"
                                                    placeholder="Digite o preço fixo" required disabled>
                                            </div>
                                            <input type="hidden" class="input-is-porcem" name="isPorcem"
                                                value="0">
                                        </div>
                                        <div class="row form-section">
                                            <div class="col-md-4">
                                                <label class="form-label">Preço:</label>
                                                <input name="price" class="form-control input-preco-final"
                                                    type="text">
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label">Total:</label>
                                                <input name="totalInformado"
                                                    class="form-control input-valor-produto-display" type="text">
                                            </div>
                                        </div>

                                        <div class="col-md-6 mt-4">
                                            <div class="col">
                                                <div class="mb-3 row">
                                                    <div class="col-lg-8 col-md-6 col-sm-12">
                                                        <div class="form-check">
                                                            <input type="hidden" class="form-control input-category-id"
                                                                name="category_id">
                                                            <input class="form-check-input" type="checkbox"
                                                                name="category_default" id="flexCheckCheckedTikTokDefault"
                                                                checked>
                                                            <label class="form-check-label"
                                                                for="flexCheckCheckedTikTokDefault">
                                                                Usar Categoria Padrão Selecionada
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- 🔽 Novo bloco: Warehouse (Depósito) -->
                                        <div class="row form-section">
                                            <div class="col-md-6 mb-4">
                                                <label class="form-label" for="select-warehouse">Warehouse (Depósito)</label>
                                                <select id="select-warehouse" name="warehouse_id" class="form-select" required>
                                                    <option value="">Carregando depósitos...</option>
                                                </select>
                                                <small class="text-muted">Selecione o depósito de origem para este anúncio.</small>
                                            </div>
                                        </div>
                                        <div class="row form-section">
                                            <div class="col-md-12">
                                                <div class="col">
                                                    <div class="mb-3 row">
                                                        <label
                                                            class="col-lg-2 col-md-12 col-sm-12 col-form-label">Categorias:</label>
                                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                                            <div class="custom-category-wrapper">
                                                                <div id="category-display"
                                                                    class="custom-category-display">
                                                                    <span id="category-label">Selecionar categoria</span>
                                                                    <span style="margin-left:auto;">▾</span>
                                                                </div>
                                                                <div id="category-dropdown" class="dropdown-list d-none">
                                                                </div>
                                                                <!-- seu select existente: -->
                                                                <select class="form-select select-categorias-tiktok d-none"
                                                                    aria-label="Default select example"
                                                                    name="id_categoria" id="select-categoria-tiktok">
                                                                    <option selected disabled>Selecionar</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row form-section">
                                            <div class="col-md-12 text-end">
                                                {{-- <div class="spinner-border text-success loading-integracao d-none"
                                                    role="status">
                                                    <span class="visually-hidden">Loading...</span>
                                                </div> --}}
                                                <button type="submit" class="btn btn-success botao_integracao">Finalizar
                                                    Integração</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal de Progresso -->
    <!-- Modal -->
    <div class="modal fade" id="progressModal" tabindex="-1" aria-labelledby="progressModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="progressModalLabel">Enviando Produto...</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Barra de progresso -->
                    <div class="progress mb-3">
                        <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated bg-primary"
                            role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0"
                            aria-valuemax="100">
                            0%
                        </div>
                    </div>

                    <!-- Abas -->
                    <ul class="nav nav-tabs" id="progressTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="summary-tab" data-bs-toggle="tab"
                                data-bs-target="#summary" type="button" role="tab" aria-controls="summary"
                                aria-selected="true">
                                Resumo
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="details-tab" data-bs-toggle="tab" data-bs-target="#details"
                                type="button" role="tab" aria-controls="details" aria-selected="false">
                                Detalhes
                            </button>
                        </li>
                    </ul>
                    <div class="tab-content mt-2">
                        <div class="tab-pane fade show active" id="summary" role="tabpanel"
                            aria-labelledby="summary-tab">
                            <p id="progressMessage" class="fw-bold mb-1">Aguardando...</p>
                            <div id="summaryAlert" class="alert d-none p-2"></div>
                        </div>
                        <div class="tab-pane fade" id="details" role="tabpanel" aria-labelledby="details-tab">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <small class="text-muted">JSON / informações brutas:</small>
                                <button id="copyDetailsBtn" type="button"
                                    class="btn btn-sm btn-outline-secondary">Copiar</button>
                            </div>
                            <pre id="errorDetailsContent" class="p-2 border overflow-auto"
                                style="max-height:260px; white-space: pre-wrap; background:#f8f9fa; font-size:12px;"></pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid px-4">
        <h2 class="mt-4">Gerenciador de Produtos</h2>

        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
            <a href="{{ route('products.create') }}"><button class="btn btn-success me-md-2" type="button">Novo Produto
                    <i class="bi bi-patch-plus"></i></button></a>
        </div>
        <div class="accordion" id="accordionExample">
            <div class="card custom-card mt-2">
                <div class="card-header custom-card-header" id="headingOne">
                    <h2 class="mb-0">
                        <button class="btn btn-outline-primary" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            <i class="bi bi-search"></i> Filtros de Busca
                        </button>
                    </h2>
                </div>

                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                    data-parent="#accordionExample">
                    <div class="p-4">
                        <form class="row gy-4 gx-3" id="filterForm" action="{{ route('products.index') }}"
                            method="GET">
                            @csrf

                            <div class="col-md-4">
                                <label for="nome" class="form-label custom-label">Nome</label>
                                <input type="text" class="form-control custom-input" name="nome"
                                    value="{{ isset($viewData['filtro']['nome']) ? $viewData['filtro']['nome'] : '' }}"
                                    id="nome" placeholder="Digite o nome do produto">
                            </div>

                            <div class="col-md-4">
                                <label for="preco" class="form-label custom-label">Preço</label>
                                <div class="input-group">
                                    <select name="preco_condicao" class="form-select custom-input"
                                        style="max-width: 150px;">
                                        <option value=">">Maior que</option>
                                        <option value="<">Menor que</option>
                                    </select>
                                    <input type="text" class="form-control custom-input" name="preco"
                                        value="{{ isset($viewData['filtro']['preco']) ? $viewData['filtro']['preco'] : '' }}"
                                        id="preco" placeholder="0,00">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label for="estoque" class="form-label custom-label">Estoque</label>
                                <input type="text" class="form-control custom-input" name="estoque"
                                    value="{{ isset($viewData['filtro']['estoque']) ? $viewData['filtro']['estoque'] : '' }}"
                                    id="estoque" placeholder="Quantidade">
                            </div>

                            <div class="col-md-6">
                                <label for="categoria" class="form-label custom-label">Categoria</label>
                                <select class="form-select custom-select" name="categoria" id="categoria">
                                    <option value="">Selecione...</option>
                                    @foreach ($viewData['categorias'] as $categoria)
                                        <optgroup label="{{ $categoria['nome'] }}">
                                            @foreach ($categoria['subcategory'] as $subcategoria)
                                                <option value="{{ $subcategoria->id }}">{{ $subcategoria->name }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 text-end mt-3">
                                <button class="btn btn-action me-2" type="submit">
                                    <i class="bi bi-filter"></i> Filtrar
                                </button>
                                <button class="btn btn-outline-danger" type="button" onclick="clearForm()">
                                    <i class="bi bi-x-circle"></i> Limpar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="container mt-4">
                    <div class="row">
                        @foreach ($viewData['products'] as $product)
                            <section class="col-md-3 mb-4 product-card-section"> {{-- Adicionei uma classe para identificar as seções de produto --}}
                                <span class="d-none id_product">{{ $product->getId() }}</span>
                                <div>
                                    <div class="product-card position-relative">
                                        @if ($product->created_at->gt(\Carbon\Carbon::now()->subDays(20)))
                                            <div class="badge-new position-absolute top-0 end-0 p-2 bg-warning text-white">
                                                <i class="bi bi-pin-angle-fill"></i> Novo
                                            </div>
                                        @endif

                                        @php
                                            $fotos = $viewData['images'][$product->getId()]['fotos'] ?? [];
                                        @endphp

                                        @if (!empty($fotos))
                                            <div id="carousel{{ $product->getId() }}" class="carousel slide"
                                                data-bs-ride="false" data-bs-interval="false">
                                                <div class="carousel-inner">
                                                    @foreach ($fotos as $index => $foto)
                                                        <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                                            <img src="{!! Storage::disk('s3')->url('produtos/' . $foto['foto']) !!}"
                                                                alt="{{ $product->getName() }}"
                                                                class="product-img d-block w-100">
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <button class="carousel-control-prev" type="button"
                                                    data-bs-target="#carousel{{ $product->getId() }}"
                                                    data-bs-slide="prev" style="filter: invert(1);">
                                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                    <span class="visually-hidden">Anterior</span>
                                                </button>
                                                <button class="carousel-control-next" type="button"
                                                    data-bs-target="#carousel{{ $product->getId() }}"
                                                    data-bs-slide="next" style="filter: invert(1);">
                                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                    <span class="visually-hidden">Próximo</span>
                                                </button>
                                            </div>
                                        @endif

                                        <p class="fw-bold text-uppercase text-muted mb-1" style="font-size: 0.8rem;">
                                            Estoque {{ $product->available_quantity }}</p>
                                        <h6 class="fw-bold mb-2 product-title">{{ $product->getName() }}</h6>

                                        <div>
                                            <span class="product-price">R$
                                                {{ number_format($product->priceWithFee, 2) }}</span>
                                        </div>

                                        <div class="product-rating mt-2">
                                            ★★★★★
                                        </div>

                                        <div class="product-actions mt-3 d-flex justify-content-between">
                                            <a href="{{ route('products.show', ['id' => $product->getId()]) }}"
                                                class="btn btn-primary btn-sm" style="border-radius: 20px;">
                                                Ver Mais
                                            </a>
                                            <button class="btn btn-success btn-sm btn-integrar-produto"
                                                style="border-radius: 20px;" data-bs-toggle="modal"
                                                data-bs-target="#exampleModalToggle">
                                                Integrar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        @endforeach
                        <div class="d-flex justify-content-center mt-4">
                            {!! $viewData['products']->links() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <input type="hidden" name="id_user" id="id_user" value="{{ Auth::user()->id }}">
        <input type="hidden" name="total" id="total-global"> {{-- Renomeado para evitar conflito --}}
        <input type="hidden" name="auth" id="auth-global" value="{{ $viewData['token']->access_token }}">
        {{-- Renomeado para evitar conflito --}}

    @endsection

    {{-- Scripts devem estar no final do body ou em uma seção 'scripts' no layout --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.theme.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script> {{-- Adicione a máscara aqui --}}



    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const accordionBody = document.querySelector('#flush-collapseTwo');
            const selectWarehouse = document.querySelector('#select-warehouse');

            async function carregarWarehouses() {
                if (!selectWarehouse) return;
                if (selectWarehouse.dataset.loaded === '1') return;

                try {
                    const resp = await fetch("{{ url('/tiktok/warehouses') }}", {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json'
                        },
                        credentials: 'same-origin'
                    });

                    const json = await resp.json();

                    // Aceita: 1) array na raiz, 2) {warehouses: [...]}, 3) {success:true,data:[...]}
                    let list = [];
                    if (Array.isArray(json)) {
                        list = json;
                    } else if (Array.isArray(json.warehouses)) {
                        list = json.warehouses;
                    } else if (json.success && Array.isArray(json.data)) {
                        list = json.data;
                    } else {
                        const msg = (json && json.message) ? json.message : 'Formato inesperado da API.';
                        selectWarehouse.innerHTML = '<option value="">' + msg + '</option>';
                        return;
                    }

                    selectWarehouse.innerHTML = '';

                    if (list.length === 0) {
                        selectWarehouse.innerHTML = '<option value="">Nenhum depósito encontrado</option>';
                        return;
                    }

                    const ph = document.createElement('option');
                    ph.value = '';
                    ph.textContent = 'Selecione um depósito';
                    ph.selected = true;
                    ph.disabled = true;
                    selectWarehouse.appendChild(ph);

                    list.forEach(item => {
                        // Verifica se o tipo é SALES_WAREHOUSE
                        if (item.raw && item.raw.type === 'SALES_WAREHOUSE') {
                            const id = item.id ?? item.warehouse_id;
                            const name = item.name ?? item.warehouse_name ?? String(id);

                            const opt = document.createElement('option');
                            opt.value = id;
                            opt.textContent = name + ' (SALES_WAREHOUSE)'; // alterado aqui
                            if (item.ownership) opt.dataset.ownership = item.ownership;
                            selectWarehouse.appendChild(opt);
                        }
                    });

                    selectWarehouse.dataset.loaded = '1';

                } catch (e) {
                    console.error(e);
                    selectWarehouse.innerHTML = '<option value="">Erro ao carregar depósitos</option>';
                }
            }

            if (accordionBody) {
                accordionBody.addEventListener('shown.bs.collapse', carregarWarehouses);
                if (accordionBody.classList.contains('show')) carregarWarehouses();
            } else {
                carregarWarehouses();
            }

            const form = document.querySelector('.integracao-formtiktok');
            if (!form) {
                console.error('Form (.integracao-formtiktok) não encontrado.');
                return;
            }

            // Elementos UI
            const progressBar = document.getElementById('progressBar');
            const progressModalElement = document.getElementById('progressModal');
            const progressMessage = document.getElementById('progressMessage');
            const summaryAlert = document.getElementById('summaryAlert');
            const errorDetailsContent = document.getElementById('errorDetailsContent');
            const copyDetailsBtn = document.getElementById('copyDetailsBtn');
            const summaryTabEl = document.getElementById('summary-tab');
            const detailsTabEl = document.getElementById('details-tab');

            let progressModal = null;
            if (typeof bootstrap !== 'undefined' && progressModalElement) {
                progressModal = new bootstrap.Modal(progressModalElement);
            } else {
                console.warn('Bootstrap não disponível ou modal ausente.');
            }

            // Retry state
            const MAX_RETRIES = 5;
            let retryCount = 0;
            let retryTimeoutId = null;
            let isManuallyCancelled = false;
            let currentSource = null;
            let lastUrl = null;

            // Banner de retry (cria se não existir)
            let retryBanner = document.getElementById('retryBanner');
            if (!retryBanner) {
                retryBanner = document.createElement('div');
                retryBanner.id = 'retryBanner';
                retryBanner.className = 'alert alert-warning small mt-2 d-none';
                retryBanner.style.display = 'flex';
                retryBanner.style.justifyContent = 'space-between';
                retryBanner.innerHTML = `
      <div id="retryText">Tentando reconectar...</div>
      <div>
        <button id="cancelRetryBtn" class="btn btn-sm btn-outline-secondary ms-2">Cancelar</button>
        <button id="forceRetryBtn" class="btn btn-sm btn-primary ms-2">Tentar agora</button>
      </div>
    `;
                progressModalElement?.querySelector('.modal-body')?.prepend(retryBanner);
            }
            const retryText = retryBanner.querySelector('#retryText');
            const cancelRetryBtn = retryBanner.querySelector('#cancelRetryBtn');
            const forceRetryBtn = retryBanner.querySelector('#forceRetryBtn');

            cancelRetryBtn.addEventListener('click', () => {
                isManuallyCancelled = true;
                clearTimeout(retryTimeoutId);
                hideRetryBanner();
            });

            forceRetryBtn.addEventListener('click', () => {
                if (lastUrl) {
                    retryCount = 0;
                    isManuallyCancelled = false;
                    hideRetryBanner();
                    connectWithRetry(lastUrl);
                }
            });

            function showRetryBanner(text) {
                retryText.textContent = text;
                retryBanner.classList.remove('d-none');
            }

            function hideRetryBanner() {
                retryBanner.classList.add('d-none');
            }

            function resetModalState() {
                if (progressBar) {
                    progressBar.style.width = '0%';
                    progressBar.setAttribute('aria-valuenow', 0);
                    progressBar.textContent = '0%';
                    progressBar.classList.remove('bg-danger', 'bg-success');
                    progressBar.classList.add('bg-primary');
                }
                if (progressMessage) {
                    progressMessage.textContent = 'Iniciando integração...';
                }
                if (summaryAlert) {
                    summaryAlert.classList.add('d-none');
                    summaryAlert.textContent = '';
                    summaryAlert.classList.remove('alert-danger', 'alert-success');
                }
                if (errorDetailsContent) {
                    errorDetailsContent.textContent = '';
                }
                if (summaryTabEl && typeof bootstrap !== 'undefined') {
                    new bootstrap.Tab(summaryTabEl).show();
                }
                retryCount = 0;
                isManuallyCancelled = false;
                clearTimeout(retryTimeoutId);
                hideRetryBanner();
                if (currentSource) {
                    currentSource.close();
                    currentSource = null;
                }
            }

            if (copyDetailsBtn) {
                copyDetailsBtn.addEventListener('click', function() {
                    const text = errorDetailsContent.textContent || '';
                    if (!text) return;
                    navigator.clipboard.writeText(text).then(() => {
                        const original = this.textContent;
                        this.textContent = 'Copiado!';
                        setTimeout(() => (this.textContent = original), 1200);
                    }).catch(err => {
                        console.warn('Falha ao copiar:', err);
                    });
                });
            }

            function processEvent(progressData, source) {
                // desembrulha envelope
                if (progressData.resposta && typeof progressData.resposta === 'object') {
                    progressData = progressData.resposta;
                }

                console.log('[SSE] conteúdo normalizado:', progressData);

                // progresso visual
                if (progressData.progress !== undefined && progressBar) {
                    const p = Math.min(100, Math.max(0, progressData.progress));
                    progressBar.style.width = `${p}%`;
                    progressBar.setAttribute('aria-valuenow', p);
                    progressBar.textContent = `${p}%`;
                }

                // mensagem principal
                let displayMessage = progressData.message || '';
                if (progressData.code && progressData.message) {
                    displayMessage = `${progressData.message} (Código: ${progressData.code})`;
                }
                if (progressMessage) {
                    progressMessage.textContent = displayMessage || 'Recebido progresso.';
                }

                // limpa alert anterior
                if (summaryAlert) {
                    summaryAlert.classList.add('d-none');
                    summaryAlert.textContent = '';
                    summaryAlert.classList.remove('alert-danger', 'alert-success');
                }

                const isErrorFallback = !progressData.status && progressData.code;

                // erro
                if (progressData.status === 'error' || isErrorFallback) {
                    if (progressBar) {
                        progressBar.classList.remove('bg-primary');
                        progressBar.classList.add('bg-danger');
                    }
                    if (summaryAlert) {
                        summaryAlert.classList.remove('d-none');
                        summaryAlert.classList.add('alert-danger');
                        summaryAlert.textContent = displayMessage || 'Erro na integração.';
                    }
                    if (errorDetailsContent) {
                        errorDetailsContent.textContent = JSON.stringify(progressData.details ?? progressData, null,
                            2);
                    }
                    if (detailsTabEl && typeof bootstrap !== 'undefined') {
                        new bootstrap.Tab(detailsTabEl).show();
                    }
                    source.close();
                    return;
                }

                // sucesso
                if (progressData.status === 'success') {
                    if (progressBar) {
                        progressBar.classList.remove('bg-primary');
                        progressBar.classList.add('bg-success');
                    }
                    if (summaryAlert) {
                        summaryAlert.classList.remove('d-none');
                        summaryAlert.classList.add('alert-success');
                        summaryAlert.textContent = progressData.message || 'Concluído com sucesso.';
                    }
                    // setTimeout(() => {
                    //     if (progressModal) progressModal.hide();
                    // }, 1200);
                    source.close();
                    return;
                }

                // info intermediário
                if (progressData.details) {
                    if (errorDetailsContent) {
                        errorDetailsContent.textContent = JSON.stringify(progressData.details, null, 2);
                    }
                }
            }

            function connectWithRetry(url) {
                lastUrl = url;

                // fecha conexão anterior
                if (currentSource) {
                    currentSource.close();
                    currentSource = null;
                }

                if (isManuallyCancelled) return;

                console.log('[Integração] tentando EventSource, tentativa', retryCount + 1);
                const source = new EventSource(url);
                currentSource = source;

                source.onopen = function() {
                    console.log('[EventSource] aberto com sucesso.');
                    retryCount = 0;
                    hideRetryBanner();
                };

                source.onmessage = function(event) {
                    // limpa qualquer planejamento de retry quando recebe dados válidos
                    retryCount = 0;
                    hideRetryBanner();
                    try {
                        const progressData = JSON.parse(event.data);
                        processEvent(progressData, source);
                    } catch (err) {
                        console.error('Erro de parsing no onmessage:', err);
                        if (progressMessage) progressMessage.textContent = `Erro de parsing: ${err.message}`;
                        if (progressBar) {
                            progressBar.classList.remove('bg-primary');
                            progressBar.classList.add('bg-danger');
                        }
                        if (summaryAlert) {
                            summaryAlert.classList.remove('d-none');
                            summaryAlert.classList.add('alert-danger');
                            summaryAlert.textContent = `Resposta inválida: ${err.message}`;
                        }
                        if (errorDetailsContent) errorDetailsContent.textContent = event.data;
                        if (detailsTabEl && typeof bootstrap !== 'undefined') {
                            new bootstrap.Tab(detailsTabEl).show();
                        }
                        source.close();
                    }
                };

                source.onerror = function(err) {
                    console.warn('[EventSource] erro de conexão:', err);
                    // se esgotou tentativas ou cancelado
                    if (retryCount >= MAX_RETRIES || isManuallyCancelled) {
                        if (summaryAlert) {
                            summaryAlert.classList.remove('d-none');
                            summaryAlert.classList.add('alert-danger');
                            summaryAlert.textContent =
                                'Não foi possível conectar ao stream após múltiplas tentativas.';
                        }
                        if (progressBar) {
                            progressBar.classList.remove('bg-primary');
                            progressBar.classList.add('bg-danger');
                        }
                        if (detailsTabEl && typeof bootstrap !== 'undefined') {
                            new bootstrap.Tab(detailsTabEl).show();
                        }
                        source.close();
                        return;
                    }

                    // backoff exponencial com jitter
                    const baseDelay = 1000 * Math.pow(2, retryCount);
                    const jitter = Math.round(baseDelay * 0.25 * (Math.random() * 2 - 1)); // ±25%
                    const delay = Math.min(30000, baseDelay + jitter); // cap em 30s

                    retryCount += 1;
                    let remaining = Math.ceil(delay / 1000);
                    showRetryBanner(
                        `Reconectando em ${remaining}s (tentativa ${retryCount}/${MAX_RETRIES})...`);

                    // countdown visual
                    const countdownInterval = setInterval(() => {
                        remaining -= 1;
                        if (remaining <= 0) {
                            clearInterval(countdownInterval);
                        } else {
                            retryText.textContent =
                                `Reconectando em ${remaining}s (tentativa ${retryCount}/${MAX_RETRIES})...`;
                        }
                    }, 1000);

                    retryTimeoutId = setTimeout(() => {
                        hideRetryBanner();
                        connectWithRetry(url);
                    }, delay);

                    source.close();
                };

                return source;
            }

            form.addEventListener('submit', function(event) {
                event.preventDefault();
                console.log('[Integração] submit interceptado');

                resetModalState();
                if (progressModal) progressModal.show();

                const formData = new FormData(form);
                const urlParams = new URLSearchParams(formData);
                urlParams.delete('_token');
                const url = form.action + '?' + urlParams.toString();

                // inicia conexão com retry
                retryCount = 0;
                isManuallyCancelled = false;
                currentSource = connectWithRetry(url);

                // fecha se modal for fechado
                if (progressModalElement) {
                    progressModalElement.addEventListener('hidden.bs.modal', function() {
                        console.log('Modal fechado, encerrando EventSource.');
                        if (currentSource) currentSource.close();
                    }, {
                        once: true
                    });
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function() {

            let categoriasTikTok = []; // vai popular da API

            const selectOriginal = document.getElementById('select-categoria-tiktok');
            const display = document.getElementById('category-display');
            const label = document.getElementById('category-label');
            const dropdown = document.getElementById('category-dropdown');

            // monta mapa por id pra navegar pai → filho
            function buildMap(arr) {
                const map = {};
                arr.forEach(c => {
                    map[c.id] = {
                        ...c
                    }; // clone
                });
                return map;
            }

            // Adicione esta função em seu código, antes de 'loadTikTokCategories' e de qualquer chamada a ela.
            function renderDropdown(items) {
                // Limpa o conteúdo do dropdown antes de renderizar
                dropdown.innerHTML = '';

                // Itera sobre as categorias para criar os elementos visuais
                items.forEach(cat => {
                    // Cria o elemento para o item do dropdown
                    const item = document.createElement('div');
                    item.classList.add('dropdown-item');

                    // Adiciona um listener para atualizar o campo de exibição quando a categoria é selecionada
                    item.addEventListener('click', () => {
                        selectOriginal.value = cat.id;
                        display.textContent = buildDisplayText(cat.full_path);
                        closeDropdown();
                    });

                    // Cria o ícone (bolinha)
                    const dot = document.createElement('span');
                    // A classe base 'dot' define o tamanho e formato do marcador.
                    dot.classList.add('dot');

                    // Lógica para colorir a bolinha com base no permission_statuses
                    if (cat.permission_statuses && cat.permission_statuses.includes('AVAILABLE')) {
                        // Se o status for "AVAILABLE", adicione a classe 'status-available' (verde)
                        dot.classList.add('status-available');
                    } else {
                        // Caso contrário, use a classe 'status-default' (cinza)
                        dot.classList.add('status-default');
                    }
                    item.appendChild(dot);

                    // Adiciona o texto da categoria
                    const text = document.createElement('span');
                    // A função buildDisplayText foi ajustada para lidar com valores undefined
                    text.textContent = buildDisplayText(cat.full_path || cat.local_name);
                    item.appendChild(text);

                    // Adiciona o item completo ao dropdown
                    dropdown.appendChild(item);
                });
            }
            // sobe a árvore para montar full_path (ex: Pai → Filho → Atual)
            function computeFullPath(cat, map) {
                const parts = [];
                let cursor = cat;
                const visited = new Set(); // evitar loop infinito
                while (cursor) {
                    if (visited.has(cursor.id)) break;
                    visited.add(cursor.id);
                    parts.unshift(cursor.local_name || '');
                    if (!cursor.parent_id || cursor.parent_id === '0') break;
                    cursor = map[cursor.parent_id];
                }
                return parts.join(' - ');
            }

            function buildDisplayText(fullPath) {
                // Verificação para garantir que fullPath não é null, undefined, ou vazio.
                // Se não for uma string válida, retorna uma string vazia para evitar o erro.
                if (typeof fullPath === 'string' && fullPath.length > 0) {
                    return fullPath.replace(/ - /g, ' → ');
                }
                return ''; // Retorna uma string vazia se o path não for válido.
            }

            function openDropdown() {
                dropdown.classList.remove('d-none');
            }

            function closeDropdown() {
                dropdown.classList.add('d-none');
            }

            display.addEventListener('click', (e) => {
                e.stopPropagation();
                if (dropdown.classList.contains('d-none')) {
                    renderDropdown(categoriasTikTok);
                    openDropdown();
                } else {
                    closeDropdown();
                }
            });

            // fecha ao clicar fora
            document.addEventListener('click', (e) => {
                if (!display.contains(e.target) && !dropdown.contains(e.target)) {
                    closeDropdown();
                }
            });

            // se select for atualizado externamente (por AJAX), re-renderiza
            const observer = new MutationObserver(() => {
                // Apenas renderiza o dropdown novamente usando o array que já foi populado
                // com todos os dados.
                if (categoriasTikTok.length > 0) {
                    renderDropdown(categoriasTikTok);
                }
            });
            observer.observe(selectOriginal, {
                childList: true
            });

            // Carrega categorias da API e popula o <select> e dropdown
            function loadTikTokCategories() {
                fetch('/api/v1/tiktok/categories', {
                        headers: {
                            'Accept': 'application/json'
                        }
                    })
                    .then(r => {
                        if (!r.ok) return r.json().then(err => {
                            throw new Error(err.message || 'Erro');
                        });
                        return r.json();
                    })
                    .then(data => {
                        // pega a lista certa
                        const raw = Array.isArray(data) ? data :
                            Array.isArray(data.categories) ? data.categories :
                            [];

                        // agora só chame buildMap se raw for array
                        const map = buildMap(raw);

                        categoriasTikTok = raw.map(cat => {
                            if (!cat.full_path) {
                                cat.full_path = computeFullPath(cat, map);
                            }
                            return cat;
                        });

                        // popula o select original para o form (opcional: limpar antes)
                        selectOriginal.innerHTML =
                            '<option selected disabled value="">Selecionar</option>';
                        categoriasTikTok.forEach(cat => {
                            const opt = document.createElement('option');
                            opt.value = cat.id;
                            opt.textContent = buildDisplayText(cat.full_path);
                            selectOriginal.appendChild(opt);
                        });

                        // Agora, renderDropdown receberá os dados completos
                        renderDropdown(categoriasTikTok);
                    })
                    .catch(err => {
                        console.error('Erro carregando categorias TikTok:', err);
                        dropdown.innerHTML =
                            '<div class="dropdown-item">Erro ao carregar categorias</div>';
                    });
            }

            // chama após DOM pronto:
            window.addEventListener('DOMContentLoaded', loadTikTokCategories);

            let currentProductIdForModal =
                null; // Variável global para armazenar o ID do produto selecionado

            // Funções auxiliares (mantidas globais ou no escopo do DOMContentLoaded)
            function calculaPorcemtagem(valor, porcem) {
                return valor * (porcem / 100);
            }

            // Esta função agora será mais genérica e poderá ser chamada por qualquer formulário para atualizar seu preço
            function updatePriceLogicForForm($form) {
                const tipoAgregado = $form.find('.select-valor-agregado').val();
                let valorAgregado = parseFloat($form.find('.input-valor-agregado').val().replace(',',
                    '.')) || 0;
                let precoFixo = parseFloat($form.find('.input-preco-fixo').val().replace(',', '.')) ||
                    0;

                let basePrice = $form.data('basePrice') ||
                    0; // Pega o preço base do data attribute do formulário

                let novoValor = basePrice;

                if ($form.find('.checkbox-preco-fixo').is(':checked')) {
                    novoValor = (precoFixo > 0) ? precoFixo : basePrice;
                } else {
                    if (tipoAgregado === 'acrescimo_reais') {
                        novoValor = basePrice + valorAgregado;
                    } else if (tipoAgregado === 'acrescimo_porcentagem') {
                        novoValor = basePrice + (basePrice * (valorAgregado / 100));
                    } else if (tipoAgregado === 'desconto_reais') {
                        novoValor = basePrice - valorAgregado;
                    } else if (tipoAgregado === 'desconto_porcentagem') {
                        novoValor = basePrice - (basePrice * (valorAgregado / 100));
                    }
                }

                if (novoValor < 0) novoValor = 0;
                $form.find('.input-valor-produto-display').val(novoValor.toFixed(2).replace('.', ','));
            }

            // Esta função agora será mais genérica e poderá ser chamada por qualquer formulário para atualizar seu progresso de título
            function updateTitleProgressForForm($form) {
                const $inputName = $form.find('.input-name');
                const $contador = $form.find('.contador');
                const $progressBar = $form.find('.progress-bar-input');

                var caracteresDigitados = $inputName.val().length;
                $contador.text(caracteresDigitados + '/60');
                var progresso = (caracteresDigitados / 60) * 100;
                $progressBar.css('width', progresso + '%').attr('aria-valuenow', progresso);

                if (caracteresDigitados > 60) {
                    $inputName.val($inputName.val().substring(0, 60));
                    $contador.text('60/60');
                    alert("O título não pode exceder 60 caracteres.");
                }
            }

            // Função genérica para carregar categorias e atributos para um formulário específico
            function loadCategoriesAndAttributesForForm($form, initialCategory = null) {
                const formType = $form.data('integration-type');
                const $selectCategorias = $form.find('.select-categorias');
                const $contentCategorias = $form.find('.content_categorias');
                const $inputIdCategoria = $form.find('.input-id-categoria');
                const $formContainer = $form.find('.formContainer');

                let categoriesUrl = '';
                let subcategoriesUrlBase = '';
                let attributesUrlBase = '';

                if (formType === 'mercadolivre') {
                    categoriesUrl = "/meli/categories";
                    subcategoriesUrlBase = "/meli/subcategories/";
                    attributesUrlBase = "/meli/subcategories/attributes/";
                } else if (formType === 'tiktok') {
                    categoriesUrl = "/tiktok/categories";
                    subcategoriesUrlBase = "/tiktok/subcategories/";
                    attributesUrlBase = "/tiktok/subcategories/attributes/";
                }

                if (!categoriesUrl) return;

                $.ajax({
                    url: categoriesUrl,
                    type: "GET",
                    success: function(response) {
                        if (response) {
                            let options = ['<option selected disabled>Selecionar</option>'];
                            $.each(response, function(i, item) {
                                options.push('<option class="option-size" value="' +
                                    item.id +
                                    '">' + item.name + '</option>');
                            });
                            $selectCategorias.html(options.join(''));

                            if (initialCategory) {
                                $selectCategorias.val(initialCategory);
                                // Chamar a função para carregar subcategorias/atributos diretamente para o contexto do form
                                loadSubcategoriesAndAttributes($selectCategorias,
                                    $contentCategorias,
                                    $inputIdCategoria, $formContainer, formType,
                                    initialCategory);
                            }

                            // Remove eventos anteriores para evitar duplicação antes de anexar o novo
                            $selectCategorias.off('change').on('change', function() {
                                let ids = $(this).children("option:selected").val();
                                let name = $(this).children("option:selected")
                                    .text();
                                let content_category_item =
                                    '<li class="list-group-item">' +
                                    name + '</li>';
                                $contentCategorias.append(content_category_item);
                                $inputIdCategoria.val(ids);
                                loadSubcategoriesAndAttributes($selectCategorias,
                                    $contentCategorias, $inputIdCategoria,
                                    $formContainer,
                                    formType, ids);
                            });
                        }
                    },
                    error: function(error) {
                        console.error(`Erro ao carregar categorias para ${formType}:`,
                            error);
                    }
                });

                function loadSubcategoriesAndAttributes($selectCategoriesElem, $contentCategoriesElem,
                    $inputIdCategoryElem, $formContainerElem, formType, category) {
                    let subcategoriesUrlBase = '';
                    let attributesUrlBase = '';

                    if (formType === 'mercadolivre') {
                        subcategoriesUrlBase = "/meli/subcategories/";
                        attributesUrlBase = "/meli/subcategories/attributes/";
                    } else if (formType === 'tiktok') {
                        subcategoriesUrlBase = "/tiktok/subcategories/";
                        attributesUrlBase = "/tiktok/subcategories/attributes/";
                    }

                    $.ajax({
                        url: subcategoriesUrlBase + category,
                        type: "GET",
                        success: function(response) {
                            if (response && response.children_categories && response
                                .children_categories
                                .length > 0) {
                                let subOptions = [
                                    '<option class="option-size" selected disabled>Selecionar Subcategoria</option>'
                                ];
                                $.each(response.children_categories, function(i, item) {
                                    subOptions.push(
                                        '<option class="option-size" value="' +
                                        item
                                        .id + '">' + item.name + '</option>');
                                });
                                $selectCategoriesElem.html(subOptions.join(''));
                                $formContainerElem.empty();
                            } else {
                                $.ajax({
                                    url: attributesUrlBase + category,
                                    type: "GET",
                                    success: function(responseAttributes) {
                                        $formContainerElem
                                            .empty(); // Limpa antes de adicionar novos campos

                                        if (responseAttributes &&
                                            responseAttributes
                                            .length > 0) {
                                            let h2 = document.createElement(
                                                "h2");
                                            h2.textContent =
                                                "Campos Obrigatórios da Categoria";
                                            $formContainerElem.append(h2);

                                            const requiredAttributeNames = [
                                                'BRAND',
                                                'MODEL', 'LENGTH', 'HEIGHT'
                                            ];
                                            responseAttributes.forEach(
                                                element => {
                                                    if (element.tags &&
                                                        element.tags
                                                        .required ===
                                                        true && !
                                                        requiredAttributeNames
                                                        .includes(
                                                            element.id)) {
                                                        let label = document
                                                            .createElement(
                                                                "label");
                                                        label.textContent =
                                                            element
                                                            .name + ':';
                                                        $formContainerElem
                                                            .append(
                                                                label);

                                                        if (!element
                                                            .values ||
                                                            element
                                                            .values
                                                            .length === 0) {
                                                            let input =
                                                                document
                                                                .createElement(
                                                                    "input"
                                                                );
                                                            input.type =
                                                                "text";
                                                            input
                                                                .className =
                                                                "form-control";
                                                            input.name =
                                                                element.id;
                                                            input.required =
                                                                true;
                                                            $formContainerElem
                                                                .append(
                                                                    input);
                                                        } else {
                                                            let selectField =
                                                                document
                                                                .createElement(
                                                                    "select"
                                                                );
                                                            selectField
                                                                .className =
                                                                "form-control";
                                                            selectField
                                                                .name =
                                                                element
                                                                .id;
                                                            selectField
                                                                .required =
                                                                true;
                                                            let defaultOption =
                                                                document
                                                                .createElement(
                                                                    "option"
                                                                );
                                                            defaultOption
                                                                .text =
                                                                "Selecione...";
                                                            defaultOption
                                                                .value = "";
                                                            defaultOption
                                                                .disabled =
                                                                true;
                                                            defaultOption
                                                                .selected =
                                                                true;
                                                            selectField
                                                                .appendChild(
                                                                    defaultOption
                                                                );

                                                            element.values
                                                                .forEach(
                                                                    val => {
                                                                        let option =
                                                                            document
                                                                            .createElement(
                                                                                "option"
                                                                            );
                                                                        option
                                                                            .text =
                                                                            val
                                                                            .name;
                                                                        option
                                                                            .value =
                                                                            val
                                                                            .id;
                                                                        selectField
                                                                            .appendChild(
                                                                                option
                                                                            );
                                                                    });
                                                            $formContainerElem
                                                                .append(
                                                                    selectField
                                                                );
                                                        }
                                                    }
                                                });
                                        }
                                    },
                                    error: function(error) {
                                        console.error(
                                            `Erro ao carregar atributos para ${formType}:`,
                                            error);
                                        $formContainerElem.html(
                                            '<p class="text-danger">Não foi possível carregar os atributos para esta categoria.</p>'
                                        );
                                    }
                                });
                            }
                        },
                        error: function(error) {
                            console.error(
                                `Erro ao carregar subcategorias para ${formType}:`,
                                error);
                        }
                    });
                }
            }


            // --------------------------------------------------------------------
            // LÓGICA DE INICIALIZAÇÃO PARA CADA FORMULÁRIO (CHAMADA UMA VEZ NO DOMContentLoaded)
            // --------------------------------------------------------------------
            $('.integracao-form, .integracao-formtiktok').each(function() {
                const $form = $(this); // O formulário atual no loop

                // Aplica máscaras aos inputs de preço do formulário específico
                $form.find('.input-preco-final').mask('000.000.000,00', {
                    reverse: true
                });
                $form.find('.input-preco-fixo').mask('000.000.000,00', {
                    reverse: true
                });
                $form.find('.input-valor-agregado').mask('000.000.000,00', {
                    reverse: true
                });

                // Anexa eventos para os elementos DENTRO DESTE formulário específico
                $form.find('.input-name').on('keyup', function() {
                    updateTitleProgressForForm($form);
                });

                $form.find('.checkbox-preco-fixo').on('change', function() {
                    if ($(this).is(':checked')) {
                        $form.find('.input-preco-fixo').prop('disabled', false);
                        $form.find('.input-valor-agregado').val('');
                        $form.find('.input-valor-agregado').prop('disabled', true);
                        $form.find('.select-valor-agregado').prop('disabled', true);
                        $form.find('.input-is-porcem').val('0');
                    } else {
                        $form.find('.input-preco-fixo').prop('disabled', true);
                        $form.find('.input-preco-fixo').val('');
                        $form.find('.input-valor-agregado').prop('disabled', false);
                        $form.find('.select-valor-agregado').prop('disabled', false);
                    }
                    updatePriceLogicForForm($form);
                });

                $form.find('.select-valor-agregado').on('change', function() {
                    const selectedOption = $(this).val();
                    switch (selectedOption) {
                        case 'acrescimo_reais':
                            $form.find('.input-valor-agregado').attr('placeholder',
                                'Digite o acréscimo em R$');
                            $form.find('.input-is-porcem').val('0');
                            break;
                        case 'acrescimo_porcentagem':
                            $form.find('.input-valor-agregado').attr('placeholder',
                                'Digite o acréscimo em %');
                            $form.find('.input-is-porcem').val('1');
                            break;
                        case 'desconto_reais':
                            $form.find('.input-valor-agregado').attr('placeholder',
                                'Digite o desconto em R$');
                            $form.find('.input-is-porcem').val('0');
                            break;
                        case 'desconto_porcentagem':
                            $form.find('.input-valor-agregado').attr('placeholder',
                                'Digite o desconto em %');
                            $form.find('.input-is-porcem').val('1');
                            break;
                        default:
                            $form.find('.input-valor-agregado').attr('placeholder',
                                'Digite o valor');
                            $form.find('.input-is-porcem').val('0');
                            break;
                    }
                    updatePriceLogicForForm($form);
                });

                $form.find('.input-valor-agregado').on('input', function() {
                    updatePriceLogicForForm($form);
                });
                $form.find('.input-preco-fixo').on('input', function() {
                    updatePriceLogicForForm($form);
                });

                // $form.find('.botao_integracao').on('click', function(event) {
                //     $form.find('.loading-integracao').removeClass('d-none');
                // });

                // $form.on('submit', function(event) {
                //     $form.find('.botao_integracao').addClass('d-none');
                //     $form.find('.loading-integracao').removeClass('d-none');
                // });


                // Listener para quando um painel do acordeão é mostrado dentro do modal
                // (Isso é para quando o usuário alterna entre Mercado Livre e TikTok DENTRO do modal)
                $form.closest('.accordion-item').on('shown.bs.collapse', function() {
                    const id_produto_ativo =
                        currentProductIdForModal; // Pega o ID do produto global
                    if (!id_produto_ativo) {
                        console.warn(
                            'Nenhum produto selecionado para carregar dados ao ativar acordeão.'
                        );
                        return;
                    }

                    // Exibe o spinner do formulário específico do acordeão
                    $form.find('.spinner-overlay').removeClass('d-none');

                    // Carrega os dados do produto novamente para garantir a categoria correta e preencher o formulário ativo
                    $.ajax({
                        url: "/api/v1/product/" + id_produto_ativo,
                        type: "GET",
                        success: function(response) {
                            $form.find('.spinner-overlay').addClass(
                                'd-none'); // Esconde o spinner do form
                            if (response) {
                                // Atualiza os dados do cartão de produto (imagem, título, ean, preço) para ESTE formulário
                                $form.find('.img_integracao_foto').attr(
                                    'src', response
                                    .image);
                                $form.find('.img_integracao_title').text(
                                    response
                                    .title);
                                $form.find('.img_integracao_ean').text(
                                    "EAN : " +
                                    response.ean);
                                $form.find('.img_integracao_price').text(
                                    "Preço: " +
                                    parseFloat(response.priceWithFee)
                                    .toFixed(2)
                                    .replace('.', ','));
                                $form.find('.linkMaterial').attr("href",
                                    response.link);

                                // Atualiza campos de input do formulário ativo
                                $form.find('.input-name').val(response
                                    .title);
                                $form.find('.textarea-editor').val(response
                                    .description);
                                $form.find('.input-preco-final').val(
                                    parseFloat(response
                                        .priceWithFee).toFixed(2)
                                    .replace('.', ','));
                                $form.find('.input-id-prodenv').val(
                                    id_produto_ativo);

                                // Define o preço base para este formulário e recalcula
                                $form.data('basePrice', parseFloat(response
                                    .priceWithFee) || 0);
                                updatePriceLogicForForm($form);
                                updateTitleProgressForForm($form);

                                // Recarrega categorias e atributos para o formulário ativo
                                $form.find('.content_categorias').empty();
                                $form.find('.formContainer').empty();
                                const activeFormIntegrationType = $form
                                    .data(
                                        'integration-type');
                                loadCategoriesAndAttributesForForm(
                                    $form, // Passa o objeto $form para as funções de categoria
                                    activeFormIntegrationType,
                                    response.category_id
                                );
                            }
                        },
                        error: function(error) {
                            $form.find('.spinner-overlay').addClass(
                                'd-none'); // Esconde o spinner do form
                            console.error(
                                'Erro ao recarregar dados do produto para formulário ativo no acordeão:',
                                error);
                        }
                    });

                    // Recarrega o histórico para o formulário ativo
                    const id_user = $('#id_user').val();
                    $.ajax({
                        url: "/api/v1/getHistoryById",
                        type: "GET",
                        data: {
                            id: id_produto_ativo,
                            id_user: id_user
                        },
                        success: function(response) {
                            if (response && response.dados) {
                                $form.find('.adicionarHistorico').empty();
                                let historyItems = [];
                                $.each(response.dados, function(i, item) {
                                    historyItems.push(
                                        '<li class="list-group-item"> Nome: ' +
                                        item.name + ' | ID: ' +
                                        item.id_ml +
                                        ' | Criado em : ' + item
                                        .created_at + '</li>');
                                });
                                $form.find('.adicionarHistorico').append(
                                    historyItems
                                    .reverse().join(''));
                            }
                        },
                        error: function(error) {
                            console.error(
                                'Erro ao recarregar histórico para formulário ativo no acordeão:',
                                error);
                        }
                    });
                });
            }); // Fim do .each(initializeFormLogic)

            // --------------------------------------------------------------------
            // LÓGICA GLOBAL PARA ABRIR O MODAL E CARREGAR DADOS INICIAIS
            // --------------------------------------------------------------------

            // Manipulador global para o clique nos botões "Integrar" (fora do loop each)
            $('.btn-integrar-produto').on('click', function() {
                const $currentProductCard = $(this).closest('.product-card-section');
                const id_produto = $currentProductCard.find('.id_product').text();
                currentProductIdForModal =
                    id_produto; // Armazena o ID para ser usado no shown.bs.modal

                // Limpa todos os formulários ANTES de abrir o modal.
                $('.integracao-form, .integracao-formtiktok').each(function() {
                    this.reset();
                    const $currentForm = $(this);
                    $currentForm.find('.content_categorias').empty();
                    $currentForm.find('.formContainer').empty();
                    $currentForm.find('.img_integracao_foto').attr('src', '');
                    $currentForm.find('.img_integracao_title').empty();
                    $currentForm.find('.img_integracao_ean').empty();
                    $currentForm.find('.img_integracao_price').empty();
                    $currentForm.find('.adicionarHistorico').empty();
                    $currentForm.find('.input-preco-fixo').val('').prop('disabled',
                        true);
                    $currentForm.find('.select-valor-agregado').val('');
                    $currentForm.find('.input-valor-agregado').val('').prop('disabled',
                        false).attr(
                        'placeholder', 'Digite o valor');
                    $currentForm.find('.checkbox-preco-fixo').prop('checked', false);
                    $currentForm.find('.input-is-porcem').val('0');
                });
            });


            // Manipulador global para o evento 'shown.bs.modal'
            $('#exampleModalToggle').on('shown.bs.modal', function(e) {
                const id_produto = currentProductIdForModal;
                const id_user = $('#id_user').val();
                const $loadingApiModal = $('.spinner-overlay'); // Spinner global do modal

                if (!id_produto) {
                    console.warn(
                        'Nenhum ID de produto encontrado para carregar ao mostrar o modal.');
                    $loadingApiModal.addClass('d-none');
                    return;
                }

                $loadingApiModal.removeClass('d-none'); // Mostra o spinner global do modal

                // Faz as chamadas AJAX para os dados do produto e histórico
                $.when(
                    $.ajax({
                        url: "/api/v1/product/" + id_produto,
                        type: "GET"
                    }),
                    $.ajax({
                        url: "/api/v1/getHistoryById",
                        type: "GET",
                        data: {
                            id: id_produto,
                            id_user: id_user
                        }
                    })
                ).done(function(productResponse, historyResponse) {
                    // Pequeno delay para garantir que o DOM esteja completamente renderizado e visível
                    setTimeout(function() {
                        $loadingApiModal.addClass(
                            'd-none'); // Esconde o spinner global

                        const productData = productResponse[0];
                        const historyData = historyResponse[0];

                        // Itera sobre todos os formulários de integração dentro do modal
                        $('.integracao-form').each(function() {
                            const $formInsideModal = $(this);

                            // Preenche os dados do CARD DO PRODUTO para TODOS os formulários no modal (eles compartilham a visualização do produto)
                            $formInsideModal.find('.input-id-prodenv')
                                .val(
                                    id_produto);
                            $formInsideModal.find(
                                '.img_integracao_foto').attr(
                                'src', productData.image);
                            $formInsideModal.find(
                                '.img_integracao_title').text(
                                productData.title);
                            $formInsideModal.find('.img_integracao_ean')
                                .text(
                                    "EAN : " + productData.ean);
                            $formInsideModal.find(
                                '.img_integracao_price').text(
                                "Preço: " + parseFloat(productData
                                    .priceWithFee)
                                .toFixed(2).replace('.', ','));
                            $formInsideModal.find('.linkMaterial').attr(
                                "href",
                                productData.link);

                            // Preenche os campos de input, textareas e recalcula APENAS para o formulário ATIVO/VISÍVEL
                            // Ao abrir o modal, o primeiro acordeão (Mercado Livre) geralmente está ativo.
                            if ($formInsideModal.closest(
                                    '.accordion-collapse')
                                .hasClass('show')) {
                                $formInsideModal.find('.input-name')
                                    .val(productData
                                        .title);
                                $formInsideModal.find(
                                    '.textarea-editor').val(
                                    productData.description);
                                $formInsideModal.find(
                                    '.input-preco-final').val(
                                    parseFloat(productData
                                        .priceWithFee)
                                    .toFixed(2).replace('.', ','));

                                // Define o preço base no data attribute do formulário ativo e aciona a lógica de preço
                                $formInsideModal.data('basePrice',
                                    parseFloat(
                                        productData.priceWithFee) ||
                                    0);
                                updatePriceLogicForForm(
                                    $formInsideModal
                                ); // Chama com o contexto do form
                                updateTitleProgressForForm(
                                    $formInsideModal
                                ); // Chama com o contexto do form

                                // Carrega categorias e atributos para o formulário ativo
                                $formInsideModal.find(
                                        '.content_categorias')
                                    .empty();
                                $formInsideModal.find('.formContainer')
                                    .empty();
                                const integrationTypeForActiveForm =
                                    $formInsideModal.data(
                                        'integration-type');
                                loadCategoriesAndAttributesForForm(
                                    $formInsideModal, // Passa o objeto $formInsideModal
                                    integrationTypeForActiveForm,
                                    productData.category_id
                                );

                                // Preenche o histórico para o formulário ativo
                                if (historyData && historyData.dados) {
                                    $formInsideModal.find(
                                            '.adicionarHistorico')
                                        .empty();
                                    let historyItems = [];
                                    $.each(historyData.dados, function(
                                        i, item) {
                                        historyItems.push(
                                            '<li class="list-group-item"> Nome: ' +
                                            item.name +
                                            ' | ID: ' + item
                                            .id_ml +
                                            ' | Criado em : ' +
                                            item
                                            .created_at +
                                            '</li>');
                                    });
                                    $formInsideModal.find(
                                            '.adicionarHistorico')
                                        .append(historyItems.reverse()
                                            .join(''));
                                } else {
                                    console.warn(
                                        'Dados do histórico não foram carregados ou estão vazios para o formulário ativo.'
                                    );
                                }
                            }
                        });

                    }, 100); // Pequeno delay de 100ms
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    $loadingApiModal.addClass('d-none');
                    console.error('Erro ao carregar dados do produto ou histórico:',
                        textStatus,
                        errorThrown);
                    alert('Erro ao carregar dados do produto ou histórico.');
                });
            });

            // 8. Inicializa o campo de preço do filtro se houver um valor
            $('#preco').mask('000.000.000,00', {
                reverse: true
            });




        }); // Fim do DOMContentLoaded
    </script>
