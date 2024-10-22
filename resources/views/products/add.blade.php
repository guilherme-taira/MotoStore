@extends('layouts.app')
@section('conteudo')
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


            <!--- FINAL DO MODAL ---->
            <div id="liveAlertPlaceholder"></div>




            <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data"  class="needs-validation">
                @csrf
                <div class="row mt-4">
                    <div class="col">
                        <div class="mb-3 row">
                            <label class="col-lg-2 col-md-4 col-sm-12 col-form-label">Image:</label>
                            <div class="col-lg-10 col-md-6 col-sm-12">
                                <input class="form-control" type="file" id="file" name="photos[]" multiple required>
                                @error('photos')
                                    <span class="badge text-bg-danger">Foto é um campo Obrigatório.</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div id="imagePreview" class="image-container-preview"></div>
                <div id="image-count"></div>
                <!--- MODAL QUE SELECIONA O MOTORISTA --->


                <div class="spinner-overlay d-none" id="loading-api">
                    <div class="spinner-border spinner-big text-light" role="status">
                        <span class="visually-hidden">Carregando...</span>
                    </div>
                </div>


                <div class="row">

                    <div class="col-lg-3">
                        <label>Ativo / Público</label>
                        <select name="isPublic" class="form-control" required>
                            <option value="1" selected>SIM</option>
                            <option value="0">NÂO</option>
                        </select>
                    </div>

                    <div class="col-lg-3">
                        <label>NFT</label>
                        <select name="isNft" class="form-control" required>
                            <option value="1">SIM</option>
                            <option value="0"selected>NÂO</option>
                        </select>
                    </div>

                    <div class="col-lg-2">
                        <label>Valor Termômetro <i class="bi bi-speedometer"></i></label>
                        <input type="number" name="termometro" id="termometro" value="{{ old('termometro') }}"
                            min="0" max="150" value="0" class="form-control">
                    </div>

                    <div class="col-lg-2">
                        <label>Marca :</label>
                        <input name="brand" type="text" value="{{ old('brand') }}" class="form-control" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="mb-3 row mt-2">
                            <label class="col-lg-2 col-md-6 col-sm-12 col-form-label">Nome:</label>
                            <div class="col-lg-10 col-md-6 col-sm-12">
                                <input name="name" type="text" value="{{ old('name') }}" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="mb-3 row mt-2">
                            <label class="col-lg-2 col-md-6 col-sm-12 col-form-label">Preço R$:</label>
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <input name="price" id="precoNormal" value="{{ old('price') }}" type="text"
                                    class="form-control" required>
                                @error('price')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <label class="col-lg-2 col-md-6 col-sm-12 col-form-label">Estoque:</label>
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <input name="stock" type="number" value="{{ old('stock') }}" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">

                        <div class="mb-3 row">
                            <div class="col-lg-3">
                                <label>Preço Promocional:</label>
                                <input name="pricePromotion" type="text" class="form-control">
                            </div>

                            <div class="col-lg-3">
                                <label>GTIN / EAN :</label>
                                <input name="ean" value="{{ old('ean') }}" class="form-control" required>
                            </div>


                            <div class="col-lg-1">
                                <label>Largura: </label>
                                <input name="width" value="{{ old('width') }}" class="form-control" required>
                            </div>


                            <div class="col-lg-1">
                                <label>Altura: </label>
                                <input name="height" value="{{ old('height') }}" class="form-control" required>
                            </div>


                            <div class="col-lg-2">
                                <label>Comprimento: </label>
                                <input name="length" value="{{ old('length') }}" class="form-control" required>
                            </div>

                            <div class="col-lg-3">
                                <label>Tipo de Anúncio :</label>
                                <select name="tipo_anuncio" class="form-control" aria-label=".form-select-sm example"
                                    required>
                                    <option value="gold_special">Clássico</option>
                                    <option value="gold_pro">Premium</option>
                                </select>
                            </div>


                            <div class="col-lg-3">
                                <label>Categoria Mercado Livre:</label>
                                <select class="form-select" id="categorias" aria-label="Default select example" required>
                                    <option selected disabled>Selecionar</option>
                                </select>
                            </div>

                            <input type="hidden" class="form-control" name="id_categoria" id="id_categoria">


                            <div class="col-md-4">
                                <div class="col">
                                    <div class="mb-3 row">
                                        <ol class="list-group list-group-numbered content_categorias">
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">

                            <label for="categoria">Categorias:</label>
                            <select class="form-select mt-2" name="categoria" id="categoria" required
                                aria-label="Default select example">
                                @foreach ($viewData['categorias'] as $categoria)
                                    <option class="bg-warning" disabled>{{ $categoria['nome'] }}</option>
                                    @foreach ($categoria['subcategory'] as $subcategoria)
                                        <option value="{{ $subcategoria->id }}"> - {{ $subcategoria->name }}
                                        </option>
                                    @endforeach
                                @endforeach
                            </select>
                        </div>
                        <div class="col col-lg-4">
                            <label>Fornecedor</label>
                            <select name="fornecedor" class="form-control mt-2" required>
                                @foreach ($viewData['fornecedor'] as $fornecedor)
                                    <option class="bg-warning" value="{{ $fornecedor->id }}">{{ $fornecedor->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>


                        <div class="col-md-12 mt-3">
                            <h3>Taxas</h3>
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
                            <div class="col">
                                <div class="mb-3 row">
                                    <label class="col-lg-2 col-md-6 col-sm-12 col-form-label">Bruto:</label>
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <input name="price" id="precoFinal" value="{{ old('price') }}"
                                            type="text" class="form-control" disabled>
                                    </div>

                                    <label class="col-lg-2 col-md-6 col-sm-12 col-form-label">Liquído: </label>
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <input name="fee" id="precoLiquido" type="text" class="form-control" disabled>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="col">
                                        <div class="mb-3 row">

                                            <label class="col-lg-2 col-md-6 col-sm-12 col-form-label">Taxa %: </label>
                                            <div class="col-lg-3 col-md-6 col-sm-12">
                                                <input name="taxaFee" id="taxaFee" type="text" value="4.99"
                                                    class="form-control" disabled>
                                            </div>

                                            <label class="col-lg-2 col-md-6 col-sm-12 col-form-label">Final: </label>
                                            <div class="col-lg-3 col-md-6 col-sm-12">
                                                <input name="PriceWithFee" id="PriceWithFee" type="text"
                                                    class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col col-lg-2">
                        <div id="inputContainer"></div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Descrição:</label>
                    <textarea required class="form-control" name="description" rows="3">{{ old('description') }}</textarea>
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Cadastrar <i class="bi bi-hdd"></i></button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

    <script>
        $(document).ready(function() {

            var i = 0;

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
                    url: 'http://127.0.0.1:8000/api/v1/fotoPreview', // Rota para o método 'upload' no controlador 'UploadController'
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        $('#imagePreview')
                            .empty(); // Limpa a div antes de adicionar novas imagens
                        $.each(data, function(index, imageUrl) {
                            $('#imagePreview').append(
                                '<div class="image-item position-relative"><img src="' +
                                imageUrl +
                                '"></div>');
                        });
                        if (data.length > 0) {
                            $('#clearImages').show();
                        }
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
                valorProduto = (parseFloat($("#precoFinal").val()) / 0.95);
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
