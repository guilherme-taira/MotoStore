@extends('layouts.cartLayoutSingleProduct')
@section('title', $viewData['title'])
@section('subtitle', $viewData['subtitle'])
@section('conteudo-cart')
            <form method="POST" action="{{ route('cart.add', ['id' => $viewData['product']->getId()]) }}">
                @csrf
                <div class="card justify-content-start">
                    <div class="card-body">
                        <h5 class="card-title">{{ $viewData['product']->getName() }}
                            (${{ $viewData['product']->getPrice() }})</h5>
                        <div class="input-group col-auto">
                            <div class="input-group-text">Quantity</div>
                            <input type="number" min="1" max="10" class="form-control quantity-input" id="quantity"
                                name="quantity" value="1">
                        </div>
                        <div class="col-auto mt-2">
                            <button class="btn bg-primary text-white" type="submit">Add to cart</button>
                        </div>
                    </div>
                </div>
            </form>
    @endsection
