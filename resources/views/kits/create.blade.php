@extends('layouts.app')
@section('conteudo')
    {{-- {{ Auth::user()->name }} --}}
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
                            <!-- SELECT DO MOTORISTA -->
                            <label class="form-label" for="form8Example1">Categoria</label>
                            <select class="form-select" name="categoria" required class="form-control" required>
                                <option selected value="">Selecione...</option>
                                @foreach ($viewData['categorias'] as $categoria)
                                    <option value="{{ $categoria->id }}">{{ $categoria->nome }}</option>
                                @endforeach
                            </select>
                            <!--- FIM --->
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

    <div class="card mb-4">
        <div class="card-header">
            Gerador de Kits
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
                <div class="alert alert-success" role="alert">
                    {{ $msg }}
                </div>
            @endif

            <form action="{{ route('setSessionRoute') }}" id="setSession" method="get">
                <div class="row">
                    <div class="col">
                        <div class="mb-3 row">
                            <label class="col-lg-2 col-md-6 col-sm-12 col-form-label">SKU / CÓDIGO:</label>
                            <div class="col-lg-10 col-md-6 col-sm-12">
                                <input name="id" id="search" type="text" class="form-control">
                                <select class="form-select d-none" multiple id="result"> </select>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="id" class="form-control" id="id">
                    <input type="hidden" id="name" name="name" class="form-control">

                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="d-grid gap-2 d-none" id="btnFinalizar">
                            <button class="btn btn-success" type="submit">Inserir no Kit <i
                                    class="bi bi-signpost-2"></i></button>
                        </div>
                    </div>
            </form>

            <div class="col-12">
                <ol>
                    @if ($produtos)
                        @foreach ($produtos as $produto)
                            @if (isset($produto['id']) != '')
                                <span class="d-none">{{ $total += $produto['price'] }}</span>
                                <li class="p-3 mb-2 kit-margin text-dark text-decoration-none produto">
                                    <img src="{!! Storage::disk('s3')->url('produtos/' . $produto['id'] . '/' . $produto['imagem']) !!}" alt="{{ $produto['nome'] }}" style="width: 80px">
                                    <label class="col-lg-2 col-md-6 col-sm-12 col-form-label">
                                        {{ isset($produto['nome']) ? $produto['nome'] : '' }} Estoque:
                                        <form
                                            action="{{ route('adicionarQuantidade', ['id' => isset($produto['id']) ? $produto['id'] : 0]) }}"
                                            method="get">

                                            <div class="col-md-4"> <input name="stock" id="stock"
                                                    value="{{ isset($produto['quantidade']) ? $produto['quantidade'] : 1 }}"
                                                    type="number" class="form-control">
                                                {{-- botao adicionar quantidade --}}
                                                <button id="btnSalvarQuantidade"
                                                    class="mt-2 btn btn-success btn-sm">Adicionar</button>
                                        </form>
            </div>
            </label>

            <a href="{{ route('deleteSessionRoute', ['id' => isset($produto['id']) ? $produto['id'] : 0]) }}"
                class="btn btn-danger float-end">Tirar do Kit <i class="bi bi-dash-circle-dotted"></i></a>
            </li>
            @endif
            @endforeach
            <input type="text" class="d-none" id="valorTotalInput" value="{{ $total }}">
            @endif
            </ol>
        </div>

        <div class="mb-3" id="submitado">
            <div class="spinner-border text-success d-none" id="carregando">
                <span class="visually-hidden">Loading...</span>
            </div>

            <div class="mb-3 row">
                <label class="col-form-label"><b>Total R$: </b></label>
                <div class="col-lg-3 col-md-2 col-sm-12">
                    <input name="price" id="total" type="text" value="{{ $total }}"
                        class="form-control">
                </div>

                <div class="mb-3 col-lg-2 col-md-3 col-sm-6 row">
                    <button type="button" class="btn btn-primary" id="modalbutton" data-bs-toggle="modal"
                        data-bs-target="#exampleModal">Cadastrar Kit <i class="bi bi-pin-map"></i></button>
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

        $("form").submit(function(event) {
            // event.preventDefault();
            $("#BtnCadastrar").addClass('d-none');
            $('#carregando').removeClass('d-none');
        });

        // VALOR TOTAL
        var total = $('#total').val();
        var totalCalculado = total;

        $('#precoFinal').val(parseFloat(total).toFixed(2));

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
                                .title + ' | Estoque: ' + item.available_quantity + '</option>';
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
