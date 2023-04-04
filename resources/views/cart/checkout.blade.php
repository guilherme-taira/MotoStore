@extends('layouts.layout')
@section('conteudo')
    <div class="card py-4">
        {{-- MESSAGE SUCCESS REMOVE ADD CART --}}
        @if (session()->get('message'))
            <div class="alert alert-success" role="alert">
                {{ session()->get('message') }}
            </div>
        @else
            {{-- <div class="card-header mt-4">
                Products in Cart
                <div class="container">
                    <div class="row">
                        <div class="col">
                            <div class="col-8">
                                <input type="text" class="form-control" id="search" placeholder="Pesquisar..">
                                <select class="form-select d-none" multiple id="result">
                                </select>
                                <p id="final"></p>
                            </div>
                        </div>
                        <div class="col">
                            <div class="container" id="ShowQuantiti"></div>
                        </div>
                    </div>
                </div>
            </div> --}}
        @endif
        {{-- END MESSAGE SUCCESS REMOVE ADD CART --}}
        <form action="{{ route('cart.purchase') }}" method="get" id="myForm">
            <section class="h-100 gradient-custom">
                <div class="container">
                    <div class="row d-flex justify-content-center my-4">
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header py-3">
                                    <h5 class="mb-0">Cart - {{ count($viewData['products']) }} item(s)</h5>
                                </div>
                                @foreach ($viewData['products'] as $product)
                                    <div class="card-body">
                                        <!-- Single item -->
                                        <div class="row">
                                            <div class="col-lg-3 col-md-12 mb-4 mb-lg-0">
                                                <!-- Image -->
                                                <div class="bg-image hover-overlay hover-zoom ripple rounded"
                                                    data-mdb-ripple-color="light">
                                                    <img src="{!! Storage::disk('s3')->url('produtos/' . $product->getId() . '/' . $product->getImage()) !!}" class="w-50"
                                                        alt="{{ $product->getName() }}"></td>
                                                    <a href="#!">
                                                        <div class="mask"
                                                            style="background-color: rgba(251, 251, 251, 0.2)">
                                                        </div>
                                                    </a>
                                                </div>
                                                <!-- Image -->
                                            </div>

                                            <div class="col-lg-5 col-md-6 mb-4 mb-lg-0">
                                                <!-- Data -->
                                                <p><strong>{{ $product->getName() }}</strong></p>
                                                <p>Valor Unid R$: {{ $product->getPrice() }}</p>
                                                <input type="hidden" value="{{ $product->getPrice() }}" id="valorUnit">
                                                <!-- Data -->
                                            </div>

                                            <div class="col-lg-4 col-md-6 mb-4 mb-lg-0" id="divValores">
                                                <!-- Quantity -->
                                                <div class="d-flex mb-4" id="divQuantidade" style="max-width: 300px">

                                                    <div class="form-outline" id="divquantity">
                                                        <input min="1" name="quantity{{ $product->id }}"
                                                            value="{{ session()->get('products')[$product->id] }}"
                                                            id="quantity" type="number" disabled class="form-control" />
                                                        <label class="form-label" for="form1">Quantidade</label>
                                                    </div>

                                                    <input type="hidden" name="produto{{ $product->id }}"
                                                        value="{{ $product->id }}">
                                                    <input type="hidden" id="valorUnitario"
                                                        value="{{ $product->getPrice() }}">
                                                </div>
                                                <!-- Quantity -->
                                                <!-- Price -->
                                                <input type="hidden" class="form-control" id="precofinal"
                                                    value="{{ $product->getPrice() * session()->get('products')[$product->id] }}">
                                                <!-- Price -->
                                            </div>
                                        </div>
                                        <!-- Single item -->
                                        <hr />
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Resumo</h5>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group list-group-flush">
                                        <li
                                            class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 pb-0">
                                            Products
                                            <span id="totalProdutos"></span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                            Frete
                                            <span>Selecione</span>
                                        </li>
                                        <ul class="list-group">
                                            @foreach ($viewData['transportadora'] as $frete)
                                                {{-- TEMPLATE  --}}
                                                <label for="{{ $frete['id_tranportadora'] }}">
                                                    <li class="list-group-item" id="card-selected">
                                                        <input type="hidden" value="{{ $frete['preco'] }}"
                                                            id="valTransp{{ $frete['id_tranportadora'] }}">

                                                        <div class="card-body">
                                                            <img class="float-end" src="{{ $frete['foto'] }}"
                                                                width="128px">
                                                            <h5 class="card-title">R$ {{ $frete['preco'] }}
                                                                {{ $frete['nome'] }}</h5>
                                                            <p class="card-text">Tempo Estimado: De
                                                                {{ $frete['dias']->min }} à
                                                                {{ $frete['dias']->max }} Dia(s) <input type="radio"
                                                                    name="transportadora"
                                                                    id="{{ $frete['id_tranportadora'] }}"
                                                                    value="{{ $frete['id_tranportadora'] }}" required></p>
                                                        </div>
                                                    </li>
                                                </label>
                                            @endforeach

                                        </ul>
                                        <li
                                            class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 mb-3">
                                            <div>
                                                <strong>Valor Total</strong>
                                                <strong>
                                                    <p class="mb-0">(Incluso Frete)</p>
                                                </strong>
                                            </div>
                                            <input type="hidden" name="pegaTotal" id="pegaTotal">
                                            <hr>
                                            <span><strong id="totalProdutosFrete"></strong></span>
                                        </li>
                                    </ul>
                                    <div class="cho-container"></div>
                                    <input type="submit" value="Finalizar Compra" class="btn btn-primary btn-lg btn-block">
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="text" name="" id="pref" value="{{$viewData['pref']}}">
                    <input type="text" name="" id="external" value="{{$viewData['external_reference']}}">

                </div>
            </section>
        </form>
        {{-- AJAX JQUERY SEARCH --}}

        <script src="https://sdk.mercadopago.com/js/v2"></script>
        <script>
            const mp = new MercadoPago('APP_USR-4f55dc1d-3b2f-4f41-96bb-578b28ad37ad', {
                locale: 'pt-BR'
            });

            mp.checkout({
                preference: {
                    id: $("#pref").val(),
                },
                render: {
                    container: '.cho-container',
                }
            });
        </script>


        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.css" rel="stylesheet" />
        <link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.theme.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.js"></script>


        <script>
            $(document).ready(function() {

                $("button").click(function(event) {
                    event.preventDefault();
                });


                // $(".cho-container").hide();

                var totalProdutosCarrinho = 0;
                // DECREMENTAR .EACH PARA SABER QUAL É O BOTÃO QUE VAI DECREMENTAR
                // FAZER ESSA IMPLEMENTAÇÃO.

                var quantidade = 0;
                var valorunitario = 0;
                var precofinal = 0;
                var total = 0;

                $("div#divValores").click(function() {
                    quantidade = $(this).children("#divQuantidade").children("#divquantity")
                        .children(
                            "#quantity").val();

                    valorunitario = $(this).children("#divQuantidade").children("#valorUnitario").val();
                    precofinal = $(this).children("#precofinal");

                    total = calcular(quantidade, valorunitario);
                    precofinal.empty().val(total.toFixed(2));

                    $("button#decrementar").click(function(e) {
                        total = calcularMenos(total, valorunitario);
                        precofinal.empty().append(total.toFixed(2));

                        totalProdutosCarrinho = 0;
                        // PEGA O VALOR TOTAL DOS PRODUTOS
                        $("input#precofinal").each(function(num, element) {
                            totalProdutosCarrinho = parseFloat(
                                    totalProdutosCarrinho) +
                                parseFloat($(element).val());
                        });

                        var carrinhovalor = 0;
                        carrinhovalor = parseFloat(totalProdutosCarrinho) - valorunitario;
                        $("#pegaTotal").val(carrinhovalor.toFixed(2));
                    });

                    $("button#avancar").click(function(e) {
                        e.preventdefault();
                        total = calcular(quantidade, valorunitario);
                        precofinal.empty().append("Total: " + total.toFixed(2));
                        totalProdutosCarrinho = 0;
                        // PEGA O VALOR TOTAL DOS PRODUTOS
                        $("input#precofinal").each(function(num, element) {
                            totalProdutosCarrinho = parseFloat(
                                    totalProdutosCarrinho) +
                                parseFloat($(element).val());
                        });

                        var carrinhovalor = 0;
                        carrinhovalor = parseFloat(totalProdutosCarrinho) - parseFloat(
                            valorunitario);
                        $("#pegaTotal").val(carrinhovalor.toFixed(2));
                    });
                });
            });

            setInterval(() => {
                contaTotal();
            }, 100);

            function calcularMenos(total, valor) {
                if (parseFloat(total) <= 0) {
                    return 0;
                } else {
                    return parseFloat(valor) - parseFloat(total);
                }
            }

            function contaTotal() {
                var totalProdutosCarrinho = 0;
                $("input#precofinal").each(function(num, element) {
                    totalProdutosCarrinho = parseFloat(totalProdutosCarrinho) + parseFloat($(
                        element).val());
                });
                $("#totalProdutos").empty().append("R$: " + totalProdutosCarrinho.toFixed(2));
            }

            function contaTotalFrete(frete) {
                var totalProdutosCarrinho = 0;
                $("input#precofinal").each(function(num, element) {
                    totalProdutosCarrinho = parseFloat(totalProdutosCarrinho) + parseFloat($(
                        element).val());
                });

                total = parseFloat(totalProdutosCarrinho) + parseFloat(frete);
                $("#pegaTotal").val(total.toFixed(2));
                $("#totalProdutosFrete").empty().append("Total: " + total.toFixed(2));
            }

            function calcular(quantidade, valor) {
                if (parseFloat(quantidade) <= 0) {
                    return parseFloat(valor) * 1;
                } else {
                    return parseFloat(valor) * parseFloat(quantidade);
                }
            }

            $('#myForm input').on('change', function() {
                $('input[name=transportadora]:checked', '#myForm').val();

                $(".cho-container").show();
                contaTotalFrete($("#valTransp" + $(this).val()).val());
            });

            // $("input#transportadora").click(function() {
            //     console.log($("input#valTransp" + $(this).val()).val());
            //     contaTotalFrete($("#valTransp" + $(this).val()).val());
            // });
        </script>
    @endsection
