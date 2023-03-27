@extends('layouts.layout')
@section('conteudo')
    <div class="card py-4">

        {{-- MESSAGE SUCCESS REMOVE ADD CART --}}
        @if (session()->get('message'))
            <div class="alert alert-success" role="alert">
                {{ session()->get('message') }}
            </div>
        @else
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header py-3">
                        <h5 class="mb-0">Resumo</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 pb-0">
                                Products
                                <span id="totalProdutos"></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                Frete
                                <span>Selecione</span>
                            </li>
                            <ul class="list-group">
                                @foreach ($viewData['transportadora'] as $frete)
                                    <li class="list-group-item"> <input type="radio" name="transportadora"
                                            id="transportadora" value="{{ $frete['id_tranportadora'] }}" required>
                                        <input type="hidden" value="{{ $frete['preco'] }}"
                                            id="valTransp{{ $frete['id_tranportadora'] }}">

                                        {{ $frete['nome'] }} Preço R$:
                                        <span id="valorTransportadora"> {{ $frete['preco'] }} </span><img class="float-end"
                                            src="{{ $frete['foto'] }}" width="128px">
                                        <hr><span>Tempo Estimado: De {{ $frete['dias']->min }} à
                                            {{ $frete['dias']->max }} Dia(s)</span>
                                    </li>
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

                        <button type="submit" class="btn btn-primary btn-lg btn-block">
                            Finalizar Compra
                        </button>
                    </div>
                </div>


                {{-- AJAX JQUERY SEARCH --}}

                <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
                <link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.css" rel="stylesheet" />
                <link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.theme.css" rel="stylesheet" />
                <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.js"></script>


                <script>
                    $(document).ready(function() {

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

                            $("button#decrementar").click(function() {
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

                            $("button#avancar").click(function() {
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
