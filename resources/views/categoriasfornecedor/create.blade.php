@extends('layouts.app')
@section('title', $viewData['title'])
@section('conteudo')
    @if ($errors->any())
        <ul class="alert alert-danger list-unstyled">
            @foreach ($errors->all() as $error)
                <li>-{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <!--- MENSAGEM DE CONFIRMAÇÂO DE SUCESSO --->
    @if (session('msg'))
        <div class="alert alert-success" role="alert">
            {{ session('msg') }}
        </div>
    @endif
    <!--- FIM MENSAGEM DE CONFIRMAÇÂO DE SUCESSO --->

    <div class="card mt-2">
        <div class="card-header">
            Cadastro Fornecedores / Usuários do Site
        </div>
        <div class="card-body">
            <form action="{{ route('subcategoriafornecedor.store') }}" method="POST">
                <div class="row g-3 align-items-center">

                    <div class="col-auto">
                        <label for="exampleInputEmail1" class="form-label">Nome Categoria Ex: SP1 / SP1</label>
                        <input type="text" class="form-control" name="name" aria-describedby="emailHelp">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label" for="flexSwitchCheckDefault">Categorias: </label>
                        <select class="form-select" name="categoria" required aria-label="Default select example">
                            <option value="">Selecione..</option>
                            @foreach ($viewData['categorias'] as $fornecedor)
                                <option value="{{ $fornecedor->id }}">{{ $fornecedor->name }} -
                                    {{ $fornecedor->descricao }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-auto">
                        <label for="exampleInputEmail1" class="form-label">Região</label>
                        <input type="text" class="form-control" name="regiao" aria-describedby="emailHelp">
                    </div>
                </div>

        <button type="submit" class="btn btn-primary mt-4">Cadastrar</button>
        </form>
    </div>

    </div>
@endsection
