@extends('layouts.app')
@section('title', $viewData['title'])
@section('conteudo')

    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
        <a href="{{route('products.create')}}"><button class="btn btn-success me-md-2" type="button">Novo Produto <i class="bi bi-patch-plus"></i></button></a>
    </div>

    <div class="card mt-2">
        <div class="card-header">
            Manage Products
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Imagem</th>
                        <th scope="col">Name</th>
                        <th scope="col">Estoque</th>
                        <th scope="col">Edit</th>
                        <th scope="col">Delete</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($viewData['products'] as $product)
                        <tr>
                            <td>{{ $product->getId() }}</td>

                            <td><img src="{!!Storage::disk('s3')->url('produtos/'.$product->getId().'/'.$product->getImage()) !!}" style="width: 10%" alt="{{ $product->getName() }}"></td>
                            <td>{{ $product->getName() }}</td>
                            <td>{{ $product->getStock() }}</td>
                            <td><a href="{{route('products.edit',['id' => $product->getId()])}}"><button class="btn btn-primary btn-sm"><i class="bi bi-pencil-square"></i>Editar</button> </a></td>
                            <td><a href="{{route('products.edit',['id' => $product->getId()])}}"><button class="btn btn-danger btn-sm"><i class="bi bi-trash"></i>Deletar</button> </a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
