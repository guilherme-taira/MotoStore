@extends('layouts.admin')
@section('title', $viewData['title'])
@section('subtitle', $viewData['subtitle'])
@section('conteudo')

    @if ($errors->any())
        <ul class="alert alert-danger list-unstyled">
            @foreach ($errors->all() as $error)
                <li>-{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
        <a href="{{ route('categorias.create') }}"><button class="btn btn-success me-md-2" type="button">Novo Produto <i
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
            Categorias do Site
        </div>

        @if (count($viewData['categorias']) <= 0)
            <div class="alert alert-danger text-center">Não Há Categoria cadastrada <a
                    href="{{ route('categorias.create') }}"><strong>Crie a Primeira</strong></a></div>
        @else
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Nome</th>
                            <th scope="col">Slug</th>
                            <th scope="col">Editar</th>
                            <th scope="col">Deletar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($viewData['categorias'] as $categoria)
                            <tr>
                                <td>{{ $categoria->getId() }}</td>
                                <td>{{ $categoria->getNome() }}</td>
                                <td>{{ $categoria->getSlug() }}</td>
                                <td><a href="{{ route('categorias.edit', ['id' => $categoria->getId()]) }}"><button
                                            class="btn btn-primary btn-sm"><i
                                                class="bi bi-pencil-square"></i>Editar</button> </a></td>
                                <td><a href="{{ route('categorias.edit', ['id' => $categoria->getId()]) }}"><button
                                            class="btn btn-danger btn-sm"><i class="bi bi-trash"></i>Deletar</button> </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
