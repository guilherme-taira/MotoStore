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
            <form action="{{route('fornecedores.store')}}" method="POST">
                <div class="mb-3">
                  <label for="exampleInputEmail1" class="form-label">Email: </label>
                  <input type="email" class="form-control"name="email" id="exampleInputEmail1" aria-describedby="emailHelp">
                </div>
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Nome</label>
                    <input type="text" class="form-control" name="nome" aria-describedby="emailHelp">
                </div>

                <div class="row g-3 align-items-center">

                    <div class="col-lg-3">
                        <label for="categoria">Categorias:</label>
                        <select class="form-select" name="categoria" id="categoria"
                            aria-label="Default select example">
                            <option selected>Selecione...</option>
                            @foreach ($viewData['subcategorias'] as $categoria)
                                <option class="bg-primary text-white" disabled>{{ $categoria['nome'] }}</option>
                                @foreach ($categoria['subcategory'] as $subcategoria)
                                    <option value="{{ $categoria['id'] }}"> - {{ $subcategoria->name }} - {{ $subcategoria->descricao }}</option>
                                @endforeach
                            @endforeach
                        </select>
                    </div>


                    {{-- <div class="col-auto">
                        <label for="exampleInputEmail1" class="form-label">Nome Categoria Ex: SP1 / SP1</label>
                        <input type="text" class="form-control" name="categoria" aria-describedby="emailHelp">
                    </div>
                    <div class="col-auto">
                        <label for="exampleInputEmail1" class="form-label">Slug</label>
                        <input type="text" class="form-control" name="slug" aria-describedby="emailHelp">
                    </div>
                    <div class="col-auto">
                        <label for="exampleInputEmail1" class="form-label">Região</label>
                        <input type="text" class="form-control" name="regiao" aria-describedby="emailHelp">
                    </div> --}}
                  </div>

                <div class="mb-3">
                  <label for="exampleInputPassword1" class="form-label">Password</label>
                  <input type="password" class="form-control" name="password" id="exampleInputPassword1">
                </div>
                <button type="submit" class="btn btn-primary">Cadastrar</button>
              </form>
        </div>

    </div>
@endsection
