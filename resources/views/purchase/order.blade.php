@extends('layouts.layout')
@section('title', $viewData['title'])
@section('subtitle', $viewData['subtitle'])
@section('conteudo')
    <div class="card">
        <div class="card-header">
            Venda Finalizada
        </div>
        <div class="card-body">
            <div class="alert alert-success" role="alert">
                Parabéns pela compra, o número do seu pedido é : <b>#{{ $viewData['order']->getId() }}</b>
            </div>
        </div>
    </div>
@endsection
