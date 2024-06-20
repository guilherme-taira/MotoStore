@extends('layouts.app')
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

    <div class="px-4 mt-4">
        <a href="{{ route('subcategorias.create') }}"><button class="btn btn-success me-md-2" type="button">Nova Sub-Categoria <i
                    class="bi bi-patch-plus"></i></button></a>
    </div>

    <!--- MENSAGEM DE CONFIRMAÇÂO DE SUCESSO --->
    @if (session('msg'))
        <div class="alert alert-success" role="alert">
            {{ session('msg') }}
        </div>
    @endif
    <!--- FIM MENSAGEM DE CONFIRMAÇÂO DE SUCESSO --->

    <div class="card mt-4">
        <div class="card-header">
            Sub-Categorias do Site
        </div>

        @if (count($viewData['subcategoria']) <= 0)
            <div class="alert alert-danger text-center">Não Há Sub-Categoria cadastrada <a
                    href="{{ route('subcategorias.create') }}"><strong>Crie a Primeira</strong></a></div>
        @else
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Nome</th>
                            <th scope="col">Categoria Principal</th>
                            <th scope="col">Slug</th>
                            <th scope="col">Editar</th>
                            <th scope="col">Deletar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($viewData['subcategoria'] as $subcategoria)
                            <tr>
                                <td>{{ $subcategoria->id }}</td>
                                <td>{{ $subcategoria->name }}</td>
                                <td>{{ $subcategoria->id_categoria }}</td>
                                <td>{{ $subcategoria->slug }}</td>
                                <td><a href="{{ route('subcategorias.edit', ['subcategorium' => $subcategoria->id]) }}"><button
                                            class="btn btn-primary btn-sm"><i
                                                class="bi bi-pencil-square"></i>Editar</button> </a></td>
                                <td><a href="{{ route('subcategorias.edit', ['subcategorium' => $subcategoria->id]) }}"><button
                                            class="btn btn-danger btn-sm"><i class="bi bi-trash"></i>Deletar</button> </a>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex">
                {!! $viewData['subcategoria']->links() !!}
            </div>
            <hr>
        @endif
    </div>
@endsection
