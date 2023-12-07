@extends('layouts.layout')
@section('conteudo')
    <div class="card py-4">
        {{-- MESSAGE SUCCESS REMOVE ADD CART --}}
        @if (session()->get('message'))
            <div class="alert alert-success" role="alert">
                {{ session()->get('message') }}
            </div>
        @else
            <div class="card-header mt-4">
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
            </div>
        @endif
        {{-- END MESSAGE SUCCESS REMOVE ADD CART --}}
        <form action="{{ route('cart.checkout') }}" method="get">
            <section class="h-100 gradient-custom">
                <div class="container">
                    <div class="row d-flex justify-content-center my-4">
                        <div class="col-md-8">
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
                                                    @if ($product->imageJson)
                                                        <td><img class="img-fluid img-thumbnail" alt=""
                                                                style="width: 100%;"
                                                                src="{{ json_decode($product->imageJson)[0]->url }}"></td>
                                                    @else
                                                        <td><img src="{!! Storage::disk('s3')->url('produtos/' . $product->getId() . '/' . $product->getImage()) !!}" style="width: 100%"
                                                                alt="{{ $product->getName() }}">
                                                    @endif
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
                                                @if ($product->getPricePromotion() > 0)
                                                    <p>Valor Unid R$: {{ $product->getPricePromotion() }}</p>
                                                    <input type="hidden" value="{{ $product->getPricePromotion() }}"
                                                        id="valorUnit">
                                                @else
                                                    <p>Valor Unid R$: {{ $product->getPrice() }}</p>
                                                    <input type="hidden" value="{{ $product->getPrice() }}" id="valorUnit">
                                                @endif

                                                <a href="{{ route('cart.deleteCarrinho', ['id' => $product->getId()]) }}">
                                                    <i class="fas fa-trash"></i>
                                                    </button></a>
                                                <!-- Data -->
                                            </div>

                                            <div class="col-lg-4 col-md-6 mb-4 mb-lg-0" id="divValores">
                                                <!-- Quantity -->
                                                <div class="d-flex mb-4" id="divQuantidade" style="max-width: 300px">
                                                    <button class="btn btn-primary px-3 me-2" id="decrementar"
                                                        onclick="this.parentNode.querySelector('input[type=number]').stepDown()">
                                                        <i class="fas fa-minus"></i>
                                                    </button>

                                                    <div class="form-outline" id="divquantity">
                                                        <input min="1" name="quantity{{ $product->id }}"
                                                            value="{{ session()->get('products')[$product->id] }}"
                                                            id="quantity" value="1" type="number"
                                                            class="form-control" />
                                                        <label class="form-label" for="form1">Quantity</label>
                                                    </div>

                                                    <button class="btn btn-primary px-3 ms-2" id="avancar"
                                                        onclick="this.parentNode.querySelector('input[type=number]').stepUp()">
                                                        <i class="fas fa-plus"></i>
                                                    </button>

                                                    <input type="hidden" name="produto{{ $product->id }}"
                                                        value="{{ $product->id }}">

                                                    @if ($product->getPricePromotion() > 0)
                                                        <input type="hidden" id="valorUnitario"
                                                            value="{{ $product->getPricePromotion() }}">
                                                    @else
                                                        <input type="hidden" id="valorUnitario"
                                                            value="{{ $product->getPrice() }}">
                                                    @endif
                                                </div>
                                                <!-- Quantity -->

                                                <!-- Price -->
                                                @if ($product->getPricePromotion() > 0)
                                                    <input type="text" class="form-control" id="precofinal"
                                                        value="{{ $product->getPricePromotion() * session()->get('products')[$product->id] }}">
                                                @else
                                                    <input type="text" class="form-control" id="precofinal"
                                                        value="{{ $product->getPrice() * session()->get('products')[$product->id] }}">
                                                @endif
                                                <!-- Price -->
                                            </div>
                                        </div>
                                        <!-- Single item -->
                                        <hr class="my-4" />
                                    </div>
                                @endforeach
                            </div>

                            {{-- ACORDION START --}}
                            <div class="accordion accordion-borderless" id="accordionFlushExampleX">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="flush-headingOneX">
                                        <button class="accordion-button" type="button" data-mdb-toggle="collapse"
                                            data-mdb-target="#flush-collapseOneX" aria-expanded="true"
                                            aria-controls="flush-collapseOneX">
                                            Endereço de Entraga
                                        </button>
                                    </h2>
                                    <div id="flush-collapseOneX" class="accordion-collapse collapse show"
                                        aria-labelledby="flush-headingOneX" data-mdb-parent="#accordionFlushExampleX">
                                        <div class="accordion-body">

                                            <div class="table-responsive mt-2">

                                                <div class="row-md-12">
                                                    <a href="{{ route('addEndereco') }}"
                                                        class="btn btn-success btn-sm">Novo Endereço</a>
                                                </div>

                                                <table class="table table-hover mb-0 border mt-4">
                                                    <thead>
                                                        <tr>
                                                            <th>Logradouro</th>
                                                            <th>Bairro</th>
                                                            <th>Endereço</th>
                                                            <th>N°</th>
                                                            <th>Cidade</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($viewData['enderecos'] as $endereco)
                                                            <tr>
                                                                <td><input type="radio" name="endereco"
                                                                        value={{ "$endereco->id" }} required>
                                                                </td>
                                                                <td><a class="navi-link"
                                                                        href="{{ route('editEndereco', ['id' => $endereco->id]) }}"
                                                                        data-toggle="modal">{{ $endereco->address }}</a>
                                                                </td>
                                                                <td>{{ $endereco->bairro }}</td>
                                                                <td>{{ $endereco->numero }}</td>
                                                                <td><span>{{ $endereco->cidade }}</span></td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ACORDION END --}}

                        <div class="col-md-4">
                            <div class="card mb-4">
                                <div class="card-header py-3">
                                    <h5 class="mb-0">Resumo</h5>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group list-group-flush">
                                        <li
                                            class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 pb-0">
                                            Total
                                            <span id="totalProdutos"></span>
                                        </li>
                                    </ul>
                                    <input type="submit" value="Ir para Checkout"
                                        class="btn btn-primary btn-lg btn-block mt-4">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </form>
        {{-- AJAX JQUERY SEARCH --}}

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.css" rel="stylesheet" />
        <link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.theme.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.js"></script>


        <script>
            $(document).ready(function() {

                $("button").click(function(event) {
                    event.preventDefault();
                });

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


            $("input#transportadora").click(function() {
                console.log($("input#valTransp" + $(this).val()).val());
                contaTotalFrete($("#valTransp" + $(this).val()).val());
            });
        </script>
    @endsection
