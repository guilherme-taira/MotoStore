@extends('layouts.app')
@section('conteudo')
<script>

function loadProducts(page = 1) {
    $.get(`/api/v1/produtos?page=${page}`, function(data) {
        const products = data.data;
        const pagination = data.links;
        // Limpa a lista e recria os produtos
        products.forEach(product => {
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

        // Adiciona números das páginas
        pagination.forEach(pageLink => {
            if (pageLink.url) {
                $('#pagination').append(`
                    <li class="page-item ${pageLink.active ? 'active' : ''}">
                        <a class="page-link" href="#" onclick="loadProducts(${pageLink.label}); return false;">${pageLink.label}</a>
                    </li>
                `);
            }
        });

        // Adiciona botão "Próximo"
        if (data.next_page_url) {
            $('#pagination').append(`
                <li class="page-item">
                    <a class="page-link" href="#" onclick="loadProducts(${page + 1}); return false;">Próximo</a>
                </li>
            `);
        }
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
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-dark" id="exampleModalLabel">Cadastro Kit <i class="bi bi-bookmarks"></i>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                                            <input name="name" type="text" class="form-control">
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
                                            <input name="price" id="precoFinal" type="text" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <div class="mb-3 row">
                                        <label class="col-lg-2 col-md-6 col-sm-12 col-form-label">Image:</label>
                                        <div class="col-lg-10 col-md-6 col-sm-12">
                                            <input class="form-control" type="file" name="photos[]" multiple>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="description" rows="3"></textarea>
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
                                        <select class="form-select" name="categoriaMl" id="categorias"
                                            aria-label="Default select example">
                                            <option selected>...</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" class="form-control" name="id_categoria" id="id_categoria">
                            <input type="text" class="form-control"  name="stock" id="stock">
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
                        <label for="search" class="input-group-text">SKU / CÓDIGO:</label>
                        <input name="id" id="search" type="text" class="form-control" placeholder="Digite o código do produto">
                        <select class="form-select d-none" multiple id="result"></select>
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
                @if (isset($produtos) && count($produtos) > 0)
                    @foreach ($produtos as $produto)
                        @if (!empty($produto['id']))
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
                <input name="price" id="total" type="text" value="{{ $total }}" class="form-control w-25">
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


        $('#adicionarSelecionados').on('click', function() {

            // Fecha outros modais se estiverem abertos
            $('.modal').modal('hide');

            // Abre o modal de progresso
            $('#progressModal').modal('show');

            const selectedProducts = [];

            // Captura os IDs e quantidades dos produtos selecionados
            $('#produto-list input[type="checkbox"]:checked').each(function() {
                const id = $(this).val();
                const stock = $(`#stock-${id}`).val(); // Obtém o valor do stock especificado pelo usuário
                selectedProducts.push({ id, stock });
            });

            if (selectedProducts.length === 0) {
                alert("Nenhum produto selecionado.");
                return;
            }

            // Inicializa o modal de progresso
            $('#progressModal').modal('show');
            $('#totalProducts').text(selectedProducts.length);
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
                        // alert("Todos os produtos foram adicionados com sucesso!");
                        location.reload(); // Atualiza a página
                    }, 6000);
                }
            }

            // Envia uma requisição para cada produto selecionado
            selectedProducts.forEach(item => {
                $.ajax({
                    url: `/adicionarQuantidade/${item.id}`,
                    type: "POST",
                    data: { stock: item.stock },
                    success: function(response) {
                        updateProgress(); // Atualiza o progresso ao concluir cada requisição
                    },
                    error: function(xhr, status, error) {
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
                        $.each(response.children_categories, function(i, item) {
                            index[i] =
                                '<option class="option-size" value=' + item.id + '>' + item
                                .name + '</option>';
                        });

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


        $("form").submit(function(event) {
            // event.preventDefault();
            $("#BtnCadastrar").addClass('d-none');
            $('#carregando').removeClass('d-none');
        });

        // VALOR TOTAL
        var total = $('#total').val();
        var totalCalculado = total;

        $('#precoFinal').val(parseFloat(total).toFixed(2));

        // // MASCARA DE PORCENTAGEM
        // $('.porcem').mask('Z9999.999', {
        //     translation: {
        //         'Z': {
        //             pattern: /[\-\+]/,
        //             optional: true
        //         }
        //     }
        // });

        $('#descontoP').keyup(function() {
            if ($('#descontoP').val().length >= 1) {
                var porcem = $('#descontoP').val();
                totalCalculado = parseFloat(total) - parseFloat(calculaPorcemtagem(total, porcem));
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
                    $('#precoFinal').val(parseFloat(totalCalculado).toFixed(2));
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
            if ($('#acressimoP').val().length >= 1) {
                var porcem = $('#acressimoP').val();
                totalCalculado = parseFloat(total) + parseFloat(calculaPorcemtagem(total, porcem));
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

        // TOKEN DE ASSINATURA
        // let _token = $('meta[name="csrf-token"]').attr('content');
        // SETA O VALOR TOTAL DO KIT
        $('#total').val($('#valorTotalInput').val());
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
