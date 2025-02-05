@extends('layouts.app')
@section('title', $viewData['title'])
@section('conteudo')
    <style>
            /* Card Principal */
    .product-form-card {
        background-color: #fff;
        border: 1px solid #e5e5e5;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 15px;
    }

    /* Labels */
    .form-label {
        font-weight: bold;
        color: #333;
    }

    /* Inputs e Selects */
    .form-control, .form-select {
        border-radius: 6px;
        box-shadow: none;
        border-color: #ccc;
    }

    /* Seções do Formulário */
    .form-section {
        border-top: 1px solid #ddd;
        margin-top: 20px;
        padding-top: 20px;
    }

    /* Botão Principal */
    .btn-success {
        border-radius: 6px;
        font-weight: bold;
    }

    /* Loading Spinner */
    .loading-integracao {
        width: 2.5rem;
        height: 2.5rem;
    }

    /* Ajuste para Alinhamento */
    .row .col {
        padding-bottom: 15px;
    }
           .product-title {
        display: -webkit-box;
        -webkit-line-clamp: 2; /* Limita a 2 linhas */
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis; /* Adiciona reticências */
        font-size: 1rem;
        font-weight: bold;
        color: #333;
        margin-bottom: 8px;
        line-height: 1.4rem;
    }
            /* Botões de ação */
    .btn-primary, .btn-success {
        font-weight: bold;
        text-transform: uppercase;
        border: none;
        padding: 6px 12px;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background: #4C566A;
        color: #fff;
        transform: scale(1.05);
    }

    .btn-success:hover {
        background: #218838;
        color: #fff;
        transform: scale(1.05);
    }
            /* Botões principais */
       /* Estilo do card de produto */
       .product-card {
        background-color: #fff; /* Fundo branco */
        border: 1px solid #e5e5e5;
        border-radius: 10px;
        padding: 15px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }

    .product-card:hover {
        transform: scale(1.03); /* Efeito ao passar o mouse */
    }

    /* Imagem do produto */
    .product-img {
        width: 100%;
        max-height: 200px;
        object-fit: contain;
        margin-bottom: 10px;
    }

    /* Etiqueta de desconto e novidade */
    .badge-discount {
        position: absolute;
        top: 10px;
        left: 10px;
        background-color: #FF5252;
        color: white;
        font-size: 0.8rem;
        border-radius: 4px;
        padding: 2px 8px;
    }

    .badge-new {
        position: absolute;
        top: 10px;
        right: 10px;
        background-color: #FF9800;
        color: white;
        font-size: 0.8rem;
        border-radius: 4px;
        padding: 2px 8px;
    }

    /* Preços */
    .product-price {
        font-size: 1.2rem;
        font-weight: bold;
        color: #E91E63; /* Cor vermelha para preço */
    }

    .product-price-old {
        text-decoration: line-through;
        color: #9E9E9E;
        font-size: 0.9rem;
    }

    /* Ícones e ações */
    .product-actions i {
        color: #777;
        font-size: 1rem;
        margin-right: 10px;
        cursor: pointer;
        transition: color 0.2s;
    }

    .product-actions i:hover {
        color: #E91E63;
    }

    /* Avaliação */
    .product-rating {
        color: #FFD700;
        font-size: 0.9rem;
    }
    </style>

    @if ($errors->any())
        <ul class="alert alert-danger list-unstyled">
            @foreach ($errors->all() as $error)
                <li>-> {{ $error }}</li>
            @endforeach
        </ul>
    @endif

    @if (session('msg'))
        <div class="alert alert-success" role="alert">
            {{ session('msg') }}
            {{ session()->forget('msg') }}
        </div>
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

    <!--- MODAL QUE SELECIONA O MOTORISTA --->

    <div class="modal fade" id="exampleModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalToggleLabel">Integrações MarketPlace</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="accordion accordion-flush" id="accordionFlushExample">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-headingOne">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#flush-collapseOne" aria-expanded="false"
                                    aria-controls="flush-collapseOne">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked"
                                            checked>
                                        <label class="form-check-label" for="flexCheckChecked">
                                            Mercado Livre
                                        </label>
                                    </div>
                                </button>
                            </h2>

                            <div class="spinner-overlay" id="loading-api">
                                <div class="spinner-border spinner-big text-light" role="status">
                                    <span class="visually-hidden">Carregando...</span>
                                </div>
                            </div>

                            <div id="flush-collapseOne" class="accordion-collapse collapse"
                                aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">

                                <div class="accordion-body">
                                    <form method="POST" action="{{ route('IntegrarProduto') }}" enctype="multipart/form-data">
                                        @csrf

                                        <!-- Card do Produto -->
                                        <div class="card mb-4 product-form-card">
                                            <div class="row g-0">
                                                <!-- Imagem do Produto -->
                                                <div class="col-md-4 text-center">
                                                    <img class="img-fluid rounded-start img_integracao_foto" alt="Produto">
                                                </div>
                                                <!-- Informações do Produto -->
                                                <div class="col-md-8">
                                                    <div class="card-body">
                                                        <h5 class="card-title img_integracao_title">Nome do Produto</h5>
                                                        <p class="card-text img_integracao_ean">EAN: 123456789</p>
                                                        <p class="card-text img_integracao_price">Preço: R$ 0,00</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <input type="hidden" class="form-control" name="id_prodenv" id="id_prodenv">
                                        <!-- Campo Nome -->
                                        <div class="row">
                                            <div class="col-md-12">
                                                Título do Anúncio: <div id="contador" class="text-end">0/60</div>
                                                <input type="text" class="form-control" name="name" id="name" placeholder="Digite o nome do produto">
                                                <div class="progress mt-2">
                                                    <div id="progress-bar" class="progress-bar" role="progressbar" style="width: 0%;"
                                                        aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Campo Descrição -->
                                        <div class="row form-section">
                                            <div class="col-md-12">
                                                <label class="form-label" for="editor">Descrição do Anúncio</label>
                                                <textarea name="editor" id="editor" rows="4" class="form-control" placeholder="Digite a descrição"></textarea>
                                            </div>
                                        </div>

                                        <div class="row form-section gap-3">
                                            <div class="col-md-6 mb-4">
                                                <p class="col-lg-4 col-md-6 col-sm-12 col-form-label">Tipo de Anúncio</p>
                                                <div class="col">
                                                    <div class="mb-3 row">
                                                        <select name="tipo_anuncio" class="form-control" aria-label=".form-select-sm example" required>
                                                            <option value="gold_special">Clássico</option>
                                                            <option value="gold_pro">Premium</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-5 mb-4">
                                                <p class="col-lg-4 col-md-12 col-sm-12 col-form-label">Material de Apoio</p>
                                                <div class="col">
                                                    <a class="linkMaterial btn btn-success" id="linkMaterial" target="_blank">Baixar Material de Apoio</a>
                                                </div>
                                            </div>
                                        </div>


                                        <!-- Valor Agregado -->
                                        <div class="row form-section">
                                            <div class="col-md-6">
                                                <label class="form-label" for="editValorAgregado">Valor Agregado</label>
                                                <select id="editValorAgregado" class="form-select" name="valor_tipo" required>
                                                    <option value="">Selecione uma opção</option>
                                                    <option value="acrescimo_reais">Acréscimo R$</option>
                                                    <option value="acrescimo_porcentagem">Acréscimo %</option>
                                                    <option value="desconto_reais">Desconto R$</option>
                                                    <option value="desconto_porcentagem">Desconto %</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label" for="valorAgregadoInput">Valor</label>
                                                <input type="text" class="form-control" id="valorAgregadoInput" name="valor_agregado" value="0" required>
                                            </div>
                                        </div>

                                        <!-- Checkbox para preço fixo -->
                                        <div class="row form-section">
                                            <div class="row-md-2">
                                                <input type="checkbox" class="form-check-input" id="precoFixoCheckbox" name="precoFixo">
                                                <label class="form-check-label" for="precoFixoCheckbox">Ativar Preço Fixo</label>
                                                <small id="precoFixoCheckbox" class="form-text text-muted">Não use virgula no preço, coloque ponto ex: 35.90.</small>
                                            </div>

                                            <!-- Input para Preço Fixo -->
                                            <div class="col-md-3">
                                                <label for="precoFixoInput" class="form-label">Preço Fixo</label>
                                                <input type="text" class="form-control" id="precoFixoInput" name="precoFixo"
                                                    placeholder="Digite o preço fixo" required disabled>
                                            </div>

                                            <!-- Hidden input para isPorcem -->
                                            <input type="hidden" id="isPorcem" name="isPorcem" value="0">
                                        </div>
                                        <!-- Preço e Total -->
                                        <div class="row form-section">
                                            <div class="col-md-4">
                                                <label class="form-label" for="precoFinal">Preço:</label>
                                                <input name="price" id="precoFinal" type="text" class="form-control">
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label" for="valorProdutoDisplay">Total:</label>
                                                <input name="totalInformado" id="valorProdutoDisplay" type="text" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-md-6 mt-4">
                                            <div class="col">
                                                <div class="mb-3 row">
                                                    <div class="col-lg-8 col-md-6 col-sm-12">
                                                        <div class="form-check">
                                                            <input type="hidden" class="form-control" name="category_id" id="category_id">
                                                            <input class="form-check-input" type="checkbox" name="category_default" id="flexCheckChecked"
                                                                checked>
                                                            <label class="form-check-label" for="flexCheckChecked">
                                                                Usar Categoria Padrão Selecionada
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Categoria -->
                                        <div class="row form-section">
                                            <div class="col-md-6">
                                                <div class="col">
                                                    <div class="mb-3 row">
                                                        <label class="col-lg-2 col-md-12 col-sm-12 col-form-label">Categorias:</label>
                                                        <div class="col-lg-8 col-md-12 col-sm-12">
                                                            <select class="form-select" id="categorias" aria-label="Default select example">
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


                                        <div id="formContainer"></div>

                                        <input type="hidden" class="form-control" name="id_categoria"
                                            id="id_categoria">
                                        </div>

                                        <!-- Spinner e Botão -->
                                        <div class="row form-section">
                                            <div class="col-md-12 text-end">
                                                <div class="spinner-border text-success loading-integracao d-none" role="status">
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
                                                <button type="submit" class="btn btn-success botao_integracao">Finalizar Integração</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <!--- HISTORICO DO PRODUTO --->

                                <ul class="list-group ">
                                    <li class="list-group-item active" aria-current="true">Histórico</li>
                                    <div class="adicionarHistorico"></div>
                                </ul>

                                <!---  FINAL DO HISTORICO  --->
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-headingTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#flush-collapseTwo" aria-expanded="false"
                                    aria-controls="flush-collapseTwo">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value=""
                                            id="flexCheckChecked" checked>
                                        <label class="form-check-label" for="flexCheckChecked">
                                            Shopee
                                        </label>
                                    </div>
                                </button>
                            </h2>
                            <div id="flush-collapseTwo" class="accordion-collapse collapse"
                                aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">Placeholder content for this accordion, which is intended to
                                    demonstrate the <code>.accordion-flush</code> class. This is the second item's accordion
                                    body. Let's imagine this being filled with some actual content.</div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-headingThree">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#flush-collapseThree" aria-expanded="false"
                                    aria-controls="flush-collapseThree">
                                    B2W
                                </button>
                            </h2>
                            <div id="flush-collapseThree" class="accordion-collapse collapse"
                                aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">Placeholder content for this accordion, which is intended to
                                    demonstrate the <code>.accordion-flush</code> class. This is the third item's accordion
                                    body. Nothing more exciting happening here in terms of content, but just filling up the
                                    space to make it look, at least at first glance, a bit more representative of how this
                                    would look in a real-world application.</div>
                            </div>
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

                            <!-- Nome -->
                            <div class="col-md-4">
                                <label for="nome" class="form-label custom-label">Nome</label>
                                <input type="text" class="form-control custom-input" name="nome"
                                    value="{{ isset($viewData['filtro']['nome']) ? $viewData['filtro']['nome'] : '' }}"
                                    id="nome" placeholder="Digite o nome do produto">
                            </div>

                            <!-- Preço -->
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

                            <!-- Estoque -->
                            <div class="col-md-4">
                                <label for="estoque" class="form-label custom-label">Estoque</label>
                                <input type="text" class="form-control custom-input" name="estoque"
                                    value="{{ isset($viewData['filtro']['estoque']) ? $viewData['filtro']['estoque'] : '' }}"
                                    id="estoque" placeholder="Quantidade">
                            </div>

                            <!-- Categoria -->
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

                            <!-- Botões -->
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

                <!-- Listagem de Produtos -->

                <div class="container mt-4">
                    <div class="row">
                        @foreach ($viewData['products'] as $product)
                        <section class="col-md-3 mb-4" id="linhasProduct">
                            <span class="d-none id_product">{{ $product->getId() }}</span>
                            <div>
                                <div class="product-card position-relative">
                                    <!-- Badge de desconto ou novo -->
                                    @if ($product->created_at->gt(\Carbon\Carbon::now()->subDays(20)))
                                        <div class="badge-new"><i class="bi bi-pin-angle-fill"></i> Novo</div>
                                    @endif

                                    <!-- Carrossel de Imagens -->
                                    @php
                                        $fotos = $viewData['images'][$product->getId()]['fotos'] ?? [];
                                    @endphp

                                    @if (!empty($fotos))
                                      <div id="carousel{{ $product->getId() }}" class="carousel slide" data-bs-ride="false" data-bs-interval="false">
                                            <div class="carousel-inner">
                                                @foreach ($fotos as $index => $foto)
                                                    <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                                        <img src="{!! Storage::disk('s3')->url('produtos/'.$foto['foto']) !!}"
                                                             alt="{{ $product->getName() }}" class="product-img d-block w-100">
                                                    </div>
                                                @endforeach
                                            </div>
                                            <!-- Controles do carrossel -->
                                            <button class="carousel-control-prev" type="button" data-bs-target="#carousel{{ $product->getId() }}" data-bs-slide="prev" style="filter: invert(1);">
                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                <span class="visually-hidden">Anterior</span>
                                            </button>
                                            <button class="carousel-control-next" type="button" data-bs-target="#carousel{{ $product->getId() }}" data-bs-slide="next" style="filter: invert(1);">
                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                <span class="visually-hidden">Próximo</span>
                                            </button>
                                        </div>
                                    @endif

                                    <!-- Estoque e Nome -->
                                    <p class="fw-bold text-uppercase text-muted mb-1" style="font-size: 0.8rem;">Estoque {{ $product->available_quantity }}</p>
                                    <h6 class="fw-bold mb-2 product-title">{{ $product->getName() }}</h6>

                                    <!-- Preço -->
                                    <div>
                                        <span class="product-price">R$ {{ number_format($product->priceWithFee, 2) }}</span>
                                    </div>

                                    <!-- Avaliação -->
                                    <div class="product-rating mt-2">
                                        ★★★★★
                                    </div>

                                    <!-- Ações -->
                                    <div class="product-actions mt-3 d-flex justify-content-between">
                                        <a href="{{ route('products.show', ['id' => $product->getId()]) }}" class="btn btn-primary btn-sm" style="border-radius: 20px;">
                                            Ver Mais
                                        </a>
                                        <button class="btn btn-success btn-sm" style="border-radius: 20px;" data-bs-toggle="modal" data-bs-target="#exampleModalToggle">
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
        <input type="hidden" name="total" id="total">

    @endsection

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.theme.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.js"></script>

    <script>
        var valorProduto = 0;
        var i = 0;
        document.addEventListener('DOMContentLoaded', function() {

        function atualizarProgresso() {
            var caracteresDigitados = $('#name').val().length;
            $('#contador').text(caracteresDigitados + '/60');
            var progresso = (caracteresDigitados / 60) * 100;
            $('#progress-bar').css('width', progresso + '%').attr('aria-valuenow', progresso);

            if (caracteresDigitados > 60) {
                $('#name').val($('#name').val().substr(0, 60));
                $('#contador').text(60+'/60');
                alert("O valor não pode exceder 60 caracteres.");
            }
        }

        // Ativar a função quando o usuário digitar
        $('#name').on('keyup', atualizarProgresso);



        // Função para atualizar o valor exibido
            const isPorcemInput = document.getElementById('isPorcem');
            const precoFixoCheckbox = document.getElementById('precoFixoCheckbox');
            const valorAgregadoInput = document.getElementById('valorAgregadoInput');
            const valorAgregadoSelect = document.getElementById('editValorAgregado');

            // Função para bloquear/desbloquear campos quando Preço Fixo é ativado
            precoFixoCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    // Ativa o campo Preço Fixo
                    precoFixoInput.disabled = false;

                    // Desativa campos de Valor Agregado
                    valorAgregadoInput.value = ''; // Limpa o valor
                    valorAgregadoInput.disabled = true;
                    valorAgregadoSelect.disabled = true;
                    isPorcemInput.value = '0'; // Garante que isPorcem seja false
                } else {
                    // Desativa o campo Preço Fixo
                    precoFixoInput.disabled = true;
                    precoFixoInput.value = ''; // Limpa o valor

                    // Reativa campos de Valor Agregado
                    valorAgregadoInput.disabled = false;
                    valorAgregadoSelect.disabled = false;
                }
            });

            // Atualiza o placeholder dinamicamente conforme a seleção do select
            valorAgregadoSelect.addEventListener('change', function() {
                const selectedOption = valorAgregadoSelect.value;

                switch (selectedOption) {
                    case 'acrescimo_reais':
                        valorAgregadoInput.placeholder = 'Digite o acréscimo em R$';
                        break;
                    case 'acrescimo_porcentagem':
                        valorAgregadoInput.placeholder = 'Digite o acréscimo em %';
                        isPorcemInput.value = '1';
                        break;
                    case 'desconto_reais':
                        valorAgregadoInput.placeholder = 'Digite o desconto em R$';
                        break;
                    case 'desconto_porcentagem':
                        valorAgregadoInput.placeholder = 'Digite o desconto em %';
                        isPorcemInput.value = '1';
                        break;
                    default:
                        valorAgregadoInput.placeholder = 'Digite o valor';
                        isPorcemInput.value = '0';
                        break;
                }
            });

            function atualizarValorProduto() {

            const tipoAgregado = valorAgregadoSelect.value; // Opção selecionada (R$ ou %)
            const valorAgregado = parseFloat(valorAgregadoInput.value) || 0;
            const precoFixo = parseFloat($("#precoFixoInput").val().replace(',', '.')) || 0;

            basePrice = parseFloat($('#precoFinal').val()) || 0; // Preço base do produto

            let novoValor = basePrice; // Começa com o preço base

            // Prioridade para preço fixo
            if (precoFixo > 0) {
                novoValor = precoFixo;
            } else {
                // Aplica o cálculo com base no tipo selecionado
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
           // Garante que o valor não seja negativo
           if (novoValor < 0) novoValor = 0;
           // Atualiza o display do valor
           $("#valorProdutoDisplay").val(novoValor.toFixed(2).replace('.', ','));


        }

            // Eventos
            valorAgregadoInput.addEventListener('input', atualizarValorProduto);
            valorAgregadoSelect.addEventListener('change', atualizarValorProduto);
            precoFixoInput.addEventListener('input', atualizarValorProduto);

            $('input[name="preco"]').mask('000.000.000,00', {
                reverse: true
            });

            $('.botao_integracao').click(function(event) {

                $(".loading-integracao").removeClass('d-none');

                // Obtém a posição do elemento de loading
                // Altura da janela do navegador
            });


            $("section#linhasProduct").click(function() {

                var id_produto = $(this).find("span:eq(0)").text(); // Obtém o ID do produto

                // Garante que o editor não tenha conteúdo antigo no DOM
                $('#editor').html(''); // Limpa o conteúdo antigo

                // LIMPA O HISTÓRICO e os campos do formulário
                $('#id_prodenv').val(''); // Limpa o campo de ID
                $('#name').val(''); // Limpa o campo de Nome
                $('#precoFinal').val(''); // Limpa o campo de Preço
                $('#category_id').val(''); // Limpa o campo de Categoria
                $(".img_integracao_foto").attr('src', ''); // Remove a imagem
                $(".img_integracao_title").empty(); // Limpa o título
                $(".img_integracao_ean").empty(); // Limpa o EAN
                $(".img_integracao_price").empty(); // Limpa o Preço
                $(".content_categorias").empty(); // Limpa a lista de categorias
                $('#editor').val(''); // Limpa o editor

                // LIMPA O HISTORICO
                $('.adicionarHistorico').empty();
                $('#id_prodenv').val(id_produto); // ID DO PRODUTO

                var id_user = $('#id_user').val();
                $.ajax({
                    url: "/api/v1/product/" + id_produto,
                    type: "GET",
                    success: function(response) {


                        $("#loading-api").addClass('d-none');
                        if (response) {
                            valorProduto = response.priceWithFee;
                            $("#linkMaterial").attr("href", response.link);
                            $("#total").val(response.priceWithFee);
                            $("#name").val(response.title);
                            $("#precoFinal").val(response.priceWithFee);
                            $("#category_id").val(response.category_id);
                            $(".img_integracao_foto").attr('src', response.image);
                            $(".img_integracao_title").append(response.title);
                            $(".img_integracao_ean").append("EAN : " + response.ean);
                            $(".img_integracao_price").append("Preço: " + response
                            .priceWithFee);

                            // Atualiza o textarea com a descrição
                            $('#editor').val(response.description);
                            atualizarProgresso();
                        }
                    },
                    error: function(error) {
                        $('#result').html(
                            '<option> Produto Digitado Não Existe! </option>'
                        );
                    }
                });



                $.ajax({
                    url: "/api/v1/getHistoryById",
                    type: "GET",
                    data: {
                        id: id_produto,
                        id_user: id_user
                    },
                    success: function(response) {
                        if (response) {
                            var index = [];
                            $.each(response.dados, function(i, item) {
                                index[i] = '<li class="list-group-item"> Nome: ' + item
                                    .name + '  | ID: ' + item.id_ml +
                                    '   | Criado em : ' + item.created_at + '</li>';
                            });

                            var arr = jQuery.makeArray(index);
                            arr.reverse();
                            $(".adicionarHistorico").append(arr);
                        }
                    },
                    error: function(error) {
                        $('#result').html(
                            '<option> Produto Digitado Não Existe! </option>'
                        );
                    }
                });
            });

            //
            $.ajax({
                url: "https://api.mercadolibre.com/sites/MLB/categories",
                type: "GET",
                success: function(response) {
                    if (response) {
                        // SHOW ALL RESULT QUERY
                        var index = [];
                        $.each(response, function(i, item) {
                            index[i] = '<option class="option-size" value=' + item.id + '>' +
                                item.name + '</option>';
                        });

                        if (i == 0) {
                            // PEGA A ALTERACAO DAS CATEGORIAS
                            $("#categorias").change(function() {
                                var ids = $(this).children("option:selected").val();
                                var name = $(this).children("option:selected").text();
                                var content_category = '<li class="list-group-item">' + name +
                                    '</li>';
                                $(".content_categorias").append(content_category);
                                $("#id_categoria").val(
                                    ids); // COLOCA O ID DA CATEGORIA NO CAMPO
                                getCategory(ids);
                            });
                        }

                        var arr = jQuery.makeArray(index);
                        arr.reverse();
                        $("#categorias").html(arr);
                    }
                },
                error: function(error) {
                    $('#result').html(
                        '<option> Produto Digitado Não Existe! </option>'
                    );
                }
            });

            // FUNCAO PARA CHAMAR CATEGORIAS
            function getCategory(category) {
                $.ajax({
                    url: " https://api.mercadolibre.com/categories/" + category,
                    type: "GET",
                    success: function(response) {
                        if (response) {
                            // SHOW ALL RESULT QUERY
                            var index = [];
                            // Adiciona a primeira opção estática
                            index.push('<option class="option-size" >Selecionar</option>');

                            $.each(response.children_categories, function(i, item) {
                                // Crie suas opções dinâmicas aqui
                                var option = '<option class="option-size" value=' + item.id +
                                    '>' + item.name + '</option>';
                                index.push(option);
                            });

                            if (index.length <= 1) {
                                $.ajax({
                                    url: " https://api.mercadolibre.com/categories/" +
                                        category + "/attributes",
                                    type: "GET",
                                    success: function(response) {
                                        if (response) {

                                            const requiredItems = [];
                                            const requiredAttributeNames = ['BRAND',
                                                'MODEL', 'LENGTH', 'HEIGHT'
                                            ];
                                            response.forEach(item => {
                                                if (item.tags && item.tags
                                                    .required === true && !
                                                    requiredAttributeNames.includes(
                                                        item.id)) {
                                                    requiredItems.push(item);
                                                }
                                            });

                                            // Adiciona o h2
                                            var h2 = document.createElement("h2");
                                            h2.textContent = "Campos Obrigatórios";
                                            formContainer.appendChild(h2);

                                            requiredItems.forEach(element => {
                                                // Adiciona o label
                                                var label = document.createElement(
                                                    "label");
                                                label.textContent = element.name;
                                                formContainer.appendChild(label);

                                                if (!element.values) {
                                                    var input = document
                                                        .createElement("input");
                                                    // Define o tipo do input como "text"
                                                    input.type = "text";
                                                    input.className =
                                                        "form-control";
                                                    input.name = element.id;
                                                    // Define um ID para o input (opcional)
                                                    // Adiciona o input ao corpo do documento (ou a qualquer outro elemento desejado)
                                                    formContainer.appendChild(
                                                        input);
                                                } else {
                                                    var selectField = document
                                                        .createElement("select");
                                                    for (var i = 0; i < element
                                                        .values.length; i++) {
                                                        var option = document
                                                            .createElement(
                                                                "option");
                                                        selectField.className =
                                                            "form-control";
                                                        selectField.name = element
                                                            .id;
                                                        option.text = element
                                                            .values[i].name;
                                                        option.value = element
                                                            .values[i].id;
                                                        selectField.appendChild(
                                                            option);

                                                    }
                                                    formContainer.appendChild(
                                                        selectField);
                                                }

                                            });
                                        }
                                    }
                                });
                            }
                            $("#categorias").html(index.join(''));
                        }
                    },
                    error: function(error) {
                        $('#result').html(
                            '<option> Produto Digitado Não Existe! </option>'
                        );
                    }
                });

            }

            $("form").submit(function(event) {
                // event.preventDefault();
                $("#BtnCadastrar").addClass('d-none');
                $('#carregando').removeClass('d-none');
            });




            $('#descontoP').keyup(function() {
                // VALOR TOTAL
                var total = $('#total').val();
                var totalCalculado = total;

                if ($('#descontoP').val().length >= 1) {
                    var porcem = $('#descontoP').val();
                    totalCalculado = parseFloat(total) - parseFloat(calculaPorcemtagem(total,
                        porcem));
                    $('#precoFinal').val(parseFloat(totalCalculado).toFixed(2));

                    $('#acressimoP').prop("disabled", true).css({
                        'background-color': 'red'
                    });
                    $('#acressimoR').prop("disabled", true).css({
                        'background-color': 'red'
                    });;
                    $('#descontoR').prop("disabled", true).css({
                        'background-color': 'red'
                    });
                } else {
                    totalCalculado = parseFloat(total);
                    $('#precoFinal').val(parseFloat(totalCalculado).toFixed(2));
                    $('#acressimoP').prop("disabled", false).css({
                        'background-color': 'white'
                    });
                    $('#acressimoR').prop("disabled", false).css({
                        'background-color': 'white'
                    });;
                    $('#descontoR').prop("disabled", false).css({
                        'background-color': 'white'
                    });
                }
            });

            $('#descontoR').keyup(function() {
                // VALOR TOTAL
                var total = $('#total').val();
                var totalCalculado = total;

                $('#descontoR').keyup(function() {
                    var reais = $('#descontoR').val();
                    totalCalculado = parseFloat(total) - parseFloat(reais);
                    $('#precoFinal').val(parseFloat(totalCalculado).toFixed(2));
                    if ($('#descontoR').val().length >= 1) {
                        $('#acressimoR').prop("disabled", true).css({
                            'background-color': 'red'
                        });
                        $('#acressimoP').prop("disabled", true).css({
                            'background-color': 'red'
                        });;
                        $('#descontoP').prop("disabled", true).css({
                            'background-color': 'red'
                        });
                    } else {
                        $('#precoFinal').val(parseFloat(total).toFixed(2));
                        $('#acressimoR').prop("disabled", false).css({
                            'background-color': 'white'
                        });
                        $('#acressimoP').prop("disabled", false).css({
                            'background-color': 'white'
                        });;
                        $('#descontoP').prop("disabled", false).css({
                            'background-color': 'white'
                        });
                    }
                });
            });

            $('#acressimoP').keyup(function() {
                // VALOR TOTAL
                var total = $('#total').val();
                var totalCalculado = total;
                if ($('#acressimoP').val().length >= 1) {
                    var porcem = $('#acressimoP').val();
                    totalCalculado = parseFloat(total) + parseFloat(calculaPorcemtagem(total,
                        porcem));
                    $('#precoFinal').val(parseFloat(totalCalculado).toFixed(2));
                    $('#acressimoR').prop("disabled", true).css({
                        'background-color': 'red'
                    });
                    $('#descontoP').prop("disabled", true).css({
                        'background-color': 'red'
                    });;
                    $('#descontoR').prop("disabled", true).css({
                        'background-color': 'red'
                    });
                } else {
                    totalCalculado = parseFloat(total);
                    $('#precoFinal').val(parseFloat(totalCalculado).toFixed(2));
                    $('#acressimoR').prop("disabled", false).css({
                        'background-color': 'white'
                    });
                    $('#descontoR').prop("disabled", false).css({
                        'background-color': 'white'
                    });
                    $('#descontoP').prop("disabled", false).css({
                        'background-color': 'white'
                    });
                }
            });

            $('#acressimoR').keyup(function() {
                // VALOR TOTAL
                var total = $('#total').val();
                var totalCalculado = total;
                if ($('#acressimoR').val().length >= 1) {
                    var reais = $('#acressimoR').val();
                    totalCalculado = parseFloat(total) + parseFloat(reais);
                    $('#precoFinal').val(parseFloat(totalCalculado).toFixed(2));

                    $('#acressimoP').prop("disabled", true).css({
                        'background-color': 'red'
                    });
                    $('#descontoP').prop("disabled", true).css({
                        'background-color': 'red'
                    });;
                    $('#descontoR').prop("disabled", true).css({
                        'background-color': 'red'
                    });
                } else {
                    totalCalculado = parseFloat(total) + 0;
                    $('#precoFinal').val(parseFloat(totalCalculado).toFixed(2));
                    $('#acressimoP').prop("disabled", false).css({
                        'background-color': 'white'
                    });
                    $('#descontoP').prop("disabled", false).css({
                        'background-color': 'white'
                    });;
                    $('#descontoR').prop("disabled", false).css({
                        'background-color': 'white'

                    });
                }
            });

            function calculaPorcemtagem(valor, porcem) {
                //60 x 25% = 160 (25/100) = 160 x 0,25 = 40.
                return valor * (porcem / 100);
            }

            $('#total').val($('#valorTotalInput').val());

            /**
             * FUNCAO QUE PEGA VALOR DIGITADO NO INPUT
             */

            function getFormattedDate(date) {
                var year = date.getFullYear();

                var month = (1 + date.getMonth()).toString();
                month = month.length > 1 ? month : '0' + month;

                var day = date.getDate().toString();
                day = day.length > 1 ? day : '0' + day;

                return month + '/' + day + '/' + year;
            }

        });
    </script>
