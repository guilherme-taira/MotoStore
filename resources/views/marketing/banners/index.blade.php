@extends('layouts.app')
@section('title', $viewData['title'])
@section('subtitle', $viewData['subtitle'])
@section('conteudo')
    <div class="container">
        <h1>Banner Site</h1>

        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <a href="{{ route('banner.create') }}"><button class="btn btn-success me-md-2" type="button">Novo Banner <i
                        class="bi bi-patch-plus"></i></button></a>
        </div>

        @if (count($viewData['banners']) <= 0)
            <div class="alert alert-danger text-center">Não Há Banners Cadastrado <a
                    href="{{ route('banner.create') }}"><strong>Criar o Primeiro</strong></a></div>
        @else
            @foreach ($viewData['banners'] as $banner)
                <div class="card mb-3" style="max-width: 540px;">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <img src="{{ Storage::disk('s3')->url('bannersEmbaleme/' . $banner->getId() . '/' . $banner->getImage()) }}"
                                class="img-fluid rounded-start" alt="...">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title">{{ $banner->name }}</h5>
                                <p class="card-text"><small class="text-muted">Criado em : {{ $banner->updated_at }}</small>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif


        <h1>Banner Auto Km</h1>

        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <a href="{{ route('banner.create') }}"><button class="btn btn-success me-md-2" type="button">Novo Banner <i
                        class="bi bi-patch-plus"></i></button></a>
        </div>

        @if (count($viewData['banners_autokm']) <= 0)
            <div class="alert alert-danger text-center">Não Há Banners Cadastrado <a
                    href="{{ route('bannersAutokm.create') }}"><strong>Criar o Primeiro</strong></a></div>
        @else
            @foreach ($viewData['banners_autokm'] as $banner)
                <div class="card mb-3" style="max-width: 540px;">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <img src="{{ Storage::disk('s3')->url('bannersAutokm/' . $banner->getId() . '/' . $banner->getImage()) }}"
                                class="img-fluid rounded-start" alt="...">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title">{{ $banner->name }}</h5>
                                <p class="card-text"><small class="text-muted">Criado em : {{ $banner->updated_at }}</small>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif

        <h1>Banner Premium</h1>

        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <a href="{{ route('bannersPremium.create') }}"><button class="btn btn-success me-md-2" type="button">Novo Banner <i
                        class="bi bi-patch-plus"></i></button></a>
        </div>

        @if (count($viewData['banners_premium']) <= 0)
            <div class="alert alert-danger text-center">Não Há Banners Cadastrado <a
                    href="{{ route('bannersPremium.create') }}"><strong>Criar o Primeiro</strong></a>
            </div>
        @else
            @foreach ($viewData['banners_premium'] as $banner)
                <div class="card mb-3" style="max-width: 540px;">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <img src="{{ Storage::disk('s3')->url('bannersPremium/' . $banner->getId() . '/' . $banner->getImage()) }}"
                                class="img-fluid rounded-start" alt="...">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title">{{ $banner->name }}</h5>
                                <p class="card-text"><small class="text-muted">Criado em :
                                        {{ $banner->updated_at }}</small></p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif

    </div>
@endsection
