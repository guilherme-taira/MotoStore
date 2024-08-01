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

                <hr>
                <div class="alert alert-warning d-flex align-items-center mt-4" role="alert">

                    <svg xmlns="http://www.w3.org/2000/svg" class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" width = "16px" viewBox="0 0 16 16" role="img" aria-label="Warning:">
                      <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                    </svg>
                    <div>
                         Atenção: Os campos default só serão preenchido em caso de não ter esses dados dentro do pedido!
                    </div>
                  </div>

                <div class="form-group col-md-6">
                    <label for="apiKey" class="form-label">Email - Default</label>
                    <input type="text" class="form-control" id="email" name="email"
                        value="{{ isset($viewData['shopify']->email) ? $viewData['shopify']->email : '' }}" required>
                </div>

                <div class="form-group col-md-6 mt-2">
                    <label for="apiKey" class="form-label">Telefone - Default</label>
                    <input type="text" class="form-control" id="telefone" name="telefone"
                        value="{{ isset($viewData['shopify']->telefone) ? $viewData['shopify']->telefone : '' }}" required>
                </div>

            </div>
            <button type="submit" class="btn btn-primary mt-3">Salvar</button>
        </form>
    </div>
@endsection
