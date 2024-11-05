@extends('layouts.app')
@section('conteudo')
    <div class="card mb-4">
        <div class="card-header">
            Editar Produto
        </div>

        <div class="card-body">
            @if ($errors->any())
                <ul class="alert alert-danger list-unstyled">
                    @foreach ($errors->all() as $error)
                        <li>- {{ $error }}</li>
                    @endforeach
                </ul>
            @endif


           <!--- FINAL DO MODAL ---->
           <div id="liveAlertPlaceholder"></div>

           <form method="POST" action="{{ route('products.update', ['id' => $viewData['product']->id]) }}" enctype="multipart/form-data" class="needs-validation">
            @method('PUT')
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
                               <div class="row mb-3">
                                   <div class="col">
                                    <div class="row imagemCs">
                                        @foreach ($viewData['photos'] as $foto)
                                        <div class="col row-cols-1 row-cols-md g-2">
                                            <div class="col">
                                                    <div class="foto-container position-relative">
                                                        <img src="{{ $foto }}" alt="{{ $viewData['product']->getName() }}"
                                                            class="img-fluid border border-3 border-secondary rounded imagem">
                                                        <span
                                                            class="icone-lixeira position-absolute top-0 end-0 m-2 p-2 bg-light rounded-circle"><i
                                                                class="fas fa-trash-alt text-danger"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <hr class="mt-3">
                                       <label class="col-lg-2 col-md-4 col-sm-12 col-form-label">Imagem:</label>
                                       <div class="col-lg-10 col-md-6 col-sm-12">
                                           <input class="form-control" type="file" id="file" name="photos[]"
                                               multiple>
                                           @error('photos')
                                               <span class="badge text-bg-danger">Foto é um campo Obrigatório.</span>
                                           @enderror
                                       </div>

                                       <div class="container mt-4">
                                           <div id="imagePreview" class="image-container-preview"></div>
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
                                       <label for="name" class="form-label">Nome:</label>
                                       <input name="name" type="text" value="{{ $viewData['product']->title }}"
                                           class="form-control" required>
                                   </div>
                                   <div class="col-lg-4">
                                       <label for="brand" class="form-label">Marca:</label>
                                       <input name="brand" type="text" value="{{ $viewData['product']->brand }}"
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
                                       <input name="price" id="precoNormal" value="{{ $viewData['product']->price }}" type="text"
                                           class="form-control" required>
                                       @error('price')
                                           <span class="text-danger">{{ $message }}</span>
                                       @enderror
                                   </div>
                                   <div class="col-lg-3">
                                       <label for="pricePromotion">Preço Promocional:</label>
                                       <input name="pricePromotion" value="0" type="text" class="form-control">
                                   </div>
                                   <div class="col-lg-3">
                                       <label for="stock">Estoque:</label>
                                       <input name="stock" type="number" value="{{ $viewData['product']->available_quantity }}"
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
                                   <textarea required class="form-control" name="description"
                                       rows="3">{{ $viewData['product']->description }}</textarea>
                               </div>

                               <div class="row mb-3">
                                   <div class="col-lg-2">
                                       <label for="termometro">Valor Termômetro:</label>
                                       <input type="number" name="termometro" id="termometro"
                                           value="{{ $viewData['product']->termometro }}" min="0" max="150"
                                           class="form-control">
                                   </div>
                                   <div class="col-lg-3">
                                       <label for="ean">GTIN / EAN:</label>
                                       <input name="ean" value="{{ $viewData['product']->gtin }}" class="form-control" required>
                                   </div>
                               </div>

                               <div class="row mb-3">
                                   <div class="col-lg-2">
                                       <label for="width">Largura:</label>
                                       <input name="width" value="{{ $viewData['product']->width }}" class="form-control" required>
                                   </div>
                                   <div class="col-lg-2">
                                       <label for="height">Altura:</label>
                                       <input name="height" value="{{ $viewData['product']->height }}" class="form-control" required>
                                   </div>
                                   <div class="col-lg-2">
                                       <label for="length">Comprimento:</label>
                                       <input name="length" value="{{ $viewData['product']->length }}" class="form-control" required>
                                   </div>
                               </div>

                               <div class="col-lg-3">
                                <label for="tipo_anuncio">Tipo de Anúncio:</label>
                                <select name="tipo_anuncio" id="tipo_anuncio" class="form-control" aria-label=".form-select-sm example" required>
                                    @php
                                        $tiposAnuncio = [
                                            'gold_special' => 'Clássico',
                                            'gold_pro' => 'Premium'
                                        ];
                                        $tipoAtual = $viewData['product']->listing_type_id ?? '';
                                    @endphp

                                    @foreach ($tiposAnuncio as $valor => $label)
                                        <option value="{{ $valor }}" {{ $tipoAtual == $valor ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>


                               <div class="row mb-3">

                                   <div class="col-md-8">
                                       <label for="preco" class="form-label">Fornecedores
                                           <div id="loadingF" class="spinner-border spinner-border-sm d-none"
                                               role="status">
                                               <span class="visually-hidden">Loading...</span>
                                           </div>
                                       </label>
                                       <div class="input-group">
                                        <select id="fornecedor-select" name="fornecedor" class="form-select" style="max-width: 240px;">
                                            <option value="">Selecione..</option>
                                            @foreach ($viewData['fornecedor'] as $fornecedor)
                                                <option class="bg-warning" value="{{ $fornecedor->id }}"
                                                    {{ old('fornecedor', $viewData['product']->fornecedor_id ?? '') == $fornecedor->id ? 'selected' : '' }}>
                                                    {{ $fornecedor->name }}
                                                </option>
                                            @endforeach
                                        </select>

                                           <span class="input-group-text"><i class="bi bi-person-lines-fill"></i></span>
                                           <input type="text" id="fornecedor-input" class="form-control"
                                               placeholder="digite o nome do fornecedor">

                                       </div>
                                   </div>

                                   <div class="col-md-6 mt-2">
                                       <label for="categoria">Categorias:</label>
                                       <select class="form-select mt-2" name="categoria" id="categoria" required aria-label="Default select example">
                                        @foreach ($viewData['categorias'] as $categoria)
                                            <option class="bg-dark text-white" disabled>{{ $categoria['nome'] }}</option>
                                            @foreach ($categoria['subcategory'] as $subcategoria)
                                                <option value="{{ $subcategoria->id }}" {{ (old('categoria') ?? ($viewData['product']->subcategoria ?? '')) == $subcategoria->id ? 'selected' : '' }}>
                                                    - {{ $subcategoria->name }}
                                                </option>
                                            @endforeach
                                        @endforeach
                                    </select>
                                   </div>
                               </div>

                               <div class="col-lg-3">
                                   <label>Categoria Mercado Livre:</label>

                                   <div class="alert alert-primary" id="mercadoLivreCategoria" role="alert">

                                  </div>

                                   <div class="input-group">
                                       <select class="form-select" id="categorias" aria-label="Default select example" required>
                                           <option selected disabled>Selecionar</option>
                                       </select>
                                       <button type="button" class="btn btn-secondary" id="resetButton">Reset</button>
                                </div>

                               <input type="hidden" class="form-control" value="{{$viewData['product']->category_id}}" name="categoria_mercadolivre" id="id_categoria">

                               <div class="col-md-12 mt-3">
                                   <div class="col">
                                       <div class="mb-3 row">
                                           <ol class="list-group list-group-numbered content_categorias">
                                           </ol>
                                       </div>
                                   </div>
                               </div>
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
                                                       class="form-control porcem" value="{{ $viewData['product']->fee }}">
                                               </div>
                                           </div>
                                       </div>

                                       <div class="mb-3 row">
                                           <label class="col-lg-2 col-md-6 col-sm-12 col-form-label">Bruto:</label>
                                           <div class="col-lg-3 col-md-6 col-sm-12">
                                               <input id="precoFinal" value="{{ old('price') }}"
                                                   type="text" class="form-control">
                                           </div>
                                           <label class="col-lg-2 col-md-6 col-sm-12 col-form-label">Liquído: </label>
                                           <div class="col-lg-3 col-md-6 col-sm-12">
                                               <input name="fee" value="{{ $viewData['product']->fee }}" id="precoLiquido" type="text" class="form-control">
                                           </div>
                                       </div>

                                       <div class="col-md-12">
                                           <div class="col">
                                               <div class="mb-3 row">

                                                   <label class="col-lg-2 col-md-3 col-sm-12 col-form-label">Taxa %:
                                                   </label>
                                                   <div class="col-lg-1 col-md-6 col-sm-12">
                                                       <input name="taxaFee" id="taxaFee" type="text"
                                                           value="4.99" class="form-control">
                                                   </div>

                                                   <label class="col-lg-1 col-md-6 col-sm-12 col-form-label">Final:
                                                   </label>
                                                   <div class="col-lg-2 col-md-3 col-sm-12">
                                                       <input name="PriceWithFee" id="PriceWithFee" type="text"
                                                           class="form-control"  value="{{ $viewData['product']->priceWithFee }}">
                                                   </div>
                                                   <label class="col-lg-2 col-md-6 col-sm-12 col-form-label">Preço Kit:
                                                    </label>
                                                    <div class="col-lg-2 col-md-3 col-sm-12">
                                                        <input name="priceKit" id="priceKit" type="text"
                                                            class="form-control"  value="{{ $viewData['product']->priceKit }}">
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

        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

    <script>
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

                    var i = 0;
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


                    getNameCategory($("#id_categoria").val());

                    // FUNCAO PARA CHAMAR CATEGORIAS
                    function getNameCategory(category) {
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
                                            url: " https://api.mercadolibre.com/categories/"+category,
                                            type: "GET",
                                            success: function(response) {
                                                $("#mercadoLivreCategoria").append(response.name);
                                            }
                                        });
                                    }

                                }
                            },
                            error: function(error) {
                                $('#result').html(
                                    '<option> Produto Digitado Não Existe! </option>'
                                );
                            }
                        });

                    }


            $(".icone-lixeira").click(function(event){

                if (confirm("Tem certeza que deseja apagar esta foto?")) {
                $(this).closest('.col').remove(); // Remove o contêiner da coluna que envolve a foto
                var urlImagem = $(this).siblings("img").attr("src");

                $("#loading-api").removeClass('d-none');

                //APAGAR A FOTO ROTA POST /
                $.ajax({
                    type: "POST",
                    url: "http://127.0.0.1:8000/api/v1/deleteFoto",
                    data: {
                        'imagem': urlImagem
                    },
                    success: function(response) {
                        // Ação a ser realizada em caso de sucesso
                        const alertPlaceholder = document.getElementById('liveAlertPlaceholder')

                        const alert = (message, type) => {
                            const wrapper = document.createElement('div')
                            wrapper.innerHTML = [
                                `<div class="alert alert-${type} alert-dismissible" role="alert">`,
                                `   <div>${message}</div>`,
                                '   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>',
                                '</div>'
                            ].join('')

                            $(event.target).fadeOut("slow", function() {
                                $(this).remove();
                            });


                            alertPlaceholder.append(wrapper)
                        }

                        alert(response.res, 'success');
                        $("#loading-api").addClass('d-none');
                    },
                    error: function(xhr, status, error) {
                        // Ação a ser realizada em caso de erro

                        console.error("Erro ao deletar a foto:", error);
                    }
                // Aqui você obtém o src da foto
                });
             }
            });




            $('#feeClass').change(function() {
                var selectedValue = $(this).val();
                // Remover input anterior, se existir
                $('#inputContainer').empty();

                // Criar novo input com a máscara apropriada
                if (selectedValue === '1') {
                    $("#precoFinal").val($("#precoNormal").val());
                    $('#exampleModal').modal('show');
                } else if (selectedValue === '2') {
                    $("#precoFinal").val($("#precoNormal").val());
                    $('#exampleModal').modal('show');

                }

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

            $('#precoFinal').val(parseFloat(total).toFixed(2));


            $('#acressimoP').keyup(function() {

                total = $('#precoNormal').val();
                $('#precoFinal').val(parseFloat(total).toFixed(2));

                if ($('#acressimoP').val().length >= 1) {
                    var porcem = $('#acressimoP').val();
                    totalCalculado = parseFloat($('#precoNormal').val()) + parseFloat(calculaPorcemtagem($(
                        '#precoNormal').val(), porcem));
                    $('#precoFinal').val(parseFloat(totalCalculado).toFixed(2));

                    totalLiquido = parseFloat($('#precoFinal').val()) - parseFloat($('#precoNormal').val());
                    $("#precoLiquido").val(totalLiquido.toFixed(2));

                    $('#acressimoR').prop("disabled", true).css({
                        'background-color': '#cecece'
                    });
                    $('#descontoP').prop("disabled", true).css({
                        'background-color': '#cecece'
                    });;
                    $('#descontoR').prop("disabled", true).css({
                        'background-color': '#cecece'
                    });
                } else {
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

                total = $('#precoNormal').val();
                $('#precoFinal').val(parseFloat(total).toFixed(2));


                if ($('#acressimoR').val().length >= 1) {
                    var reais = $('#acressimoR').val();
                    totalCalculado = parseFloat(total) + parseFloat(reais);

                    $('#precoFinal').val(parseFloat(totalCalculado).toFixed(2));

                    // valor com as taxas calculo final
                    valorProduto = (parseFloat($("#precoFinal").val()) / 0.95);

                    valorKit = $("#priceKit").val(parseFloat(total / 0.90).toFixed(2));
                    // claculo do valor liquido
                    totalLiquido = parseFloat($('#precoFinal').val()) - parseFloat($('#precoNormal').val());
                    // preço liquido final
                    $("#precoLiquido").val(totalLiquido.toFixed(2));

                    // coloca o valor final
                    $("#PriceWithFee").val(parseFloat(valorProduto).toFixed(2));

                    $('#acressimoP').prop("disabled", true).css({
                        'background-color': '#cecece'
                    });
                    $('#descontoP').prop("disabled", true).css({
                        'background-color': '#cecece'
                    });;
                    $('#descontoR').prop("disabled", true).css({
                        'background-color': '#cecece'
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
        });
    </script>
@endsection
