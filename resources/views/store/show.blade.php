@extends('layouts.layout')
@section('title',$viewData['title'])
@section('subtitle',$viewData['subtitle'])
@section('conteudo')
    <div class="card-body" id="displayCart">
        <form method="POST" action="{{ route('cart.add', ['id' => $viewData['product']->getId()]) }}">
            <div class="row">
                @csrf
                <div class="card" style="width: 18rem;">
                    <img src="{!!Storage::disk('s3')->url('produtos/'.$viewData['product']->getId().'/'.$viewData['image']) !!}" class="card-img-top img-card mt-2" alt="{{ $viewData['product']->getName() }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $viewData['product']->getName() }}
                            (${{ $viewData['product']->getPrice() }})</h5>
                        <p class="card-text">{{ $viewData['product']->getDescription() }}</p>
                        <div class="input-group col-auto">
                            <div class="input-group-text">Quantity</div>
                            <input type="number" min="1" max="10" class="form-control quantity-input" name="quantity" value="1">
                        </div>
                        <div class="input-group col-auto mt-2">
                            <div class="input-group-text">Estoque</div>
                            <input type="text" min="1" class="form-control quantity-input" disabled value="{{ $viewData['stock']}}">
                        </div>

                        <div class="col-auto mt-2">
                            <button class="btn bg-primary text-white" type="submit">Add to cart</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        </p>
    @endsection
