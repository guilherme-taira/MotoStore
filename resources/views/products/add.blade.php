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

            <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="col">
                    <label class="col-lg-2 col-md-6 col-sm-12 col-form-label">Local do Produto:</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="radio" value="1" id="flexRadioDefault1">
                        <label class="form-check-label" for="flexRadioDefault1">
                            Auto KM
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="radio" value="2" id="flexRadioDefault2">
                        <label class="form-check-label" for="flexRadioDefault2">
                            Kits
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="radio" value="3" id="flexRadioDefault2">
                        <label class="form-check-label" for="flexRadioDefault2">
                            Encapsulados
                        </label>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="mb-3 row">
                            <label class="col-lg-2 col-md-6 col-sm-12 col-form-label">Nome:</label>
                            <div class="col-lg-10 col-md-6 col-sm-12">
                                <input name="name" type="text" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="mb-3 row">
                            <label class="col-lg-2 col-md-6 col-sm-12 col-form-label">Preço R$:</label>
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <input name="price" id="preco" type="text" class="form-control">
                            </div>

                            <label class="col-lg-2 col-md-6 col-sm-12 col-form-label">Estoque:</label>
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <input name="stock"type="number" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="mb-3 row">
                            <div class="col-lg-3">
                                <label class="col-lg-3">Marca :</label>
                                <input name="brand" type="text" class="form-control">
                            </div>

                        </div>
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

                        <div class="col-md-4 p-4">
                            <div class="col">
                                <div class="mb-3 row">
                                    <ol class="list-group list-group-numbered content_categorias">

                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="mb-3 row">
                            <div class="col-lg-3">
                                <label>Preço Promocional:</label>
                                <input name="pricePromotion" id="precopromocional" type="text" class="form-control">
                            </div>

                            <div class="col-lg-3">
                                <label>GTIN / EAN :</label>
                                <input name="ean" class="form-control">
                            </div>

                            <div class="col-lg-3">
                                <label for="categoria">Categorias:</label>
                                <select class="form-select" name="categoria" id="categoria"
                                    aria-label="Default select example">
                                    <option selected>Selecione...</option>
                                    @foreach ($viewData['categorias'] as $categoria)
                                        <option class="bg-warning" disabled>{{ $categoria['nome'] }}</option>
                                        @foreach ($categoria['subcategory'] as $subcategoria)
                                            <option value="{{ $subcategoria->id }}"> - {{ $subcategoria->name }}</option>
                                        @endforeach
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mt-4">
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
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Atualizar <i class="bi bi-hdd"></i></button>
                        </div>
            </form>
        </div>
    </div>
@endsection

<script src="https://code.jquery.com/jquery-3.5.0.js"></script>
<script>
    $(document).ready(function() {

        var valorProduto = 0;
        var i = 0;

        $("#preco").maskMoney({
            prefix: 'R$ ',
            allowNegative: true,
            thousands: '.',
            decimal: ',',
            affixesStay: false
        });
        $("#precopromocional").maskMoney({
            prefix: 'R$ ',
            allowNegative: true,
            thousands: '.',
            decimal: ',',
            affixesStay: false
        });

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
    });
</script>
