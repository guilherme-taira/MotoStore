@extends('layouts.app')
@section('title', $viewData['title'])
@section('subtitle', $viewData['subtitle'])
@section('conteudo')

<div class="container">
    <form class="row g-3 needs-validation" action="{{route('payment.store')}}" method="POST">
        @csrf
        <div class="col-md-4">
            <label for="validationCustom01" class="form-label">Nome</label>
            <input type="text" class="form-control" id="validationCustom01" name="name" placeholder="cartão de credito.." required>
        </div>

        <div class="col-12">
            <button class="btn btn-primary" type="submit">Cadastrar</button>
        </div>
    </form>
</div>
@endsection
