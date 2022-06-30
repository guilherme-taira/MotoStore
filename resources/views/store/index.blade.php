@extends('layouts.layout')
@section('conteudo')
    <div class="row">
        <main>
            <div class="container-fluid bg-trasparent my-4 p-3" style="position: relative;">
                <div class="row row-cols-1 row-cols-xs-2 row-cols-sm-2 row-cols-lg-4 g-3">

                    @foreach ($viewData['products'] as $product)
                        <div class="col-md-4 mt-2">


                            <div class="card">
                                <div class="card-body">
                                    <div class="card-img-actions">

                                        <img src="{{ Storage::disk('s3')->url('produtos/' . $product->getId() . '/' . $product->getImage()) }}"
                                            class="card-img img-fluid" width="76" height="300" alt="">


                                    </div>
                                </div>

                                <div class="card-body bg-light text-center">
                                    <div class="mb-2">
                                        <h6 class="font-weight-semibold mb-2">
                                            <a href="#" class="text-default mb-2" data-abc="true">{{$product->name}}</a>
                                        </h6>

                                        <a href="#" class="text-muted" data-abc="true">Confeitaria e Doces</a>
                                    </div>

                                    <div class="text-muted"><s>R$: 15.99</s></div>
                                    <h3 class="mb-3 font-weight-semibold precoColor">R$:{{$product->price}}</h3>

                                    <a href="{{ route('products.show', ['id' => $product->getId()]) }}"><button type="button" class="btn bg-warning"><i class="fa fa-cart-plus mr-2"></i> Add to cart</button></a>


                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>
            </div>
        </main>
    </div>
@endsection

