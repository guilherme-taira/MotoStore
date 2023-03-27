@extends('layouts.app')
@section('title', $viewData['title'])
@section('conteudo')

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

    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
        <a href="{{ route('products.create') }}"><button class="btn btn-success me-md-2" type="button">Novo Produto <i
                    class="bi bi-patch-plus"></i></button></a>
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
                        <th scope="col">Name</th>
                        <th scope="col">Imagem</th>
                        <th scope="col">Integrações</th>
                        <th scope="col">Preço</th>
                        <th scope="col">Estoque</th>
                        <th scope="col">Ativo</th>
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
                            <td><i class="bi bi-arrow-left-right integracao"
                                    style="font-size: 2rem; color: cornflowerblue;margin-left:20px;display:block;"
                                    data-bs-toggle="modal" href="#exampleModalToggle" role="button"></i>
                            </td>
                            <td>{{ $product->getPrice() }}</td>
                            <td>{{ $product->getStock() }}</td>
                            @if ($product->isPublic == 1)
                                <td><i class="bi bi-check2-square text-success"></i></td>
                            @else
                                <td><i class="bi bi-slash-circle text-danger"></i></td>
                            @endif
                            <td>{{ $product->getStock() }}</td>
                            <td><a href="{{ route('products.edit', ['id' => $product->getId()]) }}"><button
                                        class="btn btn-primary btn-sm"><i class="bi bi-pencil-square"></i>Editar</button>
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
    <input type="hidden" name="id_user" id="id_user" value="{{ Auth::user()->id }}">
    <input type="hidden" name="total" id="total">
@endsection
