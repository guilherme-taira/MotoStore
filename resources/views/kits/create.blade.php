@extends('layouts.app')
@section('conteudo')
<style>
    .highlighted-category {
    color: red; /* Cor para destacar */
    font-weight: bold;
}

</style>
<script>

// Array para armazenar os IDs dos produtos selecionados
let selectedProducts = [];

// Evento para monitorar mudanças nos checkboxes
$(document).on('change', '.form-check-input', function() {
    const productId = $(this).val();
    const stockValue = $(`#stock-${productId}`).val(); // Obtém o valor do stock

    if ($(this).is(':checked')) {
        // Verifica se o produto já está no array
        const existingProduct = selectedProducts.find(product => product.id === productId);

        if (!existingProduct) {
            // Adiciona o produto ao array se estiver marcado e não existir
            selectedProducts.push({ id: productId, stock: stockValue });
        }
    } else {
        // Remove o produto do array se for desmarcado
        selectedProducts = selectedProducts.filter(product => product.id !== productId);
    }

    // Exibe o array no console sempre que um checkbox for clicado
    console.log('Produtos selecionados:', selectedProducts);
});


function loadProducts(page = 1) {
    $('#produto-list').empty(); // Limpa a lista de produtos enquanto carrega
    $('#loader').removeClass('d-none');
    $.get(`/api/v1/produtos?page=${page}`, function(data) {
        const products = data.data;
        const pagination = data.links;
        $('#loader').addClass('d-none');
        // Oculta o loader após a requisição
        // Limpa a lista e recria os produtos
        products.forEach(product => {
            const isChecked = selectedProducts.includes(product.id.toString()) ? 'checked' : '';

            $('#produto-list').append(`
                <li class="d-flex align-items-center mb-3 p-3 border rounded shadow-sm">
                    <input class="form-check-input me-2" type="checkbox" value="${product.id}" id="product-${product.id}">
                    <img src="${product.imagem_url}" alt="${product.title}" class="rounded me-3" style="width: 80px; height: 80px; object-fit: cover;">
                    <div class="flex-grow-1">
                        <h6 class="mb-1">${product.title}</h6>
                        <span class="text-muted">Preço: R$${product.priceKit.toFixed(2)}</span>
                        <span class="text-muted ms-3">Estoque Disponível: ${product.available_quantity}</span>

                        <!-- Campo de entrada para a quantidade desejada -->
                        <div class="mt-2">
                            <input type="hidden" id="stock-${product.id}" class="form-control form-control-sm w-25" min="1" max="${product.available_quantity}" value="1">
                        </div>
                    </div>
                </li>
            `);
        });

        // Atualiza a navegação de paginação
        $('#pagination').html('');
        // Adiciona botão "Anterior"
        if (data.prev_page_url) {
            $('#pagination').append(`
                <li class="page-item">
                    <a class="page-link" href="#" onclick="loadProducts(${page - 1}); return false;">Anterior</a>
                </li>
            `);
        }

        // Adiciona números das páginas com índice
        pagination.forEach((pageLink, index) => {
            if (pageLink.url) {

                if(index != pagination.length- 1 || index == 0)
                $('#pagination').append(`
                    <li class="page-item ${pageLink.active ? 'active' : ''}">
                        <a class="page-link" href="#" onclick="loadProducts(${pageLink.label}); return false;">${index}</a>
                    </li>
                `);
            }
        });

            });
        }
