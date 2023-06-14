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

    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
        <a href="{{ route('fornecedor.create') }}"><button class="btn btn-success me-md-2" type="button">Novo Usuário / Forncedor <i
                    class="bi bi-patch-plus"></i></button></a>
    </div>

    <!--- MENSAGEM DE CONFIRMAÇÂO DE SUCESSO --->
    @if (session('msg'))
        <div class="alert alert-success" role="alert">
            {{ session('msg') }}
        </div>
    @endif
    <!--- FIM MENSAGEM DE CONFIRMAÇÂO DE SUCESSO --->

    <div class="card mt-2">
        <div class="card-header">
            Fornecedores / Usuários do Site
        </div>

        @if (count($viewData['categorias']) <= 0)
            <div class="alert alert-danger text-center">Não Há Fornecedores cadastrados <a
                    href="{{ route('fornecedores.create') }}"><strong>Cadastre o Primeiro</strong></a></div>
        @else
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col" class="text-secondary">ID</th>
                            <th scope="col" class="text-secondary">Nome</th>
                            <th scope="col" class="text-secondary">Categoria</th>
                            <th scope="col" class="text-secondary">Local</th>
                            <th scope="col" class="text-secondary">Editar</th>
                            <th scope="col" class="text-secondary">Deletar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($viewData['categorias'] as $fornecedor)
                            <tr>
                                <td class="text-secondary">{{ $fornecedor->id }}</td>
                                <td class="text-secondary">{{ $fornecedor->name_fornecedor }}</td>
                                <td class="text-secondary">{{$fornecedor->nome}}</td>
                                <td class="text-secondary">{{$fornecedor->descricao}}</td>
                                <form action="{{route('fornecedores.edit',['fornecedore' => $fornecedor->id_fornecedor])}}" method="GET">
                                    <td><button class="bi bi-pencil btn btn-warning"></button></td>
                                    @csrf
                                </form>

                                <form action="{{route('fornecedores.destroy',['fornecedore' => $fornecedor->id_fornecedor])}}" method="post">
                                @method("DELETE")
                                <td><button class="bi bi-trash3-fill btn btn-danger"></button></td>
                                @csrf
                                </form>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
