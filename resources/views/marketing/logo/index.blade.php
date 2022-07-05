@extends('layouts.admin')
@section('title', $viewData['title'])
@section('conteudo')
    <div class="container">
        <h1>{{$viewData['subtitle']}}</h1>

        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <a href="{{ route('logos.create') }}"><button class="btn btn-success me-md-2" type="button">Nova Logo <i
                        class="bi bi-patch-plus"></i></button></a>
        </div>

        @if (count($viewData['logos']) <= 0)
        <div class="alert alert-danger text-center">Não Há Logo Marca Cadastrado <a
                href="{{ route('logos.create') }}"><strong>Cadastre-se Agora!</strong></a></div>
        @else
        @foreach ($viewData['logos'] as $logo)
            <div class="card mb-3" style="max-width: 540px;">
                <div class="row g-0">
                    <div class="col-md-4">
                        <img src="{{ Storage::disk('s3')->url('LogoEmbaleme/' . $logo->getId() . '/' . $logo->getImage()) }}" class="img-fluid rounded-start" alt="...">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title">{{$logo->name}}</h5>
                            <p class="card-text"><small class="text-muted">Criado em : {{$logo->updated_at}}</small></p>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        @endif

    </div>
@endsection
