@extends('layouts.app')
@section('conteudo')


    <style>
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


    <div class="card mb-4">
        <div class="card-header">
            Criar Produto
        </div>

        <div class="card-body">
            @if ($errors->any())
                <ul class="alert alert-danger list-unstyled">
                    @foreach ($errors->all() as $error)
                        <li>- {{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            <input type="hidden" name="access_token" id="access_token" value="{{$viewData['access_token']}}">

            <!--- FINAL DO MODAL ---->
            <div id="liveAlertPlaceholder"></div>

            <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data" class="needs-validation">
                @csrf

                <div class="accordion" id="productFormAccordion">
                    <!-- Informações Básicas -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingBasicInfo">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseBasicInfo" aria-expanded="true" aria-controls="collapseBasicInfo">
                                Informações Básicas
                            </button>
                        </h2>
                        <div id="collapseBasicInfo" class="accordion-collapse collapse show"
                            aria-labelledby="headingBasicInfo" data-bs-parent="#productFormAccordion">
                            <div class="accordion-body">
                                <div class="d-flex ms-auto">
                                    <input type="text" id="searchItemInput" class="form-control me-2" placeholder="Anúncio Base" style="max-width: 300px;">
                                    <button id="searchItemButton" class="btn btn-primary">importar <i class="bi bi-back"></i></button>
                                </div>
                                <div class="row mb-3">
                                    <div class="col">
                                        <label class="col-lg-2 col-md-4 col-sm-12 col-form-label">Imagem:</label>
                                        <div class="col-lg-10 col-md-6 col-sm-12">
                                            <input class="form-control" type="file" id="file" name="photos[]"
                                                multiple required>
                                            @error('photos')
                                                <span class="badge text-bg-danger">Foto é um campo Obrigatório.</span>
                                            @enderror
                                        </div>

                                        <div class="container mt-4">
                                            <div id="imagePreview" class="image-container-preview"></div>
                                            <div id="imagePreviewHidden"></div>
                                        </div>
                                        <div id="image-count mt-4"></div>
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="isPublic">Ativo / Público:</label>
                                        <select name="isPublic" class="form-control" required>
                                            <option value="1" selected>SIM</option>
                                            <option value="0">NÃO</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="isNft">NFT:</label>
                                        <select name="isNft" class="form-control" required>
                                            <option value="1">SIM</option>
                                            <option value="0" selected>NÃO</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="name" class="form-label">Titulo:</label>
                                        <input name="name" id="titulo" type="text" value="{{ old('name') }}"
                                            class="form-control" required>
                                    </div>
                                    <div class="col-lg-4">
                                        <label for="brand" class="form-label">Marca:</label>
                                        <input name="brand" id="brand" type="text" value="{{ old('brand') }}"
                                            class="form-control" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Preço e Estoque -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingPricing">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapsePricing" aria-expanded="false" aria-controls="collapsePricing">
                                Preço e Estoque
                            </button>
                        </h2>
                        <div id="collapsePricing" class="accordion-collapse collapse" aria-labelledby="headingPricing"
                            data-bs-parent="#productFormAccordion">
                            <div class="accordion-body">
                                <div class="row mb-3">
                                    <div class="col-lg-3">
                                        <label for="precoNormal">Preço R$:</label>
                                        <input name="price" id="precoNormal" value="{{ old('price') }}" type="text"
                                            class="form-control" required>
                                        @error('price')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="pricePromotion">Preço Promocional:</label>
                                        <input name="pricePromotion" type="text" value="0" class="form-control">
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="stock">Estoque:</label>
                                        <input name="stock" id="stock" type="number" value="{{ old('stock') }}"
                                            class="form-control" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Atributos -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingAttributes">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseAttributes" aria-expanded="false"
                                aria-controls="collapseAttributes">
                                Atributos
                            </button>
                        </h2>
                        <div id="collapseAttributes" class="accordion-collapse collapse"
                            aria-labelledby="headingAttributes" data-bs-parent="#productFormAccordion">
                            <div class="accordion-body">

                                <div class="mb-3">
                                    <label class="form-label">Descrição:</label>
                                    <textarea required class="form-control" id="description" name="description"
                                        rows="3">{{ old('description') }}</textarea>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-lg-2">
                                        <label for="termometro">Valor Termômetro:</label>
                                        <input type="number" name="termometro" id="termometro"
                                            value="100" min="0" max="150"
                                            class="form-control">
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="ean">GTIN / EAN:</label>
                                        <input name="ean" value="{{ old('ean') }}" id="ean" class="form-control" required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-lg-2">
                                        <label for="width">Largura:</label>
                                        <input name="width" id="packageWidth" value="{{ old('width') }}" class="form-control" required>
                                    </div>
                                    <div class="col-lg-2">
                                        <label for="height">Altura:</label>
                                        <input name="height" id="packageHeight" value="{{ old('height') }}" class="form-control" required>
                                    </div>
                                    <div class="col-lg-2">
                                        <label for="length">Comprimento:</label>
                                        <input name="length" id="packageLength" value="{{ old('length') }}" class="form-control" required>
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <label>Tipo de Anúncio :</label>
                                    <select name="tipo_anuncio" class="form-control" aria-label=".form-select-sm example"
                                        required>
                                        <option value="gold_special">Clássico</option>
                                        <option value="gold_pro">Premium</option>
                                    </select>
                                </div>

                                <div class="row mb-3">

                                    <div class="col-md-8">
                                        <label for="preco" class="form-label">Fornecedores
                                            <div id="loadingF" class="spinner-border spinner-border-sm d-none" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </label>
                                        <div class="input-group">
                                            <select id="fornecedor-select" name="fornecedor" class="form-select" style="max-width: 240px;">
                                                <option value="{{ Auth::user()->id }}">{{ Auth::user()->name }}</option>
                                                {{-- @foreach ($viewData['fornecedor'] as $fornecedor)
                                                    <option class="bg-warning" value="{{ $fornecedor->id }}" {{ old('fornecedor') == $fornecedor->id ? 'selected' : '' }}>
                                                        {{ $fornecedor->name }}
                                                    </option>
                                                @endforeach --}}
                                            </select>
                                            {{-- <span class="input-group-text"><i class="bi bi-person-lines-fill"></i></span>
                                            <input type="text" id="fornecedor-input" class="form-control"
                                                placeholder="digite o nome do fornecedor"> --}}
                                        </div>
                                    </div>

                                    <div class="col-md-6 mt-2">
                                        <label for="categoria">Categorias:</label>
                                        <select class="form-select mt-2" name="categoria" id="categoria"  required
                                            aria-label="Default select example">
                                            @foreach ($viewData['categorias'] as $categoria)
                                                <option class="bg-dark text-white" disabled>{{ $categoria['nome'] }}
                                                </option>
                                                @foreach ($categoria['subcategory'] as $subcategoria)
                                                    <option value="{{ $subcategoria->id }}"> - {{ $subcategoria->name }}
                                                    </option>
                                                @endforeach
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <label>Categoria Mercado Livre:</label>
                                    <div class="input-group">
                                        <select class="form-select" id="categorias" aria-label="Default select example" required>
                                            <option selected disabled>Selecionar</option>
                                        </select>
                                        <button type="button" class="btn btn-secondary" id="resetButton">Reset</button>
                                    </div>


                                <input type="hidden" class="form-control" name="id_categoria" id="id_categoria">


                                <div class="col-md-12 mt-3">
                                    <div class="col">
                                        <div class="mb-3 row">
                                            <ol class="list-group list-group-numbered content_categorias">
                                            </ol>
                                        </div>
                                    </div>
                                </div>

                                <div id="formContainer"></div>
                            </div>

                            </div>
                        </div>
                    </div>

                    <!-- Taxas -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingFees">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseFees" aria-expanded="false" aria-controls="collapseFees">
                                Taxas
                            </button>
                        </h2>
                        <div id="collapseFees" class="accordion-collapse collapse" aria-labelledby="headingFees"
                            data-bs-parent="#productFormAccordion">
                            <div class="accordion-body">
                                <div class="row mb-3">
                                    <div class="col">
                                        <p class="col-lg-2 col-md-6 col-sm-12 col-form-label">Acréssimo </p>
                                        <div class="col">
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-md-6 col-sm-12 col-form-label">%</label>
                                                <div class="col-lg-3 col-md-6 col-sm-6">
                                                    <input id="acressimoP" class="form-control porcem" value="{{ old('acressimoP') }}">
                                                </div>
                                                <label class="col-lg-2 col-md-6 col-sm-12 col-form-label">R$</label>
                                                <div class="col-lg-3 col-md-6 col-sm-6">
                                                    <input id="acressimoR" name="acressimoR" type="text"
                                                        class="form-control porcem" value="{{ old('acressimoR') }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3 row">
                                            <label class="col-lg-2 col-md-6 col-sm-12 col-form-label">Bruto:</label>
                                            <div class="col-lg-3 col-md-6 col-sm-12">
                                                <input name="priceBruto" id="precoFinal" value="{{ old('price') }}"
                                                    type="text" class="form-control">
                                            </div>

                                            <label class="col-lg-2 col-md-6 col-sm-12 col-form-label">Liquído: </label>
                                            <div class="col-lg-3 col-md-6 col-sm-12">
                                                <input name="fee" value="{{ old('fee') }}" id="precoLiquido" type="text" class="form-control" required>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="col">
                                                <div class="mb-3 row">

                                                    <label class="col-lg-2 col-md-6 col-sm-12 col-form-label">Taxa %:
                                                    </label>
                                                    <div class="col-lg-2 col-md-6 col-sm-12">
                                                        <input name="taxaFee" id="taxaFee" type="text"
                                                            value="4.99" class="form-control" readonly>
                                                    </div>

                                                    <label class="col-lg-1 col-md-6 col-sm-12 col-form-label">Final:
                                                    </label>
                                                    <div class="col-lg-2 col-md-6 col-sm-12">
                                                        <input name="PriceWithFee" id="PriceWithFee" type="text"
                                                            class="form-control"  value="{{ old('PriceWithFee') }}" required>
                                                    </div>

                                                    <label class="col-lg-1 col-md-6 col-sm-12 col-form-label">Preço Kit:
                                                    </label>
                                                    <div class="col-lg-2 col-md-3 col-sm-12">
                                                        <input name="priceKit" id="priceKit" type="text"
                                                            class="form-control"  value="{{ old('priceKit') }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">Cadastrar <i class="bi bi-hdd"></i></button>
                    </div>
            </form>



            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

            <script>
                $(document).on('click', '#searchItemButton', function(e) {
                 e.preventDefault();
                 const itemId = $('#searchItemInput').val().trim();

                 if (itemId === '') {
                     alert('Por favor, insira um ID de item.');
                     return;
                 }

                 // URL base da API do Mercado Livre
                 const apiBaseUrl = 'https://api.mercadolibre.com/items/';

                 // Seu token de acesso (coloque dinamicamente se possível)
                 const accessToken = $("#access_token").val();

                 // Função genérica para fazer a requisição AJAX com Bearer Token
                 function fetchData(url, successCallback, errorMessage) {
                 $.ajax({
                     url: url,
                     type: 'GET',
                     headers: {
                     'Authorization': `Bearer ${accessToken}`
                     },
                     success: successCallback,
                     error: function () {
                     $('#searchResult').html(
                         `<p class="text-danger">${errorMessage}</p>`
                     );
                     }
                 });
                 }
                 // Requisição para os dados do item
                 fetchData(
                     `${apiBaseUrl}${itemId}`,
                     function(response) {

                         $("#titulo").val(response.title);
                         $("#precoNormal").val(response.price);
                         $("#stock").val(response.initial_quantity);
                         $("#id_categoria").val(response.category_id);

                           // Itera sobre os atributos do item
                         if (response.attributes && Array.isArray(response.attributes)) {
                             response.attributes.forEach(attribute => {
                                 switch (attribute.id) {
                                     case 'BRAND':
                                         $("#brand").val(attribute.value_name); // Marca
                                         break;
                                     case 'GTIN':
                                         $("#ean").val(attribute.value_name); // Código universal
                                         break;
                                     case 'IS_KIT':
                                         $("#isKit").val(attribute.value_name); // É kit
                                         break;
                                     case 'NET_WEIGHT':
                                         $("#netWeight").val(attribute.value_name); // Peso líquido
                                         break;
                                     case 'PACKAGE_HEIGHT':
                                         $("#packageHeight").val(attribute.value_name?.replace(" cm", "")); // Altura da embalagem
                                         break;
                                     case 'PACKAGE_LENGTH':
                                         $("#packageLength").val(attribute.value_name?.replace(" cm", "")); // Comprimento da embalagem
                                         break;
                                     case 'PACKAGE_WEIGHT':
                                         $("#packageWeight").val(attribute.value_name); // Peso da embalagem
                                         break;
                                     case 'PACKAGE_WIDTH':
                                         $("#packageWidth").val(attribute.value_name?.replace(" cm", "")); // Largura da embalagem
                                         break;
                                     case 'ITEM_CONDITION':
                                         $("#itemCondition").val(attribute.value_name); // Condição do item
                                         break;
                                     case 'SALE_FORMAT':
                                         $("#saleFormat").val(attribute.value_name); // Formato de venda
                                         break;
                                     case 'SELLER_SKU':
                                         $("#sellerSku").val(attribute.value_name); // SKU do vendedor
                                         break;
                                     default:
                                         console.log(`Atributo não mapeado: ${attribute.id}`);
                                 }
                             });
                         }
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
         </script>

            <script>


                // Certifique-se de que esta função esteja no escopo global
                function removeImage(index) {
                    $('#imagePreview .image-item').eq(index).remove();
                    $('#imagePreview .image-item').each(function(newIndex) {
                        $(this).find('.image-badge').text(newIndex + 1);
                        $(this).find('.main-image-label').remove();
                        if (newIndex === 0) {
                            $(this).prepend('<span class="main-image-label">Imagem Principal</span>');
                        }
                    });
                    $('#image-count').text('Total de fotos: ' + $('#imagePreview .image-item').length);
                }

                $(document).ready(function() {

                    $('#fornecedor-input').on('input', function() {
                        let inputVal = $(this).val();

                        if (inputVal.length >= 1) { // Começa a buscar após 2 caracteres
                            $.ajax({
                                url: '/api/v1/fornecedores',
                                data: {
                                    name: inputVal
                                },
                                success: function(data) {
                                    // Esconde o spinner após o carregamento
                                    $("#spinner").removeClass('d-none');
                                    // Limpa as opções existentes
                                    $('#fornecedor-select').empty().append(
                                        '<option value="">Selecione um fornecedor</option>');

                                    // Adiciona os novos fornecedores ao select
                                    $.each(data, function(index, fornecedor) {
                                        $('#fornecedor-select').append('<option value="' +
                                            fornecedor.id + '">' + fornecedor.name +
                                            '</option>');
                                    });

                                    // Esconde o spinner após o carregamento
                                    $("#spinner").addClass('d-none');
                                },
                                error: function(xhr) {
                                    console.error('Erro ao buscar fornecedores', xhr);
                                }
                            });
                        } else {
                            // Limpa o select se menos de 2 caracteres
                            $('#fornecedor-select').empty().append(
                                '<option value="">Selecione um fornecedor</option>');
                        }
                    });

                    // $('input[name="price"]').mask('000.000.000,00', {
                    //     reverse: true
                    // });
                    $('input[name="acressimoR"]').mask('000.000.000,00', {
                        reverse: true
                    });

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



                    function getUnmaskedValue(input) {
                        let value = input.val().replace(/\./g, '').replace(',',
                        '.'); // Remove pontos e substitui vírgula por ponto
                        return parseFloat(value) || 0; // Converte para número
                    }

                    var i = 0;

                    getAllCategorias();

                    function getAllCategorias(){
                        $.ajax({
                        url: "/meli/categories",
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
                                        $("#id_categoria").val(ids); // COLOCA O ID DA CATEGORIA NO CAMPO
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



                    $('#file').change(function() {
                        var formData = new FormData();

                        var files = $('#file')[0].files;
                        for (var i = 0; i < files.length; i++) {
                            formData.append('file[]', files[i]);
                        }

                        $.ajax({
                            url: '/api/v1/fotoPreview', // Rota para o método 'upload' no controlador 'UploadController'
                            type: 'POST',
                            data: formData,
                            contentType: false,
                            processData: false,
                            success: function(data) {

                                $('#imagePreview').empty();
                                $('#image-count').text('Total de fotos: ' + data.length);

                                $.each(data, function(index, imageUrl) {

                                    console.log(imageUrl);
                                    var mainLabel = index === 0 ?
                                        '<span class="main-image-label">Imagem Principal</span>' :
                                        '';
                                    var deleteButton =
                                        '<button type="button" class="delete-button" onclick="removeImage(' +
                                        index + ')">Excluir</button>';


                                    $('#imagePreview').append(
                                        '<div class="image-item position-relative">' +
                                        mainLabel +
                                        '<img src="' + imageUrl.url + '" class="img-fluid">' +
                                        deleteButton +
                                        '</div>'
                                    );
                                });

                                if (data.length > 0) {
                                    $('#clearImages').show();
                                }

                                $('#imagePreview').sortable({
                                    axis: "x",
                                    start: function(event, ui) {
                                        $('#imageContainer').css('overflow-x', 'hidden');
                                    },
                                    stop: function(event, ui) {
                                        $('#imageContainer').css('overflow-x', 'auto');
                                    },
                                    update: function(event, ui) {
                                        // Remove a etiqueta "Imagem Principal" de todas as imagens
                                        $('#imagePreview .main-image-label').remove();

                                        // Atualiza a numeração e adiciona "Imagem Principal" à primeira imagem
                                        $('#imagePreview .image-item').each(function(
                                        index) {
                                            $(this).find('.image-badge').text(
                                                index + 1);
                                            if (index === 0) {
                                                $(this).prepend(
                                                    '<span class="main-image-label">Imagem Principal</span>'
                                                    );
                                            }
                                        });
                                    }
                                });
                            }
                        });
                    });


                    // Evento para adicionar imagens ao selecionar arquivos
                    $('#file').on('change', function(e) {
                        var files = e.target.files;

                        // Limpa o contêiner de imagens e a contagem
                        $('#image-container').empty();

                        // Adiciona as imagens ao contêiner
                        for (var i = 0; i < files.length; i++) {
                            var file = files[i];
                            var reader = new FileReader();
                            reader.onload = function(e) {
                                $('#image-container').append('<div class="image-item"><img src="' + e.target
                                    .result + '"><button class="remove-icon">X</button></div>');
                                updateImageCount(); // Atualiza a contagem de imagens
                            };
                            reader.readAsDataURL(file);
                        }

                        // // Reseta o input file para limpar os arquivos selecionados
                        // $(this).val('');
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


                    $('#precoNormal').keyup(function() {
                        var selectedValue = $(this).val();

                        // Remover input anterior, se existir
                        $('#inputContainer').empty();
                        $('#precoFinal').val($("#precoNormal").val());

                        // valor com as taxas calculo final
                        valorProduto = (getUnmaskedValue($(this)) / 0.95);
                        // claculo do valor liquido
                        // totalLiquido = parseFloat($('#precoFinal').val()) - parseFloat($('#precoNormal').val());
                        $('#precoFinal').val(valorProduto.toFixed(2));

                    });

                    // VALOR TOTAL
                    var total = $('#precoNormal').val();
                    var totalCalculado = total;
                    var totalLiquido = 0;
                    var valorProduto = 0;
                    var taxa = $("#taxaFee").val();

                    function calculaPorcemtagem(valor, porcem) {
                        //60 x 25% = 160 (25/100) = 160 x 0,25 = 40.
                        return valor * (porcem / 100);
                    }

                    $('#precoFinal').val(0);
                    $('#acressimoP').keyup(function() {

                        total = $('#precoNormal').val();
                        $('#precoFinal').val(parseFloat(total).toFixed(2));

                        if ($('#acressimoP').val().length >= 1) {
                            var porcem = $('#acressimoP').val();

                            totalCalculado = parseFloat($('#precoNormal').val()) + parseFloat(calculaPorcemtagem($(
                                '#precoNormal').val(), porcem));

                            $('#precoFinal').val(parseFloat(totalCalculado).toFixed(2));

                            valorProduto = (parseFloat($("#precoFinal").val()) / 0.951);
                            // claculo do valor liquido
                            totalLiquido = parseFloat($('#precoFinal').val()) - parseFloat($('#precoNormal').val());
                            // preço liquido final
                            $("#precoLiquido").val(totalLiquido.toFixed(2));

                            // coloca o valor final
                            $("#PriceWithFee").val(parseFloat(valorProduto).toFixed(2));

                            $('#acressimoR').prop("disabled", true).css({
                                'background-color': '#cecece'
                            });
                        } else {
                            $('#acressimoR').prop("disabled", false).css({
                                'background-color': 'white'
                            });
                        }
                    });

                    $('#acressimoR').keyup(function() {

                        total = $('#precoNormal').val();
                        $('#precoFinal').val(parseFloat(total).toFixed(2));


                        if ($('#acressimoR').val().length >= 1) {
                            var reais = $('#acressimoR').val();
                            totalCalculado = parseFloat(total) + getUnmaskedValue($(this));

                            $('#precoFinal').val(parseFloat(totalCalculado).toFixed(2));

                            // valor com as taxas calculo final
                            valorProduto = (parseFloat($("#precoFinal").val()) / 0.951);

                            valorKit = (parseFloat(total) / 0.90);
                            $("#priceKit").val(parseFloat(valorKit).toFixed(2));
                            // claculo do valor liquido
                            totalLiquido = parseFloat($('#precoFinal').val()) - parseFloat($('#precoNormal').val());
                            // preço liquido final
                            $("#precoLiquido").val(totalLiquido.toFixed(2));

                            // coloca o valor final
                            $("#PriceWithFee").val(parseFloat(valorProduto).toFixed(2));

                            $('#acressimoP').prop("disabled", true).css({
                                'background-color': '#cecece'
                            });

                        } else {
                            totalCalculado = parseFloat(total) + 0;
                            $('#precoFinal').val(parseFloat(totalCalculado).toFixed(2));
                            $('#acressimoP').prop("disabled", false).css({
                                'background-color': 'white'
                            });
                        }


                    });
                });
            </script>
        @endsection
