@extends('layouts.app')
@section('conteudo')
<div class="container">
    <h1>Adicionar Integração Bling</h1>
    <form action="{{ route('bling.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="access_token">Access Token</label>
            <input type="text" name="access_token" id="access_token" class="form-control" value="{{ old('access_token') }}" placeholder="Insira o Access Token" required>
        </div>

        <div class="form-group">
            <label for="client_id">Client ID</label>
            <input type="text" name="client_id" id="client_id" class="form-control" value="{{ old('client_id') }}" placeholder="Insira o Client ID" required>
        </div>

        <div class="form-group">
            <label for="client_secret">Client Secret</label>
            <input type="text" name="client_secret" id="client_secret" class="form-control" value="{{ old('client_secret') }}" placeholder="Insira o Client Secret" required>
        </div>

        <div class="form-group">
            <label for="link">Link</label>
            <input type="text" name="link" id="link" class="form-control" value="{{ old('link') }}" placeholder="Insira o Link (opcional)">
        </div>

        <div class="form-group">
            <label for="isIntegrado">Está Integrado?</label>
            <select name="isIntegrado" id="isIntegrado" class="form-control">
                <option value="1" {{ old('isIntegrado') == '1' ? 'selected' : '' }}>Sim</option>
                <option value="0" {{ old('isIntegrado', '0') == '0' ? 'selected' : '' }}>Não</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success mt-3">Salvar</button>
    </form>
</div>
@endsection
