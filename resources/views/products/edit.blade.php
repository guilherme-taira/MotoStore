@extends('layouts.admin')
@section('conteudo')
    <div class="card mb-4">
        <div class="card-header">
            Criar Produto
        </div>

        <div class="card-body">
            @if ($errors->any())
                <ul class="alert alert-danger list-unstyled">
                    @foreach ($errors->all() as $error)
                        <li>- {{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            <form method="POST" action="{{ route('products.update', ['id' => $viewData['product']->id]) }}"
                enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <div class="row">
                    <label class="col-lg-2 col-md-6 col-sm-12 col-form-label">Local do Produto:</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="radio" value="1" id="flexRadioDefault1">
                        <label class="form-check-label" for="flexRadioDefault1">
                            Auto KM
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="radio" value="2" id="flexRadioDefault2">
                        <label class="form-check-label" for="flexRadioDefault2">
                            Kits
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="radio" value="3" id="flexRadioDefault2">
                        <label class="form-check-label" for="flexRadioDefault2">
                            Encapsulados
                        </label>
                    </div>

                    <div class="col">
                        <div class="mb-3 row">
                            <label class="col-lg-5 col-md-6 col-sm-12 col-form-label">Imagem Atual:</label>
                            <div class="col-lg-10 col-md-6 col-sm-12">
                                <img src="{!! Storage::disk('s3')->url(
                                    'produtos/' . $viewData['product']->getId() . '/' . $viewData['product']->getImage(),
                                ) !!}" alt="{{ $viewData['product']->getName() }}"
                                    style="width: 200px">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="mb-3 row">
                            <label class="col-lg-2 col-md-6 col-sm-12 col-form-label">Nome:</label>
                            <div class="col-lg-10 col-md-6 col-sm-12">
                                <input name="name" value="{{ $viewData['product']->title }}" type="text"
                                    class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="mb-3 row">
                            <label class="col-lg-2 col-md-6 col-sm-12 col-form-label">Preço R$:</label>
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <input name="price" value="{{ $viewData['product']->price }}" type="text"
                                    class="form-control">
                            </div>

                            <label class="col-lg-2 col-md-6 col-sm-12 col-form-label">Estoque:</label>
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <input name="stock" value="{{ $viewData['product']->available_quantity }}" type="number"
                                    class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="mb-3 row">

                            <label class="col-lg-2 col-md-2 col-sm-2 col-form-label">Categoria Mercado Livre:</label>
                            <div class="col-lg-3 col-md-2 col-sm-2">
                                <input name="categoria_mercadolivre" value="{{ $viewData['product']->category_id }}"
                                    type="text" class="form-control">
                            </div>

                            <label class="col-lg-2 col-md-6 col-sm-12 col-form-label">Marca :</label>
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <input name="brand" value="{{ $viewData['product']->brand }}" type="text"
                                    class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="mb-3 row">
                            <div class="col-lg-3">
                                <label>Preço Promocional:</label>
                                <input name="pricePromotion" value="{{ $viewData['product']->pricePromotion }}"
                                    type="text" class="form-control">
                            </div>

                            <div class="col-lg-3">
                                <label>GTIN / EAN :</label>
                                <input name="ean" value="{{ $viewData['product']->gtin }}" class="form-control">
                            </div>

                            <div class="col-lg-3">
                                <label>Tipo de Anúncio :</label>
                                <select name="tipo_anuncio" class="form-control" aria-label=".form-select-sm example"
                                    required>

                                    @if ($viewData['product']->listing_type_id == 'gold_special')
                                        <option value="gold_special">Clássico</option>
                                        <option value="gold_pro">Premium</option>
                                    @elseif($viewData['product']->listing_type_id == 'gold_pro')
                                        <option value="gold_pro">Premium</option>
                                        <option value="gold_special">Clássico</option>
                                    @else
                                        <option selected>Selecione..</option>
                                        <option value="gold_special">Clássico</option>
                                        <option value="gold_pro">Premium</option>
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col col-lg-4">
                                <label for="categoria">Categorias:</label>
                                <select class="form-select mt-2" name="categoria" id="categoria"
                                    aria-label="Default select example">
                                    <option selected>Selecione...</option>
                                    @foreach ($viewData['categorias'] as $categoria)
                                        <option value="{{ $categoria->id }}">{{ $categoria->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col">
                                <div class="mb-3 row">
                                    <label class="col-lg-2 col-md-6 col-sm-12 col-form-label">Image:</label>
                                    <div class="col-lg-10 col-md-6 col-sm-12">
                                        <input class="form-control" type="file" name="image">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="3">{{ $viewData['product']->description }}</textarea>
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Atualizar <i class="bi bi-hdd"></i></button>
                        </div>
            </form>
        </div>
    </div>
@endsection
