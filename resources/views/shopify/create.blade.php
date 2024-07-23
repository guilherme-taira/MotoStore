@extends('layouts.app')
@section('conteudo')
    <div class="container mt-5">
        <h1 class="mb-4">Configurar Shopify</h1>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($viewData['integrado'])
            <div class="alert alert-success">{{ $viewData['integrado'] }}</div>
        @endif

        <form action="{{ route('shopify.store') }}" method="POST">
            @csrf
            <div class="form-row">

                <div class="form-check form-switch">
                    @if (isset($viewData['shopify']->comunicando) && $viewData['shopify']->comunicando != 0)
                    <input class="form-check-input" name="comunicando" type="checkbox" checked id="flexSwitchCheckDefault">
                    @else
                    <input class="form-check-input" name="comunicando" type="checkbox" id="flexSwitchCheckDefault">
                    @endif

                    <label class="form-check-label" name="comunicando" for="flexSwitchCheckDefault">Comunicar venda</label>
                </div>

                <div class="form-group col-md-6">
                    <label for="apiKey" class="form-label">API Key</label>
                    <input type="text" class="form-control" id="apiKey" name="apiKey"
                        value="{{ isset($viewData['shopify']->apiKey) ? $viewData['shopify']->apiKey : '' }}" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="token" class="form-label">Token</label>
                    <input type="text" class="form-control" id="token" name="token"
                        value="{{ isset($viewData['shopify']->token) ? $viewData['shopify']->token : '' }}" required>
                </div>

                <div class="form-group col-md-6">
                    <label for="name_store" class="form-label">URL LOJA</label>
                    <input type="text" class="form-control" id="name_store" name="name_store"
                        value="{{ isset($viewData['shopify']->name_loja) ? $viewData['shopify']->name_loja : '' }}"
                        required>
                </div>

            </div>
            <button type="submit" class="btn btn-primary mt-3">Salvar</button>
        </form>
    </div>
@endsection
