@extends('layouts.admin')
@section('title', $viewData['title'])
@section('subtitle', $viewData['subtitle'])
@section('conteudo')
    <div class="container">
        <div class="card mt-2">
            <div class="card-header">
                Vendas
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Cliente</th>
                            <th scope="col">Valor</th>
                            <th scope="col">Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($viewData['orders'] as $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td><a href={{ route('orders.show',['id' => $order->id]) }}>{{ $order->name}}</a></td>
                                <td>R$: {{$order->total}}</td>
                                <td>{{$order->created_at}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
