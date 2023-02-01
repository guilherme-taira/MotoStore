@extends('layouts.app')
@section('title', $viewData['title'])
@section('conteudo')

    @if ($errors->any())
        <ul class="alert alert-danger list-unstyled">
            @foreach ($errors->all() as $error)
                <li>-> {{ $error }}</li>
            @endforeach
        </ul>
    @endif

    @if (!empty($msg_success))
        <ul class="alert alert-success list-unstyled">
            <li>{{ $msg_success }}</li>
        </ul>
    @endif

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

                            <div id="flush-collapseOne" class="accordion-collapse collapse"
                                aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">
                                    <form method="POST" action="{{ route('IntegrarProduto') }}"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="col-md-12">
                                            <input type="hidden" name="id_product" id="id_product">
                                            <div class="row">
                                                <div class="col">
                                                    <div class="mb-3 row">
                                                        <label
                                                            class="col-lg-2 col-md-6 col-sm-12 col-form-label">Nome:</label>
                                                        <div class="col-lg-10 col-md-6 col-sm-12">
                                                            <input name="name" id="name" type="text"
                                                                class="form-control">
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
                                                            <input id="acressimoR" type="text"
                                                                class="form-control porcem">
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
                                                            <input id="descontoP" type="text"
                                                                class="form-control porcem">
                                                        </div>
                                                        <label class="col-lg-2 col-md-6 col-sm-12 col-form-label">R$</label>
                                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                                            <input id="descontoR" type="text"
                                                                class="form-control porcem">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <p class="col-lg-4 col-md-6 col-sm-12 col-form-label">Tipo de Anúncio</p>
                                                <div class="col">
                                                    <div class="mb-3 row">
                                                        <select name="tipo_anuncio" class="form-control"
                                                            aria-label=".form-select-sm example" required>
                                                            <option value="gold_special">Clássico</option>
                                                            <option value="gold_pro">Premium</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="col">
                                                    <div class="mb-3 row">
                                                        <label
                                                            class="col-lg-2 col-md-6 col-sm-12 col-form-label">Preço:</label>
                                                        <div class="col-lg-3 col-md-6 col-sm-12">
                                                            <input name="price" id="precoFinal" type="text"
                                                                class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="col">
                                                    <div class="mb-3 row">
                                                        <label
                                                            class="col-lg-2 col-md-6 col-sm-12 col-form-label">Categorias:</label>
                                                        <select class="form-select" id="categorias"
                                                            aria-label="Default select example">
                                                            <option selected>...</option>
                                                        </select>
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
                                        </div>

                                        <input type="hidden" class="form-control" name="id_categoria"
                                            id="id_categoria">

                                        <button type="submit" class="btn btn-success mt-4"
                                            data-bs-target="#exampleModalToggle2" data-bs-toggle="modal"
                                            data-bs-dismiss="modal">Finalizar Integração</button>
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
    <div class="modal fade" id="exampleModalToggle2" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2"
        tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalToggleLabel2">Resumo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <span class="text-success font-weight-bold"> INTEGRADO COM SUCESSO!</span> <br> Código do Produto
                    "MLB23123544"
                    <hr> Preço Final: R$ 59,88 <br> Modalidade Clássico
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" data-bs-target="#exampleModalToggle" data-bs-toggle="modal"
                        data-bs-dismiss="modal">Back to first</button>
                </div>
            </div>
        </div>
    </div>
    <!--- FINAL DO MODAL ---->

    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
        <a href="{{ route('products.create') }}"><button class="btn btn-success me-md-2" type="button">Novo Produto <i
                    class="bi bi-patch-plus"></i></button></a>
    </div>

    <div class="card mt-2">
        <div class="card-header">
            Manage Products
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Imagem</th>
                        <th scope="col">Integrações</th>
                        <th scope="col">Preço</th>
                        <th scope="col">Estoque</th>
                        <th scope="col">Edit</th>
                        <th scope="col">Delete</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($viewData['products'] as $product)
                        <tr id="linhasProduct">
                            <td class="id_product">{{ $product->getId() }}</td>
                            <td>{{ $product->getName() }}</td>
                            <td><img src="{!! Storage::disk('s3')->url('produtos/' . $product->getId() . '/' . $product->getImage()) !!}" style="width: 10%" alt="{{ $product->getName() }}">
                            </td>
                            <td><i class="bi bi-arrow-left-right integracao"
                                    style="font-size: 2rem; color: cornflowerblue;margin-left:20px;display:block;"
                                    data-bs-toggle="modal" href="#exampleModalToggle" role="button"></i>
                            </td>
                            <td>{{ $product->getPrice() }}</td>
                            <td>{{ $product->getStock() }}</td>
                            <td><a href="{{ route('products.edit', ['id' => $product->getId()]) }}"><button
                                        class="btn btn-primary btn-sm"><i class="bi bi-pencil-square"></i>Editar</button>
                                </a></td>
                            <td><a href="{{ route('products.edit', ['id' => $product->getId()]) }}"><button
                                        class="btn btn-danger btn-sm"><i class="bi bi-trash"></i>Deletar</button> </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <input type="text" name="id_user" id="id_user" value="{{ Auth::user()->id }}">
    <input type="hidden" name="total" id="total">
@endsection

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.theme.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.3/jquery.mask.min.js"></script>

<script>
    var valorProduto = 0;
    var i = 0;
    $(document).ready(function() {

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

        // MASCARA DE PORCENTAGEM
        $('.porcem').mask('Z9999.999', {
            translation: {
                'Z': {
                    pattern: /[\-\+]/,
                    optional: true
                }
            }
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
