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
            <div class="row">
                @csrf
                <div class="row p-2 bg-white border rounded">
                    <div class="col-md-3 mt-1"><img class="img-fluid img-responsive rounded product-image"
                            src="{!! Storage::disk('s3')->url('produtos/' . $viewData['product']->getId() . '/' . $viewData['image']) !!}"></div>
                    <div class="col-md-6 mt-1">
                        <h5>{{ $viewData['product']->getName() }}</h5>

                        <p class="text-justify text-truncate para mb-0"> {{ $viewData['product']->getDescription() }}<br><br></p>

                        <div class="col-md-6">
                            <div>Quantidade</div>
                            <input type="number" min="1"class="form-control quantity-input"
                                id="quantity" name="quantity" value="1">
                        </div>
                        <div class="col-md-6">
                            <div>Estoque</div>
                            <input type="text" min="1" id="stock" class="form-control quantity-input" disabled
                                value="{{ $viewData['stock'] }}">
                        </div>

                    </div>
                    <div class="align-items-center align-content-center col-md-3 border-left mt-1">
                        <div class="d-flex flex-row align-items-center">
                            <h4 class="mr-1">{{ $viewData['product']->getPrice() }} </h4><span class="strike-text">R$:
                                {{ $viewData['product']->getPrice() }}</span>
                        </div>
                        <h6 class="text-success">Free shipping</h6>
                        <div class="d-flex flex-column mt-4"><button class="btn bg-primary text-white btn-sm"
                                id="btn-submit"  type="button">Details</button><button
                                class="btn btn-outline-primary btn-sm mt-2" type="submit">Adicionar ao Carrinho</button></div>
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
            console.log(quantity);
            console.log(stock);
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
