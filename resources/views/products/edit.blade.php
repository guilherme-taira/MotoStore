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
                    <div class="col">
                        <div class="mb-3 row">
                            <label class="col-lg-2 col-md-6 col-sm-12 col-form-label">Nome:</label>
                            <div class="col-lg-10 col-md-6 col-sm-12">
                                <input name="name" value="{{ $viewData['product']->name }}" type="text"
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
                                <input name="stock" value="{{ $viewData['product']->stock }}" type="number"
                                    class="form-control">
                            </div>
                        </div>
                    </div>



                    <div class="row">
                        <div class="col">
                            <label class="col-lg-6 col-md-6 col-sm-12 col-form-label">Preço Promocional:</label>
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <input name="pricePromotion" value="{{ $viewData['product']->pricePromotion }}"
                                    type="text" class="form-control">
                            </div>
                        </div>

                        <div class="col">
                            <div class="mb-3 row">
                                <label class="col-lg-5 col-md-6 col-sm-12 col-form-label">Imagem Atual:</label>
                                <div class="col-lg-10 col-md-6 col-sm-12">
                                    <img src="{!! Storage::disk('s3')->url('produtos/' . $viewData['product']->getId() . '/' . $viewData['product']->getImage()) !!}" alt="{{ $viewData['product']->getName() }}"
                                        style="width: 200px">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
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
