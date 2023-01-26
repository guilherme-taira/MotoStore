@extends('layouts.layout')
@section('title', $viewData['title'])
@section('subtitle', $viewData['subtitle'])
@section('conteudo')
    <div class="card-body" id="displayCart">
        <!-- Button trigger modal -->
        <input type="hidden" id="modalbutton" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Aviso! <i class="bi bi-exclamation-triangle"></i>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Produto Não Tem Estoque Suficiente para a quantidade colocada no carrinho!
                        <i class="bi bi-cart-x"></i>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>

        <form method="POST" id="formulario" action="{{ route('cart.add', ['id' => $viewData['product']->getId()]) }}">
            <div class="row mt-4">
                @csrf
                <div class="row p-2 bg-white border rounded">
                    <!--- FOTOS ADICIONAIS  --->
                    <div class="row-md">
                        @foreach ($viewData['images'] as $foto)
                            <img src="{!! Storage::disk('s3')->url('produtos/' . $viewData['product']->getId() . '/' . $foto) !!}" class="tamanho-fotos fotoProduto" alt="...">
                        @endforeach
                    </div>
                    <!--- FINAL FOTOS ADICIONAIS  --->
                    <div class="col-md-3 mt-1 receivedPhoto"><img
                            class="img-fluid img-responsive rounded product-image tradeFoto" src="{!! Storage::disk('s3')->url('produtos/' . $viewData['product']->getId() . '/' . $viewData['image']) !!}">
                    </div>
                    <div class="col-md-6 mt-1">
                        <h5>{{ $viewData['product']->getName() }}</h5>

                        <!--- Desconto e fixo  --->
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label class="mt-2">KM</label>
                                <div class="progress">
                                    <div class="progress-bar py-2" role="progressbar" style="width: 40%;" aria-valuenow="40"
                                        aria-valuemin="0" aria-valuemax="100" ${6| ,progress-bar-animated}></div>
                                </div>
                            </div>
                        </div>
                        <!--- Final Desconto e fixo  --->

                        <!--- comissao e fixo  --->
                        <div class="row">
                            <div class="col-md-3">
                                <div>Estoque Unid.</div>
                                <input type="text" min="1" id="stock" class="form-control quantity-input"
                                    disabled value="{{ $viewData['product']->getStock() }}">
                            </div>

                            <div class="col-md-3">
                                <div>SKU:</div>
                                <input type="text" min="1" id="sku" class="form-control quantity-input"
                                    disabled value="{{ $viewData['product']->getId() }}">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div>Título Para Integração</div>
                            <input type="text" min="1" maxlength="60" class="form-control quantity-input"
                                id="titulo_integracao" name="titulo_integracao"
                                value="{{ $viewData['product']->getName() }}">
                        </div>

                        <!--- comissao e fixo  --->
                        <div class="row">
                            <div class="col-md-6">
                                <div>acréscimo % </div>
                                <input type="text" min="1"class="form-control quantity-input" id="quantity"
                                    name="quantity" value="0.00">
                            </div>
                            <div class="col-md-6">
                                <div>Fixo R$:</div>
                                <input type="text" min="1"class="form-control quantity-input" id="quantity"
                                    name="quantity" value="0">
                            </div>
                        </div>

                        <!--- final comissao e fixo --->

                        <!--- Desconto e fixo  --->
                        <div class="row">
                            <div class="col-md-6">
                                <div>Desconto % </div>
                                <input type="text" min="1"class="form-control quantity-input" id="quantity"
                                    name="quantity" value="0.00">
                            </div>
                            <div class="col-md-6">
                                <div>Fixo R$:</div>
                                <input type="text" min="1"class="form-control quantity-input" id="quantity"
                                    name="quantity" value="0">
                            </div>
                        </div>
                        <!--- Final Desconto e fixo  --->

                        <!--- Desconto e fixo  --->
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label class="mt-2">Material de Apoio / Dúvidas</label>
                                <button class="btn btn-primary">Material de apoio <i
                                        class="bi bi-archive-fill"></i></button>
                            </div>
                        </div>
                        <!--- Final Desconto e fixo  --->

                        <!--- Botões dos marketplaces  --->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="div mt-4">
                                    <button class="btn btn-warning">Mercado Livre</button>
                                    <button class="btn btn-secondy" disabled>Shopee</button>
                                    <button class="btn btn-sucess" disabled>B2W</button>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="align-items-center align-content-center col-md-3 border-left mt-1">
                        <div class="d-flex flex-row align-items-center">
                            @if ($viewData['product']->getPricePromotion() > 0)
                                <div class="col-md-12">
                                    <p class="text-danger margin-negative"><s>De R$:
                                            {{ $viewData['product']->getPrice() }}</s></p>
                                    <h4 class="text-success">Por R$: {{ $viewData['product']->getPricePromotion() }} </h4>
                                </div>
                            @else
                                <div class="col-md-3">
                                    <p>Valor:</p>
                                </div>
                                <div class="col-md-6">
                                    <h2 class="text-success margin-negative-maior">
                                        R$:{{ $viewData['product']->getPrice() }} </h2>
                                </div>
                            @endif
                        </div>

                        <div class="d-flex flex-column mt-4"><button class="btn bg-primary text-white" id="btn-submit"
                                type="button">Comprar</button><button class="btn btn-outline-primary btn-sm mt-2"
                                type="submit">Adicionar ao Carrinho</button>
                        </div>
                        <div class="col-md-12">
                            <div>Quantidade:</div>
                            <input type="number" min="1" id="quantity" class="form-control quantity-input"
                                value="1">
                        </div>
                    </div>
                    <!--- final botões marketplaces --->
                    <div class="card py-2 mt-4">
                        <div class="card-body">
                            <div class="negrito">Descrição do Produto</div>
                            <p class="text-justify">{{ $viewData['product']->getDescription() }}</p>
                        </div>
                    </div>
                </div>
        </form>
    </div>
    </div>
@endsection

{{-- AJAX JQUERY SEARCH --}}

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.theme.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.js"></script>


<script>
    $(document).ready(function() {

        var quantity = $("#quantity").val();
        var stock = $("#stock").val();

        $("img.fotoProduto").mouseenter(function() {
            $(this).fadeOut(100);
            $(this).fadeIn(500);
            $(this).css({
                border: "2px solid red",
                width: "58px",
                height: "58px"
            });

            var images = $(this).attr('src');
            $('.product-image').attr('src',images);

        }).mouseleave(function() {
            $(this).fadeOut(100);
            $(this).fadeIn(500);
            $(this).css({
                border: "1px solid black",
                width: "58px",
                height: "58px"
            });

        });





        if (parseInt(quantity) > parseInt(stock)) {
            $('#btn-submit').prop("disabled", true);
            $('#modalbutton').trigger('click');
        }

        $('input[id=quantity]').change(function() {

            var quantity = $("#quantity").val();

            if (parseInt(quantity) > parseInt(stock) || parseInt(stock) == '0') {
                $('#btn-submit').prop("disabled", true);
                $('#modalbutton').trigger('click');
                return false;
            } else {
                $('button').prop("disabled", false);
            }
        });
    });
</script>