</script>

    <!-- Modal de Progresso -->
    <div class="modal fade" id="progressModal" tabindex="-1" aria-labelledby="progressModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="progressModalLabel"> <div class="spinner-border text-success" role="status"> </div> Processando Produtos </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="progress">
                        <div id="progressBar" class="progress-bar" role="progressbar" style="width: 0%;" aria-valuemin="0" aria-valuemax="100">0%</div>
                    </div>
                    <p class="mt-3">Processando <span id="currentProduct">1</span> de <span id="totalProducts"></span> produtos...
                    </p>
                </div>
            </div>
        </div>
    </div>


    <!--- MODAL QUE SELECIONA O MOTORISTA --->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between align-items-center">
                    <h5 class="modal-title text-dark" id="exampleModalLabel">Cadastro Kit <i class="bi bi-bookmarks"></i></h5>
                    <div class="d-flex ms-auto">
                        <input type="text" id="searchItemInput" class="form-control me-2" placeholder="Anúncio Base" style="max-width: 300px;">
                        <button id="searchItemButton" class="btn btn-primary">Copiar</button>
                    </div>
                </div>

                <form method="POST" action="{{ route('kitadd') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col">
                                    <div class="mb-3 row">
                                        <label class="col-lg-2 col-md-6 col-sm-12 col-form-label">Nome:</label>
                                        <div class="col-lg-10 col-md-6 col-sm-12">
                                            <input name="name" id="titleAnuncio" type="text" value="{{old('name')}}" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <p class="col-lg-2 col-md-6 col-sm-12 col-form-label">Acréssimo </p>
                                <div class="col">
                                    <div class="mb-3 row">
                                        <label class="col-lg-2 col-md-6 col-sm-12 col-form-label">%</label>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <input id="acressimoP" class="form-control porcem">
                                        </div>
                                        <label class="col-lg-2 col-md-6 col-sm-12 col-form-label">R$</label>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <input id="acressimoR" type="text" class="form-control porcem">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <p class="col-lg-2 col-md-6 col-sm-12 col-form-label">Desconto </p>
                                <div class="col">
                                    <div class="mb-3 row">
                                        <label class="col-lg-2 col-md-6 col-sm-12 col-form-label">%</label>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <input id="descontoP" type="text" class="form-control porcem">
                                        </div>
                                        <label class="col-lg-2 col-md-6 col-sm-12 col-form-label">R$</label>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <input id="descontoR" type="text" class="form-control porcem">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="col">
                                    <div class="mb-3 row">
                                        <label class="col-lg-2 col-md-6 col-sm-12 col-form-label">Preço:</label>
                                        <div class="col-lg-3 col-md-6 col-sm-12">
                                            <input name="precoFinal" id="precoFinal" value="{{old('precoFinal')}}" type="text" class="form-control" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <div class="mb-3 row">
                                        <label class="col-lg-2 col-md-6 col-sm-12 col-form-label">Image:</label>
                                        <div class="col-lg-10 col-md-6 col-sm-12">
                                            <input class="form-control" type="file" value="{{old('photos')}}" name="photos[]" multiple>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="description" id="description" rows="8"></textarea>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label for="categoria">Categorias:</label>
                            <select class="form-select" name="categoria" id="categoria" aria-label="Default select example">
                                <option selected>Selecione...</option>
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
                                            <select class="form-select" name="categoriaMl" id="categorias" aria-label="Default select example" required>
                                                <option selected disabled>Selecionar</option>
                                            </select>
                                            <button type="button" class="btn btn-secondary" id="resetButton">Reset</button>
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
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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

                    <!-- Botão para adicionar produtos selecionados ao kit -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                       <!-- Botão para enviar produtos selecionados -->
                    <button type="button" class="btn btn-primary" id="adicionarSelecionados">Adicionar Produtos Selecionados</button>


                        <!-- Navegação de paginação -->
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
                        <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#catalogoModal">
                            <i class="bi bi-book me-2"></i> Ver Catálogo
                        </button>
                        {{-- <label for="search" class="input-group-text">SKU / CÓDIGO:</label>
                        <input name="id" id="search" type="text" class="form-control" placeholder="Digite o código do produto">
                        <select class="form-select d-none" multiple id="result"></select> --}}
                    </div>
                </div>
                <input type="hidden" name="id" id="id">
                <input type="hidden" id="name" name="name">
                <div class="col-lg-4 text-end">
                    <button class="btn btn-success w-100 d-none" id="btnFinalizar">Inserir no Kit <i class="bi bi-signpost-2"></i></button>
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
                            $total += $produto['price']; // Acumula o valor de cada produto no total
                        @endphp

                            <li class="d-flex align-items-center mb-3 p-3 border rounded shadow-sm">

                                <img src="{!! Storage::disk('s3')->url('produtos/' . $produto['id'] . '/' . $produto['imagem']) !!}" alt="{{ $produto['nome'] }}" class="rounded me-3" style="width: 80px; height: 80px; object-fit: cover;">

                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $produto['nome'] ?? '' }}</h6>

                                    <!-- Seção de preço e estoque -->
                                    <div class="d-flex align-items-center mb-2">
                                        <span class="text-dark me-3">Preço: R$ {{ number_format($produto['price'], 2, ',', '.') }} Un / Pct </span>
                                        <span class="text-dark">Estoque: {{ $produto['available_quantity'] ?? 0 }} Disponível</span>
                                    </div>

                                    <!-- Campo de quantidade e botão de adicionar -->
                                    <div class="d-flex align-items-center">
                                        <span class="me-2">Quantidade:</span>
                                        <form action="{{ route('adicionarQuantidade', ['id' => $produto['id']]) }}" method="POST" class="d-inline-flex align-items-center">
                                            @csrf
                                            <input name="stock" id="stock-{{ $produto['id'] }}" value="{{ $produto['quantidade'] ?? 1 }}" type="number" class="form-control form-control-sm w-25 me-2">
                                            <button id="btnSalvarQuantidade" class="btn btn-success btn-sm">Adicionar</button>
                                        </form>
                                    </div>
                                </div>

                                <a href="{{ route('deleteSessionRoute', ['id' => $produto['id']]) }}" class="btn btn-danger ms-3">Remover <i class="bi bi-dash-circle-dotted"></i></a>
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
                <input name="price" id="total" type="text" value="{{ number_format($total, 2, ',', '.') }}" class="form-control w-25" readonly>
                <button type="button" class="btn btn-primary ms-2" id="modalbutton" data-bs-toggle="modal" data-bs-target="#exampleModal">Cadastrar Kit <i class="bi bi-pin-map"></i></button>
            </div>
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
                    $('#searchResult').html(`<p class="text-danger">${errorMessage}</p>`);
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
                    const pathList = categoryResponse.path_from_root.map((category, index, array) => {
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



$('#adicionarSelecionados').on('click', function () {
    // Fecha outros modais se estiverem abertos
    $('.modal').modal('hide');

    // Abre o modal de progresso
    $('#progressModal').modal('show');

    // Inicializa o array de produtos selecionados (resetar para evitar duplicações)
    let selectedProducts = [];

    // Captura os IDs e quantidades dos produtos selecionados
    $('#produto-list input[type="checkbox"]:checked').each(function () {
        const id = $(this).val();
        const stock = $(`#stock-${id}`).val(); // Obtém o valor do stock especificado pelo usuário
        selectedProducts.push({ id, stock });
    });

    if (selectedProducts.length === 0) {
        alert("Nenhum produto selecionado.");
        $('#progressModal').modal('hide'); // Fecha o modal de progresso se nenhum produto for selecionado
        return;
    }

    // Inicializa o modal de progresso
    $('#totalProducts').text(selectedProducts.length);

    console.log("PRODUTOS >", selectedProducts.length);
    let completedRequests = 0;

    // Função para atualizar a barra de progresso
    function updateProgress() {
        completedRequests++;
        const progressPercent = (completedRequests / selectedProducts.length) * 100;
        $('#progressBar').css('width', `${progressPercent}%`);
        $('#progressBar').text(`${Math.round(progressPercent)}%`);
        $('#currentProduct').text(completedRequests);

        // Fecha o modal quando todos os produtos forem processados
        if (completedRequests === selectedProducts.length) {
            setTimeout(() => {
                $('#progressModal').modal('hide');
                location.reload(); // Atualiza a página
            }, 1000);
        }
    }

    // Envia uma requisição para cada produto selecionado
    selectedProducts.forEach(item => {
        $.ajax({
            url: `/adicionarQuantidade/${item.id}`,
            type: "POST",
            data: { stock: item.stock },
            success: function (response) {
                console.log(`Produto ID ${item.id} adicionado com sucesso.`);
                updateProgress(); // Atualiza o progresso ao concluir cada requisição
            },
            error: function (xhr, status, error) {
                console.error(`Erro ao adicionar o produto ID: ${item.id}`, error);
                updateProgress(); // Atualiza o progresso mesmo se ocorrer um erro
            }
        });
    });
});




        // Carregar produtos ao abrir o modal
        $('#catalogoModal').on('show.bs.modal', function() {
            loadProducts();
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

function getAllCategorias(){
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
                $("#categorias").off("change").on("change", function() {
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
}

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
        var total = $('#total').val();
        console.log(total);
        // var totalCalculado = $total;
        $('#precoFinal').val(total);

        function calculaPorcentagem(base, porcentagem) {
    return (base * parseFloat(porcentagem)) / 100;
}
// Evento para manipular descontos e acréscimos em porcentagem
$('#acressimoP, #descontoP').keyup(function () {
    let totalConvertido = parseFloat(total.toString().replace(',', '.'));
    let valorPorcentagem = parseFloat($(this).val().replace(',', '.'));

    if ($(this).val().length > 0) {
        if ($(this).attr('id') === 'acressimoP') {
            totalCalculado = totalConvertido + calculaPorcentagem(totalConvertido, valorPorcentagem);
        } else if ($(this).attr('id') === 'descontoP') {
            totalCalculado = totalConvertido - calculaPorcentagem(totalConvertido, valorPorcentagem);
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
$('#acressimoR, #descontoR').keyup(function () {
    let totalConvertido = parseFloat(total.toString().replace(',', '.'));
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
            $(field).prop('disabled', $(currentField).val().length > 0).css('background-color', $(currentField).val().length > 0 ? 'red' : 'white');
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

