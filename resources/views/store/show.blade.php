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
                <div class="card" style="width: 18rem;">
                    <img src="{!! Storage::disk('s3')->url('produtos/' . $viewData['product']->getId() . '/' . $viewData['image']) !!}" class="card-img-top img-card mt-2"
                        alt="{{ $viewData['product']->getName() }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $viewData['product']->getName() }}
                            (${{ $viewData['product']->getPrice() }})</h5>
                        <p class="card-text">{{ $viewData['product']->getDescription() }}</p>
                        <div class="input-group col-auto">
                            <div class="input-group-text">Quantity</div>
                            <input type="number" min="1" max="10" class="form-control quantity-input" id="quantity"
                                name="quantity" value="1">
                        </div>
                        <div class="input-group col-auto mt-2">
                            <div class="input-group-text">Estoque</div>
                            <input type="text" min="1" id="stock" class="form-control quantity-input" disabled
                                value="{{ $viewData['stock'] }}">
                        </div>

                        <div class="col-auto mt-2">
                            <button class="btn bg-primary text-white" id="btn-submit" type="submit">Add to cart</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        </p>
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

            if(parseInt(stock) === 0){
                $('#btn-submit').prop("disabled", true);
            }

            $('input[id=quantity]').change(function() {

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
