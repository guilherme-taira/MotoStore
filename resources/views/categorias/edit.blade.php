@extends('layouts.app')
@section('title', $viewData['title'])
@section('subtitle', $viewData['subtitle'])
@section('conteudo')

    <div class="container">
        <div class="card mt-2">
            <div class="card-header">
                Categorias do Site
            </div>
            <div class="card-body">
                <form class="row g-3 needs-validation" action="{{ route('categorias.update', ['id' => $id]) }}" method="POST" novalidate>
                    @method('PUT')
                    @csrf
                    <div class="col-md-4">
                        <label for="validationCustom01" class="form-label">Nome:</label>
                        <input type="text" class="form-control" name="nome" value="{{ $viewData['categoria']->nome }}" id="validationCustom01" required>
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="validationCustom02" class="form-label">Slug: chocolate-confeiro</label>
                        <input type="text" class="form-control" value="{{ $viewData['categoria']->slug }}" name="slug" id="validationCustom02" required>
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="validationTextarea" class="form-label">Descrição:</label>
                        <textarea class="form-control is-invalid" name="descricao" id="validationTextarea" required>{{ $viewData['categoria']->descricao }}</textarea>
                        <div class="invalid-feedback">
                            Digite uma descrição da categoria.
                        </div>
                    </div>

                    <div class="col-12">
                        <button class="btn btn-primary" type="submit">Atualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
