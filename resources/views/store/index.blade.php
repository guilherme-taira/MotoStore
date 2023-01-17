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

                {{-- START PRODUTOS --}}
                <div class="row row-cols-1 row-cols-xs-2 row-cols-sm-2 row-cols-lg-4 g-3 mt-4">

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
                                            <a href="#" class="text-default mb-2"
                                                data-abc="true">{{ $product->name }}</a>
                                        </h6>
                                    </div>
                                    <p class="titulo-principal text-truncate">{{ $product->getName() }}</p>
                                    <p class="categoria-up-line"><span class="titulo-categoria">Categoria: </span>Chocolate
                                        e Coberturas</p>
                                    <p class="categoria-up-line"><span class="titulo-categoria">Tipo: </span>Comissão aberta
                                    </p>
                                    @if ($product->getPricePromotion() <= 0)
                                        <h3 class="mb-3 preco-index-produto">R$
                                            {{ str_replace('.', ',', $product->getPrice()) }}</h3>
                                    @else
                                        <h4 class="mb-3 preco-index-produto"> <s class="text-danger">De {{ str_replace('.', ',', $product->getPrice()) }} </s>  R$ {{ str_replace('.', ',', $product->getPricePromotion()) }}</h4>
                                    @endif
                                    <a href="{{ route('products.show', ['id' => $product->getId()]) }}"><button
                                            type="button" class="btn botao-afiliarse"><span class="texto-afiliase">SAIBA
                                                MAIS</span></button></a>
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>
            </div>
        </main>
    </div>
@endsection
