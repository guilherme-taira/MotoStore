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
            <form action="{{ route('fornecedores.update', ['fornecedore' => $viewData['id']]) }}" method="POST">
                @method('PUT')
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Email: </label>
                    <input type="email" class="form-control"name="email" value="{{ $viewData['fornecedor']->email }}"
                        id="exampleInputEmail1" aria-describedby="emailHelp">
                </div>
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Nome</label>
                    <input type="text" class="form-control" name="nome"
                        value="{{ $viewData['fornecedor']->name_fornecedor }}" aria-describedby="emailHelp">
                </div>

                <div class="row g-6 align-items-center">

                    <div class="col-md-4">
                        <label for="categoria">Categorias:</label>
                        <select class="form-select" name="categoria" id="categoria" aria-label="Default select example" required>
                            <option value="" selected>Selecione...</option>
                            @foreach ($viewData['subcategorias'] as $categoria)
                                <option class="bg-primary text-white" disabled>{{ $categoria['nome'] }}</option>
                                @foreach ($categoria['subcategory'] as $subcategoria)
                                    <option value="{{ $subcategoria->id }}"> - {{ $subcategoria->name }}</option>
                                @endforeach
                            @endforeach
                        </select>
                    </div>

                    <div class="col-auto">
                        <label class="form-label" for="flexSwitchCheckDefault">Para ser Tornar Fornecedor</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" name="isFornecedor" type="checkbox" id="flexSwitchCheckDefault">
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" id="exampleInputPassword1">
                </div>
                <button type="submit" class="btn btn-primary">Atualizar</button>
            </form>
        </div>

    </div>
@endsection
