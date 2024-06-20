@extends('layouts.app')
@section('title', $viewData['title'])
@section('conteudo')


    <div class="container-fluid px-4">

        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
            <a href="{{ route('products.create') }}"><button class="btn btn-success me-md-2" type="button">Novo Produto <i
                        class="bi bi-patch-plus"></i></button></a>
        </div>

        <div class="card mt-2">


            @if ($errors->any())
                <ul class="alert alert-danger list-unstyled">
                    @foreach ($errors->all() as $error)
                        <li>-> {{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            @if (session('msg'))
                <div class="alert alert-success" role="alert">
                    {{ session('msg') }}
                    {{ session()->forget('msg') }}
                </div>
            @endif

            @if (session('msg_error'))
                <div class="alert alert-danger" role="alert">
                    {{ session('msg_error') }}
                    {{ session()->forget('msg_error') }}
                </div>
            @endif

            <div class="card-header">
                Manage Products
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Name</th>
                            <th scope="col">Imagem</th>
                            <th scope="col">Preço</th>
                            <th scope="col">Estoque</th>
                            <th scope="col">Ativo</th>
                            <th scope="col">Visualizações</th>
                            <th scope="col">Edit</th>
                            <th scope="col">Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($viewData['products'] as $product)
                            <tr id="linhasProduct">
                                <td class="id_product">{{ $product->getId() }}</td>
                                <td>{{ $product->getName() }}</td>
                                <td><img src="{!! Storage::disk('s3')->url('produtos/' . $product->getId() . '/' . $product->getImage()) !!}" style="width: 10%" alt="{{ $product->getName() }}">
                                </td>
                                <td>{{ $product->getPrice() }}</td>
                                <td>{{ $product->getStock() }}</td>
                                @if ($product->isPublic == 1)
                                    <td><i class="bi bi-check2-square text-success"></i></td>
                                @else
                                    <td><i class="bi bi-slash-circle text-danger"></i></td>
                                @endif
                                <td>{{$product->acessos}}</td>
                                <td><a href="{{ route('products.edit', ['id' => $product->getId()]) }}"><button
                                            class="btn btn-primary btn-sm"><i
                                                class="bi bi-pencil-square"></i>Editar</button>
                                    </a></td>
                                <td><a href="{{ route('products.edit', ['id' => $product->getId()]) }}"><button
                                            class="btn btn-danger btn-sm"><i class="bi bi-trash"></i>Deletar</button> </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="d-flex">
                    {!! $viewData['products']->links() !!}
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="id_user" id="id_user" value="{{ Auth::user()->id }}">
    <input type="hidden" name="total" id="total">
@endsection
