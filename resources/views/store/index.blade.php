@extends('layouts.layout')
@section('conteudo')
    <div class="row">
        <main>
            <div class="container-fluid bg-trasparent my-4 p-3" style="position: relative;">
                {{-- DIV BANNER --}}

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
                                    @if ($product->imageJson)
                                        <img class="img-fluid img-thumbnail" alt=""
                                            style="width: 300px; height: 300px;"
                                            src="{{ json_decode($product->imageJson)[0]->url }}">
                                    @else
                                        <img class="img-fluid img-thumbnail" alt=""
                                            style="width: 300px; height: 300px;"
                                            src="{{ Storage::disk('s3')->url('produtos/' . $product->id . '/' . $product->image) }}">
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

                                    @if ($product->termometro < 100)
                                        <div class="progress" role="progressbar" aria-label="Success striped example"
                                            aria-valuenow="{{ $product->termometro }}" aria-valuemin="0"
                                            aria-valuemax="150">
                                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-danger"
                                                style="width:{{ $product->termometro / 2 }}%"></div>
                                        </div>
                                    @else
                                        <div class="progress" role="progressbar" aria-label="Success striped example"
                                            aria-valuenow="{{ $product->termometro }}" aria-valuemin="0"
                                            aria-valuemax="150">
                                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-danger"
                                                style="width:{{ $product->termometro - 50 }}%"></div>
                                        </div>
                                    @endif
                                </div>

                                <div class="add-to-cart">
                                    <a href="{{ route('products.show', ['id' => $product->id]) }}"><button
                                            class="add-to-cart-btn"><i class="fa fa-shopping-cart"></i>Saiba
                                            Mais</button></a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="d-flex py-2">
                        {!! $viewData['products']->links() !!}
                    </div>
                </div>
            </div>

            <!--- MODAL QUE SELECIONA O MOTORISTA --->
            <div class="modal fade modal-fullscreen" id="exampleModalLong" aria-hidden="true" aria-labelledby="exampleModalLongTitle"
                tabindex="-1">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalToggleLabel">Ops, Esse Produto Não é do seu Fornecedor!</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <h2 class="fs-5">Como Resolver</h2>
                            <p>Faça a troca de Fornecedor através do link. para acessar novas categorias assine o plano: <span class="badge text-bg-info"><a href="#" class="text-white" data-bs-toggle="tooltip" title="Tooltip">Plano Premium.</a></span></p>
                            <img src="{{ asset('storage/images/imagem_product_fail.jpg') }}" width="250px">
                            <hr>
                            <h2 class="fs-5">Já Tenho o Plano Premium Assinado.</h2>
                            <p>Caso você já tenha o plano Premium entre em contato com nosso time para ajudarmos você.  <button class="btn btn-success"> Contato <i class="bi bi-whatsapp"></i></button></p>
                          </div>
                    </div>
                </div>
            </div>
            <!--- FINAL DO MODAL ---->

        </main>
    </div>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
@endsection
