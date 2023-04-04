@extends('layouts.app')
@section('title', $viewData['title'])
@section('subtitle', $viewData['subtitle'])
@section('conteudo')
    <div class="container mt-5">

        @if ($errors->any())
            <ul class="alert alert-danger list-unstyled">
                @foreach ($errors->all() as $error)
                    <li>-{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <!--- MENSAGEM DE CONFIRMAÇÂO DE SUCESSO --->
        @if (session('msg'))
            <div class="alert alert-success" role="alert">
                {{ session('msg') }}
            </div>
        @endif
        <!--- FIM MENSAGEM DE CONFIRMAÇÂO DE SUCESSO --->

        <div class="row">
            <div class="col-lg-4 pb-5">
                <!-- Account Sidebar-->
                <div class="author-card pb-3">
                    <div class="author-card-cover"
                        style="background-image: url({{ Storage::disk('s3')->url('LogoEmbaleme/' .app(App\Models\logo::class)->getLogo()->getId() .'/' .app(App\Models\logo::class)->getLogo()->getImage()) }});">
                        <a class="btn btn-style-1 btn-white btn-sm" href="#" data-toggle="tooltip" title=""
                            data-original-title="You currently have 290 Reward points to spend"><i
                                class="fa fa-award text-md"></i>&nbsp;290 points</a></div>
                    <div class="author-card-profile">
                        <div class="author-card-avatar"><img src="https://bootdey.com/img/Content/avatar/avatar6.png"
                                alt="Daniel Adams">
                        </div>
                        <div class="author-card-details">
                            <h5 class="author-card-name text-lg">{{ $viewData['user']->name }}</h5><span
                                class="author-card-position">Desde {{ $viewData['user']->created_at }}</span>
                        </div>
                    </div>
                </div>
                <div class="wizard">
                    <nav class="list-group list-group-flush">
                        <a class="list-group-item active" href="{{ route('settings') }}"><i
                                class="fe-icon-user text-muted"></i>Perfil</a>
                        <a class="list-group-item" href="{{ route('address') }}"><i
                                class="fe-icon-map-pin text-muted"></i>Endereços</a>
                    </nav>
                </div>
            </div>
            <!-- Profile Settings-->
            <div class="col-lg-8 pb-5">
                <form class="row" action="{{ route('editProfile') }}" method="get">
                    @csrf
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="account-fn">Nome</label>
                            <input class="form-control" type="text" id="account-fn" name="nome"
                                value="{{ $viewData['user']->name }}" required="">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="account-email">E-mail Address</label>
                            <input class="form-control" type="email" id="account-email" name="email"
                                value="{{ $viewData['user']->email }}" disabled="">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="account-phone">Telefone</label>
                            <input class="form-control" type="text" id="account-phone" name="telefone"
                                value="{{ $viewData['user']->phone }}" required="">
                        </div>
                    </div>
                    <br>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="account-pass">Novo Password</label>
                            <input class="form-control" type="password" id="account-pass" name="password">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="account-confirm-pass">Confirma Password</label>
                            <input class="form-control" type="password" id="account-confirm-pass" name="confirm">
                        </div>
                    </div>
                    <div class="col-12">
                        <hr class="mt-2 mb-3">
                        <button type="submit" class="btn btn-success">Atualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


