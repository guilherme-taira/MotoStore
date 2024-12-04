@extends('layouts.app')

@section('conteudo')
<div class="container">
    <h1>Editar Integração Bling</h1>
    <form action="{{ route('bling.update', $integracaoBling) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="access_token">Access Token</label>
            <input type="text" name="access_token" id="access_token" class="form-control" value="{{ $integracaoBling->access_token }}" required>
        </div>
        <div class="form-group">
            <label for="client_id">Client ID</label>
            <input type="text" name="client_id" id="client_id" class="form-control" value="{{ $integracaoBling->client_id }}" required>
        </div>
        <div class="form-group">
            <label for="client_secret">Client Secret</label>
            <input type="text" name="client_secret" id="client_secret" class="form-control" value="{{ $integracaoBling->client_secret }}" required>
        </div>

        <div class="form-group">
            <label for="link">Link</label>
            <input type="text" name="link" id="link" class="form-control" value="{{ $integracaoBling->link }}" placeholder="Insira o Link (opcional)">
        </div>

        <button type="submit" class="btn btn-success mt-4">Atualizar</button>
    </form>
</div>
@endsection
