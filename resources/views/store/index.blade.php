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
                                    <img class="card-img img-fluid foto-produto-index" alt="" style="width: 300px"
                                        src="{{ Storage::disk('s3')->url('produtos/' . $product->id . '/' . $product->image) }}">
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
                        {{-- <div class="col-md-4 mt-2">
                            <div class="card">
                                <div class="card-body">
                                    <div class="card-img-actions img-container">
                                        <img src="{{ Storage::disk('s3')->url('produtos/' . $product->id . '/' . $product->image) }}"
                                            class="card-img img-fluid" width="76" height="300" alt="">
                                    </div>
                                </div>

                                @if (!empty($viewData['lancamentos']))
                                    <marquee class="bg-info text-white" behavior="alternate" style="border:solid">NOVO
                                        LANÇAMENTO &nbsp; &nbsp; &nbsp; &nbsp; NOVO LANÇAMENTO &nbsp; &nbsp; &nbsp; &nbsp;
                                        NOVO LANÇAMENTO </marquee>
                                @endif
                                <div class="card-body bg-light text-center">
                                    <p class="titulo-principal text-truncate">{{ $product->title }}</p>
                                    <p class="categoria-up-line"><span class="titulo-categoria">Categoria: </span>Chocolate
                                        e Coberturas</p>
                                    <p class="categoria-up-line"><span class="titulo-categoria">Tipo: </span>Comissão aberta
                                    </p>
                                    @if ($product->pricePromotion <= 0)
                                        <h3 class="mb-3 preco-index-produto">R$
                                            {{ str_replace('.', ',', $product->price) }}</h3>
                                    @else
                                        <h4 class="mb-3 preco-index-produto"> <s class="text-danger">De
                                                {{ str_replace('.', ',', $product->price) }} </s> R$
                                            {{ str_replace('.', ',', $product->pricePromotion) }}</h4>
                                    @endif
                                    <a href="{{ route('products.show', ['id' => $product->id]) }}"><button type="button"
                                            class="btn botao-afiliarse"><span class="texto-afiliase">SAIBA
                                                MAIS</span></button></a>
                                </div>
                            </div> --}}
                    @endforeach
                </div>
            </div>
        </main>
    </div>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
@endsection
