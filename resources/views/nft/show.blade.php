@extends('layouts.nft_product')
@section('title', $viewData['title'])
@section('conteudo')
    <div class="main-content">
        <div class="row">
            <div class="col-lg-6">
                <img src="{!! Storage::disk('s3')->url('produtos/' . $viewData['product']->getId() . '/' . $viewData['image']) !!}" alt="imagem-nft">
            </div>
            <div class="col-lg-6">
                <div class="section-heading">
                    <h2>Integração com Open Sea <img
                            src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/26/OpenSea_icon.svg/1200px-OpenSea_icon.svg.png"
                            style="width: 50px;" alt=""></h2>
                    <h2>{{$viewData['product']->title}}</h2>
                    <div class="line-dec"></div>
                    <p>You are free to use this template for any purpose. You are not allowed to
                        redistribute the
                        downloadable ZIP file of Tale SEO Template on any other template website. Please
                        contact us. Thank
                        you.</p>
                    <div class="add-to-cart mt-4">
                        <a href=""><button class="add-to-cart-btn"><i class="fa fa-shopping-cart"></i>Comprar</button></a>
                    </div>
                </div>

                <p class="more-info">{{$viewData['product']->description}}
                </p>


            </div>
        </div>
    </div>
    </div>
@endsection
