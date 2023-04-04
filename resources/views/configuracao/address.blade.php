@extends('layouts.app')
@section('title', $viewData['title'])
@section('subtitle', $viewData['subtitle'])
@section('conteudo')
    <div class="container mt-5">
        <div class="row">
            <div class="col-lg-4 pb-5">
                <!-- Account Sidebar-->
                <div class="author-card pb-3">
                    <div class="author-card-cover"
                        style="background-image: url({{ Storage::disk('s3')->url('LogoEmbaleme/' .app(App\Models\logo::class)->getLogo()->getId() .'/' .app(App\Models\logo::class)->getLogo()->getImage()) }});">
                        <a class="btn btn-style-1 btn-white btn-sm" href="#" data-toggle="tooltip" title=""
                            data-original-title="You currently have 290 Reward points to spend"><i
                                class="fa fa-award text-md"></i>&nbsp;290 points</a>
                    </div>
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
                        <a class="list-group-item" href="{{ route('settings') }}"><i
                                class="fe-icon-user text-muted"></i>Perfil</a>
                        <a class="list-group-item active" href="{{ route('address') }}"><i
                                class="fe-icon-map-pin text-muted"></i>Endereços</a>
                    </nav>
                </div>
            </div>
            <!-- Profile Settings-->
            <div class="col-lg-8 pb-5">

                <div class="row-md-12">
                    <a href="{{ route('addEndereco') }}"><button type="button" class="btn btn-success btn-sm"> + Novo
                            Endereço</button></a>
                </div>

                <div class="table-responsive mt-2">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Logradouro</th>
                                <th>Bairro</th>
                                <th>N°</th>
                                <th>Cidade</th>
                                <th>Deletar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($viewData['enderecos'] as $endereco)
                                <tr>
                                    <td><a class="navi-link" href="{{ route('editEndereco', ['id' => $endereco->id]) }}"
                                            data-toggle="modal">{{ $endereco->address }}</a></td>
                                    <td>{{ $endereco->bairro }}</td>
                                    <td>{{ $endereco->numero }}</td>
                                    <td><span>{{ $endereco->cidade }}</span></td>
                                    <td>
                                        <form action="{{route('deletarEndereco',['id' => $endereco->id])}}" method="post">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" style="border: none;"><i class="bi bi-trash text-dark btn btn-danger"></i></button>
                                        </form>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endsection
