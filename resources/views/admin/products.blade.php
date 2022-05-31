@extends('layouts.admin')
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
                        <th scope="col">Edit</th>
                        <th scope="col">Delete</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($viewData['products'] as $product)
                        <tr>
                            <td>{{ $product->getId() }}</td>
                            <td><img src="{{ asset('/storage/'.$product->getImage())}}" style="width: 50px" alt="{{ $product->getImage() }}"></td>
                            <td>{{ $product->getName() }}</td>
                            <td>Edit</td>
                            <td>Delete</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
