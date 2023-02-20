@extends('layouts.app')
@section('title', $viewData['subtitle'])
@section('subtitle', $viewData['subtitle'])
@section('conteudo')
    <div class="container">

        @if ($errors->any())
            <ul class="alert alert-danger list-unstyled">
                @foreach ($errors->all() as $error)
                    <li>-{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <h1>Novos Banner AutoKM</h1>
        <form action="{{ route('bannersAutokm.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Nome da Fase:</label>
                <input type="text" class="form-control" name="name" id="exampleFormControlInput1"
                    placeholder="Digite o nome do banner">
            </div>
            <div class="row">
                <div class="col">
                    <div class="mb-3 row">
                        <label class="col-lg-2 col-md-6 col-sm-12 col-form-label">Imagem do Banner:</label>
                        <div class="col-lg-10 col-md-6 col-sm-12">
                            <input class="form-control" type="file" name="image">
                        </div>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <input type="submit" value="Salvar" class="btn btn-primary">
            </div>
        </form>
    </div>
@endsection
