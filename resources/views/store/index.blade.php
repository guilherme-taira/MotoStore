@extends('layouts.layout')
@section('conteudo')
    <div class="row">
        <main>
            <div class="container-fluid bg-trasparent my-4 p-3" style="position: relative;">
                <div class="row row-cols-1 row-cols-xs-2 row-cols-sm-2 row-cols-lg-4 g-3">

                    @foreach ($viewData['products'] as $product)
                        <div class="col">
                            <div class="card h-100 shadow-sm"> <img
                                    src="{{ Storage::disk('s3')->url('produtos/' . $product->getId() . '/' . $product->getImage()) }}"
                                    class="card-img-top" alt="...">
                                <div class="card-body">

                                    @if ($product->getStock() == 0 && $product->getPricePromotion() == 0)
                                        {{-- PREÇO --}}
                                        <div class="clearfix mb-3"> <span
                                                class="float-start badge rounded-pill bg-primary">ASUS
                                                Rog</span> <span class="float-end price-hp">SEM ESTOQUE</span> </div>
                                    @elseif($product->getPricePromotion() > 0)
                                        {{-- PREÇO --}}
                                        <div class="clearfix mb-3"> <span class="badge rounded-pill bg-warning"><s> R$:
                                                    {{ $product->getPrice() }}</s></span> <span
                                                class="float-end price-hp">

                                                <h4 class="text-success">R$: {{ $product->getPricePromotion() }}</h4>
                                                <a href="{{ route('products.show', ['id' => $product->getId()]) }}"
                                                    class="btn bg-primary text-white">{{ $product->getName() }}</a>
                                            </span> </div>
                                    @else
                                        <h4 class="text-success">R$: {{ $product->getPrice() }}</h4>
                                        <a href="{{ route('products.show', ['id' => $product->getId()]) }}"
                                            class="btn bg-primary text-white">{{ $product->getName() }}</a>
                                    @endif
                                    <h5 class="card-title mt-2">Lorem, ipsum dolor sit amet consectetur adipisicing elit.
                                        Veniam
                                        quidem eaque ut eveniet aut quis rerum.</h5>
                                    <div class="text-center my-4"> <a
                                            href="{{ route('products.show', ['id' => $product->getId()]) }}"
                                            class="btn btn-warning">Comprar</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </main>
    </div>
@endsection
