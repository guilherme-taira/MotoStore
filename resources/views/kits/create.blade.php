@extends('layouts.app')
@section('conteudo')
    <style>
        .highlighted-category {
            color: red;
            /* Cor para destacar */
            font-weight: bold;
        }

        .image-item {
            position: relative;
            display: inline-block;
            margin-right: 10px;
            white-space: normal;
        }

        .main-image-label {
            position: absolute;
            top: -20px;
            background-color: #007bff;
            color: #fff;
            padding: 2px 8px;
            font-size: 12px;
            border-radius: 5px;
        }

        .delete-button {
            display: block;
            background-color: #dc3545;
            color: #fff;
            border: none;
            padding: 5px 10px;
            font-size: 12px;
            cursor: pointer;
            border-radius: 5px;
            position: absolute;
            bottom: -25px;
            left: 50%;
            transform: translateX(-50%);
        }

        .image-badge {
            position: absolute;
            top: 5px;
            right: 5px;
            background-color: red;
            color: #fff;
            padding: 2px 6px;
            font-size: 12px;
            border-radius: 12px;
            font-weight: bold;
        }

        .img-fluid {
            width: 100px;
            height: auto;
            border-radius: 5px;
        }
    </style>
    <script>
        function removeImage(index) {
            // Remove a imagem correspondente no preview
            $('#imagePreview .image-item').eq(index).remove();

            // Atualiza os índices e redefine a etiqueta "Imagem Principal"
            $('#imagePreview .image-item').each(function(newIndex) {
                $(this).find('.image-badge').text(newIndex + 1);
                $(this).find('.main-image-label').remove();
                if (newIndex === 0) {
                    $(this).prepend('<span class="main-image-label">Imagem Principal</span>');
                }
            });

            // Atualiza a contagem de imagens
            $('#image-count').text('Total de fotos: ' + $('#imagePreview .image-item').length);

            // Atualiza o input de arquivos com os arquivos restantes
            updateInputFile(index);
        }

        function updateInputFile(removedIndex) {
            const input = document.getElementById('file');
            const dataTransfer = new DataTransfer();

            // Reinsere os arquivos restantes no input
            Array.from(input.files).forEach((file, index) => {
                if (index !== removedIndex) {
                    dataTransfer.items.add(file);
                }
            });

            // Atualiza o input file
            input.files = dataTransfer.files;
        }


        // Array para armazenar os IDs dos produtos selecionados
        let selectedProducts = [];

        $(document).on('change', '.form-check-input', function() {
            const productId = $(this).val();
            const stockValue = $(`#stock-${productId}`).val(); // Obtém o valor do estoque
            const nome = $(`#title-${productId}`).val(); // Obtém o nome do produto
            const fornecedor = $(`#fornecedor-${productId}`).val(); // Obtém o nome do produto

            if ($(this).is(':checked')) {
                // Verifica se o produto já está no array
                const existingProduct = selectedProducts.find((product) => product.id === productId);

                if (!existingProduct) {
                    // Adiciona o produto ao array se não existir
                    selectedProducts.push({
                        id: productId,
                        stock: stockValue,
                        nome: nome,
                        fornecedor: fornecedor
                    });
                }
            } else {
                // Remove o produto do array se for desmarcado
                selectedProducts = selectedProducts.filter((product) => product.id !== productId);
            }

            console.log('Produtos selecionados:', selectedProducts);
        });

        // Exibe o array no console sempre que um checkbox for clicado
        console.log('Produtos selecionados:', selectedProducts);
        let filterApplied = false; // Indica se o filtro já foi aplicado

        function loadProducts(page = 1, fornecedor = null) {
            $('#produto-list').empty(); // Limpa a lista de produtos enquanto carrega
            $('#loader').removeClass('d-none');

            $.get(`/api/v1/produtos?page=${page}&fornecedor_id=${fornecedor}`, function(data) {
                const products = data.data;
                const pagination = data.links;
                console.log(pagination);
                $('#loader').addClass('d-none');

                // Cria os itens da lista de produtos
                products.forEach(product => {
                    const isChecked = selectedProducts.includes(product.id.toString()) ? 'checked' : '';
                    $('#produto-list').append(`
                <li class="d-flex align-items-center mb-3 p-3 border rounded shadow-sm">
                    <input class="form-check-input me-2 product-checkbox" type="checkbox" value="${product.id}" id="product-${product.id}" ${isChecked}>
                    <img src="${product.imagem_url}" alt="${product.title}" class="rounded me-3" style="width: 80px; height: 80px; object-fit: cover;">
                    <div class="flex-grow-1">
                        <h6 class="mb-1">${product.title}</h6>
                        <span class="text-muted">Preço: R$${product.priceKit.toFixed(2)}</span>
                        <span class="text-muted ms-3">Estoque Disponível: ${product.available_quantity}</span>
                        <!-- Inputs ocultos para armazenar dados -->
                        <div class="mt-2">
                            <input type="hidden" id="stock-${product.id}" value="1">
                            <input type="hidden" id="title-${product.id}" value="${product.title}">
                            <input type="hidden" id="fornecedor-${product.id}" value="${product.fornecedor_id}">
                        </div>
                    </div>
                </li>
            `);
                });

                // Vincula o evento de clique aos checkboxes recém-criados
                $('.product-checkbox').on('click', function() {
                    const productId = $(this).val();
                    const fornecedorId = $(`#fornecedor-${productId}`).val();
                    // Chama novamente a função para recarregar os produtos
                    // Se o filtro ainda não foi aplicado ou se o fornecedor for diferente, recarregue com o novo filtro
                    if (!filterApplied || currentFornecedor !== fornecedorId) {
                        loadProducts(page, fornecedorId);
                        filterApplied = true;
                        currentFornecedor = fornecedorId;
                    }
                });

                // Atualiza a navegação de paginação
                $('#pagination').html('');

                // Botão "Anterior" (se existir)
                if (data.prev_page_url) {
                    // Podemos detectar o link de "previous" procurando no array
                    // ou simplesmente usar a URL `data.prev_page_url`.
                    // Exemplo usando o page - 1:
                    $('#pagination').append(`
                        <li class="page-item">
                            <a class="page-link" href="#"
                            onclick="loadProducts(${page - 1}, ${fornecedor === null ? 'null' : `'${fornecedor}'`}); return false;">
                            Anterior
                            </a>
                        </li>
                    `);
                }

                // Percorre cada link de paginação
                pagination.forEach((pageLink) => {
                    // Se o label for 'pagination.previous' ou 'pagination.next',
                    // podemos tratar separadamente ou simplesmente ignorar aqui.
                    if (pageLink.label === 'pagination.previous' || pageLink.label === 'pagination.next') {
                        return;
                    }

                    // Tenta converter o label em número (páginas numéricas)
                    const pageNumber = parseInt(pageLink.label, 10);

                    // Se for um número válido, exibimos
                    if (!isNaN(pageNumber)) {
                        $('#pagination').append(`
                            <li class="page-item ${pageLink.active ? 'active' : ''}">
                                <a class="page-link" href="#"
                                onclick="loadProducts(${pageNumber}, ${fornecedor === null ? 'null' : `'${fornecedor}'`}); return false;">
                                ${pageNumber}
                                </a>
                            </li>
                        `);
                    }
                });

                // Botão "Próximo" (se existir)
                if (data.next_page_url) {
                    // Você pode detectar a URL exata ou simplesmente usar page + 1.
                    // Exemplo usando o page + 1:
                    $('#pagination').append(`
                        <li class="page-item">
                            <a class="page-link" href="#"
                            onclick="loadProducts(${page + 1}, ${fornecedor === null ? 'null' : `'${fornecedor}'`}); return false;">
                            Próximo
                            </a>
                        </li>
                    `);
                }

            });
        }

        function clearFilter() {
            // Reseta o controle de filtro e recarrega a lista sem filtro
            filterApplied = false;
            currentFornecedor = null;
            loadProducts(1, null);
        }
    </script>



    <div class="container mt-3">
        <!-- Mensagem de sucesso -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Mensagem de erro -->
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>





    <!-- Modal de Progresso -->
    <div class="modal fade" id="progressModal" tabindex="-1" aria-labelledby="progressModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="progressModalLabel">
                        <div class="spinner-border text-success" role="status"></div> Processando Produtos aguarde..
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="progress">
                        <div id="progressBar" class="progress-bar" role="progressbar" style="width: 0%;" aria-valuemin="0"
                            aria-valuemax="100">0%</div>
                    </div>
                    <p class="mt-3">
                        Processando <span id="currentProduct">0</span>º<span id="totalProducts"></span> produto...
                    </p>
                    <p class="text-muted">Produto atual: <span id="currentProductName" class="fw-bold">---</span></p>
                </div>
            </div>
        </div>
    </div>



    @php
        // Se $produtos for array associativo, reindexamos para pegar o primeiro item;
        // se for uma Collection, usamos ->first().
        $firstProduct = null;
        if (isset($produtos) && count($produtos) > 0) {
            $firstProduct = is_array($produtos)
                ? array_values($produtos)[0] // Reindexa e pega o primeiro
                : $produtos->first(); // Se for Collection
        }

        // Verifica se há fornecedor definido no primeiro produto
        $hasFornecedor = false;
        if ($firstProduct && !empty($firstProduct['fornecedor'])) {
            $hasFornecedor = true;
        }
    @endphp


    <!--- MODAL QUE SELECIONA O MOTORISTA --->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between align-items-center">
                    <h5 class="modal-title text-dark" id="exampleModalLabel">Cadastro Kit <i class="bi bi-bookmarks"></i>
                    </h5>
                    <div class="d-flex ms-auto">
                        <input type="text" id="searchItemInput" class="form-control me-2" placeholder="Anúncio Base"
                            style="max-width: 300px;">
                        <button id="searchItemButton" class="btn btn-primary">Copiar</button>
                    </div>
                </div>

                <form method="POST" action="{{ route('kitadd') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="imageOrder" name="imageOrder">
                    <div class="modal-body">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col">
                                    <div class="mb-3 row">
                                        <label class="col-lg-2 col-md-6 col-sm-12 col-form-label">Nome:</label>
                                        <div class="col-lg-10 col-md-6 col-sm-12">
                                            <input name="name" id="titleAnuncio" type="text"
                                                value="{{ old('name') }}" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="col">
                                    <div class="mb-3 row">
                                        <label class="col-lg-2 col-md-6 col-sm-12 col-form-label">Preço:</label>
                                        <div class="col-lg-3 col-md-6 col-sm-12">
                                            <input name="precoFinal" required id="precoFinal"
                                                value="{{ old('precoFinal') }}" type="text" class="form-control"
                                                readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <div class="mb-3 row">
                                        <label class="col-lg-2 col-md-6 col-sm-12 col-form-label">Image:</label>
                                        <div class="col-lg-10 col-md-6 col-sm-12">
                                            <input class="form-control" type="file" id="file" name="photos[]"
                                                multiple required>
                                        </div>
                                    </div>
                                </div>

                                <div class="container mt-4">
                                    <div id="imagePreview" class="image-container-preview"></div>
                                </div>

                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="description" id="description" required rows="8"></textarea>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label for="categoria">Categorias:</label>
                            <select class="form-select" name="categoria" id="categoria" required
                                aria-label="Default select example">
                                <option value="">Selecione...</option>
                                @foreach ($viewData['categorias'] as $categoria)
                                    <option class="bg-warning" disabled>{{ $categoria['nome'] }}</option>
                                    @foreach ($categoria['subcategory'] as $subcategoria)
                                        <option value="{{ $subcategoria->id }}"> - {{ $subcategoria->name }}</option>
                                    @endforeach
                                @endforeach
                            </select>
                        </div>

                        <div class="row p-4">
                            <div class="col-md-6">
                                <div class="col">
                                    <div class="mb-6 row">
                                        <label class="col-lg-2 col-md-3 col-sm-6 col-form-label">Categorias:</label>
                                        <div class="input-group">
                                            <select class="form-select" name="categoriaMl" id="categorias"
                                                aria-label="Default select example" required>
                                                <option selected disabled>Selecionar</option>
                                            </select>
                                            <button type="button" class="btn btn-secondary"
                                                id="resetButton">Reset</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" class="form-control" name="id_categoria" id="id_categoria">
                            {{-- <input type="text" class="form-control"  name="stock" id="stock"> --}}
                            <div class="col-md-4 p-4">
                                <div class="col">
                                    <div class="mb-3 row">
                                        <ol class="list-group list-group-numbered content_categorias">

                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="owner" id="owner" value="{{ Auth::user()->id }}">

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Finalizar Cadastro</button>
                        </div>
                </form>
            </div>
        </div>
    </div>
    </div>

    <!--- FINAL DO MODAL ---->

    <!-- Modal para exibir o catálogo de produtos com checkboxes -->
    <div class="modal fade" id="catalogoModal" tabindex="-1" aria-labelledby="catalogoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="catalogoModalLabel">Catálogo de Produtos</h5>
                    <div class="d-flex">
                        <!-- Botão de Limpar Filtro (só aparece se NÃO houver fornecedor) -->
                        @if (!$hasFornecedor)
                            <button type="button" class="btn btn-danger me-2" onclick="clearFilter()"
                                id="btnLimparFiltro">
                                <i class="bi bi-x-circle me-2"></i> Limpar Filtro
                            </button>
                        @endif

                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>
                <div class="modal-body">
                    <!-- Loader -->
                    <div id="loader" class="text-center mb-3 d-none">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Carregando...</span>
                        </div>
                    </div>

                    <!-- Área onde os produtos serão listados -->
                    <div id="produto-list"></div>

                    <!-- Botão para adicionar produtos selecionados ao kit e navegação de paginação -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <button type="button" class="btn btn-primary" id="adicionarSelecionados">
                            Adicionar Produtos Selecionados
                        </button>
                        <nav aria-label="Navegação de página" id="pagination" class="pagination"></nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--- FINAL DO MODAL ---->

    <div class="card mb-4 shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="m-0">Gerador de Kits</h5>
        </div>
        <div class="card-body">

            @if ($errors->any())
                <ul class="alert alert-danger list-unstyled">
                    @foreach ($errors->all() as $error)
                        <li>- {{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            @if (!empty($msg))
                <div class="alert alert-primary" role="alert">
                    {{ $msg }}
                </div>
            @endif

            <form action="{{ route('setSessionRoute') }}" id="setSession" method="get" class="mb-3">
                <div class="row align-items-center">
                    <div class="col-lg-8 mb-3">
                        <div class="input-group">
                            <div class="input-group">
                                <button type="button" class="btn btn-secondary" data-bs-toggle="modal"
                                    data-bs-target="#catalogoModal">
                                    <i class="bi bi-book me-2"></i> Ver Catálogo
                                </button>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="id" id="id">
                    <input type="hidden" id="name" name="name">
                    <input type="hidden" id="unit_price" name="unit_price">
                    <div class="col-lg-4 text-end">
                        <button class="btn btn-success w-100 d-none" id="btnFinalizar">Inserir no Kit <i
                                class="bi bi-signpost-2"></i></button>
                    </div>
                    <div class="spinner-border text-success d-none" id="carregando">
                        <span class="visually-hidden">Loading...</span>
                    </div>
            </form>

            <div class="product-list">
                <h6 class="border-bottom pb-2 mb-3">Produtos no Kit</h6>
                <ol class="list-unstyled">
                    @php
                        $total = 0;
                    @endphp

                    @if (isset($produtos) && count($produtos) > 0)
                        @foreach ($produtos as $produto)
                            @if (!empty($produto['id']))
                                @php
                                    // Soma o preço e a taxa multiplicada pela quantidade
                                    $total += $produto['price'] + $produto['fee'] * $produto['quantidade'];
                                @endphp
                                <li class="d-flex align-items-center mb-3 p-3 border rounded shadow-sm">
                                    <img src="{!! Storage::disk('s3')->url('produtos/' . $produto['id'] . '/' . $produto['imagem']) !!}" alt="{{ $produto['nome'] }}" class="rounded me-3"
                                        style="width: 80px; height: 80px; object-fit: cover;">

                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">{{ $produto['nome'] ?? '' }}</h6>

                                        <!-- Seção de preço e estoque -->
                                        <div class="d-flex align-items-center mb-2">
                                            <span class="text-dark me-3">
                                                Preço: R$ {{ number_format($produto['price'], 2, ',', '.') }} Un / Pct
                                            </span>
                                            <span class="text-dark">
                                                Estoque: {{ $produto['available_quantity'] ?? 0 }} Disponível
                                            </span>
                                        </div>

                                        <!-- Campo de quantidade e botão de adicionar -->
                                        <div class="d-flex align-items-center">
                                            <span class="me-2">Quantidade:</span>
                                            <form
                                                action="{{ route('adicionarQuantidadeNoKit', ['id' => $produto['id']]) }}"
                                                method="POST" class="d-inline-flex align-items-center">
                                                @csrf
                                                <input name="stock" id="stock-{{ $produto['id'] }}"
                                                    value="{{ $produto['quantidade'] ?? 1 }}" type="number"
                                                    class="form-control form-control-sm w-25 me-2">
                                                <button id="btnSalvarQuantidade" class="btn btn-success btn-sm">
                                                    Adicionar
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                    <a href="{{ route('deleteSessionRoute', ['id' => $produto['id']]) }}"
                                        class="btn btn-danger ms-3">
                                        Remover <i class="bi bi-dash-circle-dotted"></i>
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    @else
                        <p class="text-muted">Nenhum produto no kit.</p>
                    @endif
                </ol>
            </div>

            <div class="total mt-4">
                <div class="d-flex justify-content-between align-items-center">
                    <label class="me-2"><strong>Total R$:</strong></label>
                    <input name="price" id="total" type="text"
                        value="{{ number_format($total, 2, ',', '.') }}" class="form-control w-25" readonly>
                    <button type="button" class="btn btn-primary ms-2" id="modalbutton" data-bs-toggle="modal"
                        data-bs-target="#exampleModal">
                        Cadastrar Kit <i class="bi bi-pin-map"></i>
                    </button>
                </div>
            </div>


            <!-- Modal de Escolha -->
            <div class="modal fade" id="modalEscolha" tabindex="-1" aria-labelledby="modalEscolhaLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalEscolhaLabel">O que deseja fazer?</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Fechar"></button>
                        </div>
                        <div class="modal-body text-center">
                            <p class="mb-4">Escolha uma das opções abaixo:</p>
                            <button type="button" class="btn btn-success me-2" id="btnCriarKit">Criar Kit</button>
                            <button type="button" class="btn btn-info" id="btnCriarVariacoes">Criar Variações</button>
                        </div>
                    </div>
                </div>
            </div>

        @endsection

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.css" rel="stylesheet" />
        <link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.theme.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.3/jquery.mask.min.js"></script>


        <script>
            $(document).ready(function() {


                let currentFornecedor = null;
                let filterApplied = false;

                // Se existir pelo menos um produto, definimos o fornecedor e marcamos como filtrado
                @if ($firstProduct)
                    currentFornecedor = "{{ $firstProduct['fornecedor'] ?? '' }}";
                    filterApplied = true;
                @endif


                // Faz uma requisição POST para apagar as mensagens da sessão
                fetch("{{ route('clear.session.messages') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({})
                    }).then(response => response.json())
                    .then(data => console.log(data.message)) // Exibe a mensagem no console
                    .catch(error => console.error('Erro ao limpar a sessão:', error));

                // Função para resetar o conteúdo
                $('#resetButton').on('click', function() {
                    getAllCategorias(); // VOLTA TUDO AS CATEGORIAS
                    // Limpa a lista de categorias selecionadas
                    $('.content_categorias').empty();

                    // Limpa o campo de ID de categoria
                    $('#id_categoria').val('');
                    // Reseta o select para a opção padrão
                    $('#categorias').empty();
                });

                $(document).on('click', '#searchItemButton', function() {
                    const itemId = $('#searchItemInput').val().trim();

                    if (itemId === '') {
                        alert('Por favor, insira um ID de item.');
                        return;
                    }

                    // URL base da API do Mercado Livre
                    const apiBaseUrl = 'https://api.mercadolibre.com/items/';

                    // Função genérica para fazer a requisição AJAX
                    function fetchData(url, successCallback, errorMessage) {
                        $.ajax({
                            url: url,
                            type: 'GET',
                            success: successCallback,
                            error: function() {
                                $('#searchResult').html(
                                    `<p class="text-danger">${errorMessage}</p>`);
                            }
                        });
                    }

                    // Requisição para os dados do item
                    fetchData(
                        `${apiBaseUrl}${itemId}`,
                        function(response) {

                            $("#titleAnuncio").val(response.title);
                            $("#precoFinal").val(parseFloat(response.price).toFixed(2).replace('.', ','));
                            $("#acressimoR").val(0);

                            // Requisição para os dados da categoria
                            fetchData(
                                `https://api.mercadolibre.com/categories/${response.category_id}`,
                                function(categoryResponse) {
                                    $("#categoryName").val(categoryResponse.name);

                                    $("#id_categoria").val(categoryResponse.id);
                                    // Exibe o caminho da categoria (path_from_root)
                                    const pathList = categoryResponse.path_from_root.map((category,
                                        index, array) => {
                                        if (index === array.length - 1) {
                                            return `<li class="list-group-item highlighted-category">${category.name} &rarr;</li>`;
                                        } else {
                                            return `<li class="list-group-item">${category.name}</li>`;
                                        }
                                    }).join('');
                                    $('.content_categorias').append(pathList);
                                },
                                'Erro ao buscar a categoria. Verifique o ID e tente novamente.'
                            );
                        },
                        'Erro ao buscar o item. Verifique o ID e tente novamente.'
                    );

                    // Requisição para a descrição do item
                    fetchData(
                        `${apiBaseUrl}${itemId}/description`,
                        function(descriptionResponse) {
                            $("#description").val(descriptionResponse.plain_text);
                        },
                        'Erro ao buscar a descrição do item. Verifique o ID e tente novamente.'
                    );


                });


                $('#file').change(function() {
                    var formData = new FormData();

                    var files = $('#file')[0].files;
                    for (var i = 0; i < files.length; i++) {
                        formData.append('file[]', files[i]);
                    }

                    $.ajax({
                        url: '/api/v1/fotoPreview', // Rota para o método 'fotoPreview'
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(data) {
                            $('#imagePreview').empty();
                            $('#image-count').text('Total de fotos: ' + data.length);

                            $.each(data, function(index, image) {
                                const mainLabel = index === 0 ?
                                    '<span class="main-image-label">Imagem Principal</span>' :
                                    '';
                                const deleteButton =
                                    `<button type="button" class="delete-button" onclick="removeImage(${index})">Excluir</button>`;

                                $('#imagePreview').append(
                                    `<div class="image-item position-relative" data-original-name="${image.originalName}">
                        ${mainLabel}
                        <img src="${image.url}" class="img-fluid">
                        ${deleteButton}
                    </div>`
                                );
                            });

                            if (data.length > 0) {
                                $('#clearImages').show();
                            }

                            // Configura a funcionalidade de sortable e captura a nova ordem
                            $('#imagePreview').sortable({
                                axis: "x",
                                start: function(event, ui) {
                                    $('#imageContainer').css('overflow-x', 'hidden');
                                },
                                stop: function(event, ui) {
                                    $('#imageContainer').css('overflow-x', 'auto');
                                },
                                update: function(event, ui) {

                                    // Remove todas as etiquetas "Imagem Principal"
                                    $('#imagePreview .main-image-label').remove();

                                    // Adiciona a etiqueta "Imagem Principal" à primeira imagem
                                    $('#imagePreview .image-item').each(function(
                                        index) {
                                        if (index === 0) {
                                            $(this).prepend(
                                                '<span class="main-image-label">Imagem Principal</span>'
                                            );
                                        }
                                    });

                                    const newOrder = [];
                                    $('#imagePreview .image-item').each(function() {
                                        const imageUrl = $(this).find('img')
                                            .attr('src');
                                        const originalName = $(this).data(
                                            'original-name');
                                        newOrder.push({
                                            url: imageUrl,
                                            originalName: originalName
                                        });
                                    });

                                    console.log(newOrder);

                                    // Atualiza o campo hidden com a nova ordem
                                    let imageOrderInput = $('#imageOrder');
                                    if (imageOrderInput.length === 0) {
                                        imageOrderInput = $('<input>', {
                                            type: 'hidden',
                                            id: 'imageOrder',
                                            name: 'imageOrder',
                                        });
                                        $('#imagePreview').append(imageOrderInput);
                                    }

                                    imageOrderInput.val(JSON.stringify(newOrder));
                                },
                            });
                        },
                    });
                });



                let fileOrder = [];

                $('#file').on('change', function(e) {
                    let files = e.target.files;
                    fileOrder = []; // Reseta a ordem

                    for (let i = 0; i < files.length; i++) {
                        fileOrder.push(i); // Registra o índice de cada arquivo
                    }
                });

                function removePicture(element) {
                    $(element).parent().remove();
                    updateInputFile();
                }

                // Função para atualizar o input type file
                function updateInputFile() {
                    var files = [];
                    $("#imagePreview img").each(function() {
                        files.push($(this).attr('src'));
                    });

                    // Remover e recriar o input file
                    var inputFile = $('#file');
                    var newInputFile = $('<input class="form-control" type="file" id="file" name="photos[]" multiple>');

                    // Substituir o input file antigo pelo novo input file
                    inputFile.replaceWith(newInputFile);

                    // Adicionar os URLs das imagens remanescentes como hidden inputs
                    for (var i = 0; i < files.length; i++) {
                        newInputFile.after('<input type="hidden" name="photo_urls[]" value="' + files[i] + '">');
                    }
                }

                // Evento para remover a imagem quando o botão "X" é clicado
                $(document).on('click', '.remove-icon', function() {
                    $(this).parent().remove();
                    removePicture(this);
                });

                // Função para atualizar a contagem de imagens
                function updateImageCount() {
                    var count = $('.image-item').length;
                    $('#image-count').text('Total de fotos: ' + count);
                }

                // Função para converter URL de dados em Blob
                function dataURItoBlob(dataURI) {
                    var byteString = atob(dataURI.split(',')[1]);
                    var mimeString = dataURI.split(',')[0].split(':')[1].split(';')[0];
                    var ab = new ArrayBuffer(byteString.length);
                    var ia = new Uint8Array(ab);
                    for (var i = 0; i < byteString.length; i++) {
                        ia[i] = byteString.charCodeAt(i);
                    }
                    return new Blob([ab], {
                        type: mimeString
                    });
                }



                // Clique no botão "Adicionar Selecionados"
                $('#adicionarSelecionados').on('click', function() {
                    $('#modalEscolha').modal('show');
                });

                $('#btnCriarKit').on('click', function() {
                    $('#modalEscolha').modal('hide');

                    // Fecha outros modais se estiverem abertos
                    $('.modal').modal('hide');

                    // Abre o modal de progresso
                    $('#progressModal').modal('show');

                    if (selectedProducts.length === 0) {
                        alert('Nenhum produto selecionado.');
                        $('#progressModal').modal('hide'); // Fecha o modal se nada foi selecionado
                        return;
                    }

                    let completedRequests = 0;

                    // Função para atualizar a barra de progresso
                    function updateProgress() {
                        completedRequests++;
                        const progressPercent = (completedRequests / selectedProducts.length) * 100;
                        $('#progressBar').css('width', `${progressPercent}%`);
                        $('#progressBar').text(`${Math.round(progressPercent)}%`);
                        $('#currentProduct').text(completedRequests);
                        $('#currentProductName').text(selectedProducts[completedRequests - 1]?.nome || '');

                        // Fecha o modal quando todos os produtos forem processados
                        if (completedRequests === selectedProducts.length) {
                            setTimeout(() => {
                                $('#progressModal').modal('hide');
                                location.reload(); // Atualiza a página
                            }, 3000);
                        }
                    }

                    // Função para enviar os produtos sequencialmente
                    function processProductsSequentially(index) {
                        if (index >= selectedProducts.length) {
                            return; // Fim do processamento
                        }

                        const item = selectedProducts[index];
                        console.log(`Processando produto: ${item.nome} (${item.id})`);

                        // Envia o produto para a rota
                        $.ajax({
                            url: '/adicionarQuantidade',
                            type: 'POST',
                            data: {
                                products: [item],
                            },
                            success: function(response) {
                                console.log(
                                    `Produto ${item.nome} (${item.id}) adicionado com sucesso.`);
                                updateProgress();
                            },
                            error: function(xhr, status, error) {
                                console.error(
                                    `Erro ao adicionar produto ${item.nome} (${item.id}):`,
                                    error);
                                updateProgress();
                            },
                            complete: function() {
                                // Chama a próxima iteração após 3 segundos
                                setTimeout(() => {
                                    processProductsSequentially(index + 1);
                                }, 3000);
                            },
                        });
                    }

                    // Inicia o processamento sequencial
                    processProductsSequentially(0);
                });

                $('#btnCriarVariacoes').on('click', function() {
                        $('#modalEscolha').modal('hide');
                        $('.modal').modal('hide'); // Fecha todos os modais
                        $('#progressModal').modal('show'); // Abre o modal de progresso

                        if (selectedProducts.length === 0) {
                            alert('Nenhum produto selecionado.');
                            $('#progressModal').modal('hide');
                            return;
                        }

                        // Atualiza os dados do progresso
                        $('#currentProduct').text(selectedProducts.length);
                        $('#currentProductName').text('Preparando envio...');

                        // Envia todos os produtos em uma única requisição
                        $.ajax({
                            url: "{{ route('storeWithVariations') }}", // Laravel route
                            type: "POST",
                            data: {
                                _token: "{{ csrf_token() }}",
                                products: selectedProducts
                            },
                            success: function(response) {
                                $('#progressBar').css('width', '100%');
                                $('#progressBar').text('100%');
                                $('#currentProductName').text(
                                    'Variações criadas com sucesso!');
                                setTimeout(() => {
                                    $('#progressModal').modal('hide');
                                    window.location.href = "{{ route('allProductsByFornecedorVariation') }}"; // Redireciona
                                }, 3000);
                            },
                            error: function(xhr, status, error) {
                                console.error('Erro ao criar variações:', error);
                                $('#progressBar').addClass('bg-danger');
                                $('#progressBar').text('Erro!');
                                $('#currentProductName').text('Erro ao criar variações.');
                            }
                        });
                    });



                // Carregar produtos ao abrir o modal
                $('#catalogoModal').on('show.bs.modal', function() {
                    loadProducts(1, currentFornecedor);
                });

                // Evento para adicionar os produtos selecionados ao kit
                $('#addToKit').on('click', function() {
                    selectedProducts = [];
                    $('#produto-list input:checked').each(function() {
                        selectedProducts.push($(this).val());
                    });

                    // Aqui você pode enviar selectedProducts para sua rota ou processá-los
                    console.log(selectedProducts); // Exemplo para verificar os IDs selecionados
                    alert("Produtos adicionados ao kit!");
                    $('#catalogoModal').modal('hide');
                });

                var valorProduto = 0;
                var i = 0;

                $("tr#linhasProduct").click(function() {
                    // LIMPA O HISTORICO
                    $('.adicionarHistorico').empty();

                    id_produto = $(this).children(".id_product").text();
                    $('#id_product').val(id_produto); // ID DO PRODUTO
                    var id_user = $('#id_user').val();
                    $.ajax({
                        url: "/api/v1/product/" + id_produto,
                        type: "GET",
                        success: function(response) {
                            if (response) {
                                valorProduto = response.price;
                                $("#total").val(response.price);
                                $("#name").val(response.title);
                                $("#precoFinal").val(response.price);
                                $("#unit_price").val(response.priceKit)
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
                                        ' | Criado em : ' + item.created_at + '</li>';
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

                getAllCategorias();

                function getAllCategorias() {
                    $.ajax({
                        url: "/meli/categories",
                        type: "GET",
                        success: function(response) {
                            if (response) {
                                // SHOW ALL RESULT QUERY
                                var index = [];
                                $.each(response, function(i, item) {
                                    index[i] = '<option class="option-size" value=' + item.id +
                                        '>' +
                                        item.name + '</option>';
                                });

                                if (i == 0) {
                                    // PEGA A ALTERACAO DAS CATEGORIAS
                                    $("#categorias").off("change").on("change", function() {
                                        var ids = $(this).children("option:selected").val();
                                        var name = $(this).children("option:selected").text();
                                        var content_category = '<li class="list-group-item">' +
                                            name +
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
                }

                // FUNCAO PARA CHAMAR CATEGORIAS
                function getCategory(category) {
                    $.ajax({
                        url: "/meli/subcategories/" + category,
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
                                        url: "/meli/subcategories/attributes/" + category,
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

                                                    var selectField = document
                                                        .createElement("select");
                                                    for (var i = 0; i < element.values
                                                        .length; i++) {
                                                        var option = document
                                                            .createElement("option");
                                                        selectField.className =
                                                            "form-control";
                                                        selectField.name = element.id;
                                                        option.text = element.values[i]
                                                            .name;
                                                        option.value = element.values[i]
                                                            .id;
                                                        selectField.appendChild(option);

                                                    }
                                                    formContainer.appendChild(
                                                        selectField);
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

                // VALOR TOTAL
                var total = parseFloat($('#total').val().replace(',', '.')); // Converte para número
                if (!isNaN(total)) {
                    var novoTotal = total / 0.95; // Aumenta 5%
                    $('#total').val(novoTotal.toFixed(2).replace('.', ',')); // Atualiza formatado
                }

                // var totalCalculado = $total;
                $('#precoFinal').val(novoTotal.toFixed(2).replace('.', ','));

                function calculaPorcentagem(base, porcentagem) {
                    return (base * parseFloat(porcentagem)) / 100;
                }
                // Evento para manipular descontos e acréscimos em porcentagem
                $('#acressimoP, #descontoP').keyup(function() {
                    let totalConvertido = parseFloat(novoTotal.toFixed(10));
                    let valorPorcentagem = parseFloat($(this).val().replace(',', '.'));

                    if ($(this).val().length > 0) {
                        if ($(this).attr('id') === 'acressimoP') {
                            totalCalculado = totalConvertido + calculaPorcentagem(totalConvertido,
                                valorPorcentagem);
                        } else if ($(this).attr('id') === 'descontoP') {
                            totalCalculado = totalConvertido - calculaPorcentagem(totalConvertido,
                                valorPorcentagem);
                        }
                        $('#precoFinal').val(totalCalculado.toFixed(2).replace('.', ','));
                    } else {
                        // Volta ao valor original se o campo estiver vazio
                        $('#precoFinal').val(totalConvertido.toFixed(2).replace('.', ','));
                    }

                    // Desabilita outros campos enquanto um campo é preenchido
                    toggleInputFields(this);
                });

                // Evento para manipular descontos e acréscimos em reais
                $('#acressimoR, #descontoR').keyup(function() {
                    let totalConvertido = parseFloat(novoTotal.toFixed(10));
                    let valorReais = parseFloat($(this).val().replace(',', '.'));

                    if ($(this).val().length > 0) {
                        if ($(this).attr('id') === 'acressimoR') {
                            totalCalculado = totalConvertido + valorReais;
                        } else if ($(this).attr('id') === 'descontoR') {
                            totalCalculado = totalConvertido - valorReais;
                        }
                        $('#precoFinal').val(totalCalculado.toFixed(2).replace('.', ','));
                    } else {
                        // Volta ao valor original se o campo estiver vazio
                        $('#precoFinal').val(totalConvertido.toFixed(2).replace('.', ','));
                    }

                    // Desabilita outros campos enquanto um campo é preenchido
                    toggleInputFields(this);
                });

                // Função para desabilitar/reenabilitar campos
                function toggleInputFields(currentField) {
                    let fields = ['#acressimoP', '#acressimoR', '#descontoP', '#descontoR'];

                    fields.forEach(field => {
                        if (field !== `#${$(currentField).attr('id')}`) {
                            $(field).prop('disabled', $(currentField).val().length > 0).css('background-color',
                                $(currentField).val().length > 0 ? 'red' : 'white');
                        }
                    });
                }
                // TOKEN DE ASSINATURA
                // let _token = $('meta[name="csrf-token"]').attr('content');
                // SETA O VALOR TOTAL DO KIT
                // $('#total').val($('#valorTotalInput').val());
                /**
                 * FUNCAO QUE PEGA VALOR DIGITADO NO INPUT
                 */

                $("#search").keyup(function() {

                    var id = $("#search").val();

                    console.log(id);
                    $.ajax({
                        url: "/getProductByName",
                        type: "GET",
                        data: {
                            id: id,
                        },
                        success: function(response) {
                            if (response) {
                                $('#result').removeClass('d-none');
                                // CONVERT ARRAY IN JSON FOR EACH FUNCTION
                                var json = $.parseJSON(response.dados);
                                // SHOW ALL RESULT QUERY
                                var index = [];
                                $.each(json, function(i, item) {
                                    index[i] =
                                        '<option class="option-size" value=' +
                                        item
                                        .id + '>' + 'ID: ' + item.id + ' | Nome: ' + item
                                        .title + ' | Estoque: ' + item.available_quantity +
                                        '</option>';
                                });

                                var arr = jQuery.makeArray(index);
                                arr.reverse();
                                $("#result").html(arr);

                                $("select").change(function() {
                                    $('#id').val($(this).children(
                                        "option:selected").val());
                                    $('#name').val($(this).children(
                                            "option:selected")
                                        .text());

                                    var number = $('#id').val();
                                    if (number > 0) {
                                        $("#btnFinalizar").removeClass(
                                            'd-none');
                                        $('#name,#id').addClass(
                                            'p-3 mb-2 bg-warning text-dark');
                                    }
                                });

                            }
                        },
                        error: function(error) {
                            $('#result').html(
                                '<option> Produto Digitado Não Existe! </option>');
                        }
                    });
                });

            });
        </script>
