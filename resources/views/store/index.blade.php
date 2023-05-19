@extends('layouts.layout')
@section('conteudo')
    <div class="row">
        <main>
            <div class="container-fluid bg-trasparent my-4 p-3" style="position: relative;">
                {{-- DIV BANNER --}}
                <header>
                    <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-indicators">
                            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0"
                                class="active" aria-current="true" aria-label="Slide 1"></button>
                            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1"
                                aria-label="Slide 2"></button>
                            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2"
                                aria-label="Slide 3"></button>
                        </div>
                        @if (isset($viewData['bannersFix']))
                            <div class="carousel-inner">
                                <div class="carousel-item active"
                                    style="background-image: url('{{ Storage::disk('s3')->url('bannersEmbaleme/' . $viewData['bannersFix']->getId() . '/' . $viewData['bannersFix']->getImage()) }}')">
                                </div>

                                @foreach ($viewData['banners'] as $banner)
                                    <div class="carousel-item"
                                        style="background-image: url('{{ Storage::disk('s3')->url('bannersEmbaleme/' . $banner->getId() . '/' . $banner->getImage()) }}')">
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </header>
                {{-- END DIV BANNER --}}

                @if (isset($viewData['subtitle']))
                    <h2>{{ $viewData['subtitle'] }}</h2>
                @endif

                {{-- START PRODUTOS --}}
                <div class="row row-cols-1 row-cols-xs-2 row-cols-sm-2 row-cols-lg-4 g-3 mt-4">
                    @foreach ($viewData['products'] as $product)
                        <!-- product -->
                        <div class="products-slick" data-nav="#slick-nav-1">
                            <div class="product">
                                <div class="product-img">
                                    @if($product->imageJson)
                                        <img class="img-fluid img-thumbnail" alt="" style="width: 300px; height: 300px;" src="{{json_decode($product->imageJson)[0]->url}}">
                                    @else
                                        <img class="img-fluid img-thumbnail" alt="" style="width: 300px; height: 300px;" src="{{ Storage::disk('s3')->url('produtos/' . $product->id . '/' . $product->image) }}">
                                    @endif

                                    <div class="product-label">
                                        <span class="sale">-30%</span>
                                        <span class="new">NEW</span>
                                    </div>
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
                                    <div>
                                        <h6>{{ $product->termometro }} KM <i class="bi bi-speedometer"></i></h6>
                                    </div>

                                    @if($product->termometro < 100)
                                    <div class="progress" role="progressbar" aria-label="Success striped example"
                                        aria-valuenow="{{$product->termometro}}" aria-valuemin="0" aria-valuemax="150">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-danger" style="width:{{$product->termometro / 2}}%"></div>
                                    </div>
                                    @else
                                    <div class="progress" role="progressbar" aria-label="Success striped example"
                                        aria-valuenow="{{$product->termometro}}" aria-valuemin="0" aria-valuemax="150">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-danger" style="width:{{$product->termometro - 50}}%"></div>
                                    </div>
                                    @endif
                                </div>
                                <div class="add-to-cart">
                                    <a href="{{ route('products.show', ['id' => $product->id]) }}"><button
                                            class="add-to-cart-btn"><i class="fa fa-shopping-cart"></i>Comprar</button></a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="d-flex py-2">
                        {!! $viewData['products']->links() !!}
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
@endsection
