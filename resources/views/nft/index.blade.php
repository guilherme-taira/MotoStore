@extends('layouts.nft')
@section('title', $viewData['title'])
@section('conteudo')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="owl-features owl-carousel">
                    @foreach ($viewData['products'] as $product)
                        <div class="item">
                            <img src="{{ Storage::disk('s3')->url('produtos/' . $product->id . '/' . $product->image) }}" alt="">
                            <div class="down-content">
                                <h4>{{$product->title}}</h4>
                                <a href="{{ route('nfts.show', ['nft' => $product->id]) }}"><i class="fa fa-link"></i></a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    </div>

    <div class="infos section" id="infos">
        <div class="container">
            <div class="row row-cols-1 row-cols-xs-2 row-cols-sm-2 row-cols-lg-4 g-3 mt-4">
                <!-- product -->
                @foreach ($viewData['products'] as $product)
                    <!-- product -->
                    <div class="products-slick" data-nav="#slick-nav-1">
                        <div class="product">
                            <div class="product-img">
                                @if ($product->imageJson)
                                    <img class="img-fluid img-thumbnail" alt="" style="width: 320px; height: 300px;"
                                        src="{{ json_decode($product->imageJson)[0]->url }}">
                                @else
                                    <img class="img-fluid img-thumbnail" alt="" style="width: 320px; height: 300px;"
                                        src="{{ Storage::disk('s3')->url('produtos/' . $product->id . '/' . $product->image) }}">
                                @endif
                            </div>
                            <div class="product-body">
                                <p class="product-category">Category</p>
                                @if ($product->pricePromotion)
                                    <h3 class="product-name titulo-principal text-truncate decoration-none"><a
                                            href="{{ route('products.show', ['id' => $product->id]) }}">{{ $product->title }}</a>
                                    </h3>
                                    <h4 class="product-price">R$
                                        {{ number_format($product->pricePromotion, 2, ',', '.') }}
                                        <del class="product-old-price">R$
                                            {{ number_format($product->price, 2, ',', '.') }}</del>
                                    </h4>
                                @else
                                    <h3 class="product-name titulo-principal text-truncate decoration-none"><a
                                            href="{{ route('products.show', ['id' => $product->id]) }}">{{ $product->title }}</a>
                                    </h3>
                                    <h4 class="product-price">R$ {{ number_format($product->price, 2, ',', '.') }}</h4>
                                @endif

                            </div>
                            <div class="add-to-cart">
                                <a href="{{ route('nfts.show', ['nft' => $product->id]) }}"><button
                                        class="add-to-cart-btn"><i class="fa fa-shopping-cart"></i>Comprar</button></a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
