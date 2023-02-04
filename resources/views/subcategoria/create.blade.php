@extends('layouts.admin')
@section('title', $viewData['title'])
@section('subtitle', $viewData['subtitle'])
@section('conteudo')
    <div class="container">
        <div class="card mt-2">
            <div class="card-header">
                Sub-Categorias do Site
            </div>
            <div class="card-body">

                @if ($errors->any())
                    <ul class="alert alert-danger list-unstyled">
                        @foreach ($errors->all() as $error)
                            <li>- {{ $error }}</li>
                        @endforeach
                    </ul>
                @endif

                <form class="row g-3 needs-validation" action="{{ route('subcategorias.store') }}" method="POST" novalidate>
                    @csrf
                    <div class="col-md-4">
                        <label for="validationCustom01" class="form-label">Nome:</label>
                        <input type="text" class="form-control" name="nome" id="validationCustom01" required>
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="validationCustom02" class="form-label">Slug: chocolate-confeiro</label>
                        <input type="text" class="form-control" name="slug" id="validationCustom02" required>
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="validationTextarea" class="form-label">Descrição:</label>
                        <textarea class="form-control is-invalid" name="descricao" id="validationTextarea"
                            placeholder="Digite uma descrição da categoria." required></textarea>
                        <div class="invalid-feedback">
                            Digite uma descrição da categoria.
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col col-lg-4">
                            <label for="categoria">Categorias Principais:</label>
                            <select class="form-select mt-2" name="categoria" id="categoria"
                                aria-label="Default select example">
                                <option selected>Selecione...</option>
                                @foreach ($viewData['categorias'] as $categoria)
                                    <option value="{{ $categoria->id }}">{{ $categoria->nome }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-12">
                        <button class="btn btn-primary" type="submit">Cadastrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
