@extends('layouts.app')
@section('conteudo')
<div class="container">

    <h1>Integrações Bling</h1>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif


    @if(session('error'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
   @endif

    <a href="{{ route('bling.create') }}" class="btn btn-primary">Adicionar Integração</a>
    <table class="table table-striped mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Integrado</th>
                <th>Client ID</th>
                <th>Client Secret</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($integracoes as $integracao)
                <tr>
                    <td>{{ $integracao->id }}</td>
                    @if ($integracao->isIntegrado == true)
                         <td><span class="badge text-bg-success">SIM</span></td>
                    @else
                    <td><span class="badge text-bg-warning">NÃO</span></td>
                    @endif
                    <td>{{ $integracao->client_id }}</td>
                    <td>{{ $integracao->client_secret }}</td>
                    <td>
                        @if(!$integracao->isIntegrado)
                        <a href="{{ $integracao->link }}" class="btn btn-success btn-sm">Integrar</a>
                        @endif
                        <a href="{{ route('bling.edit', $integracao->id) }}" class="btn btn-warning btn-sm">Editar</a>
                        <form action="{{ route('bling.destroy', $integracao->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir esta integração?')">Excluir</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
