@extends('layouts.app')
@section('title', $viewData['title'])
@section('conteudo')



<div class="container-fluid px-4">
    <h2 class="mt-4">Produtos Integrados</h2>

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


    <div class="card mt-2">
        <div class="card-header">
            Produtos
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Imagem</th>
                        <th scope="col">Integrações</th>
                        <th scope="col">ID LOJA</th>
                        <th scope="col">Criado</th>
                        <th scope="col">Valor Agregado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($viewData['products'] as $product)
                        <tr id="linhasProduct">
                            <td class="id_product">{{ $product->id }}</td>
                            <td>{{ $product->name }}</td>
                            <td><img src="{!! Storage::disk('s3')->url('produtos/' . $product->product_id . '/' . $product->image) !!}" style="width: 10%" alt="{{ $product->image }}">
                            </td>
                            <td><span class="badge bg-success">{{ $product->id_mercadolivre }}</span></td>
                            <td>{{ $product->product_id }}</td>
                            <td>{{ $product->created_at }}</td>
                            <td>{{ $product->priceNotFee}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex py-2">
                {!! $viewData['products']->links() !!}
            </div>
        </div>
    </div>

</div>

@endsection

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.theme.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.3/jquery.mask.min.js"></script>
