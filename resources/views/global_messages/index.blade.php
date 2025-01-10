@extends('layouts.app')

@section('conteudo')
<div class="container">
    <h1 class="my-4">Mensagens Globais</h1>
    <a href="{{ route('global_messages.create') }}" class="btn btn-primary mb-3">Criar Nova Mensagem</a>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Título</th>
                <th>Conteúdo</th>
                <th>Início</th>
                <th>Fim</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse($messages as $message)
                <tr>
                    <td>{{ $message->title }}</td>
                    <td>{{ Str::limit($message->content, 50) }}</td>
                    <td>{{ $message->start_at }}</td>
                    <td>{{ $message->end_at }}</td>
                    <td>
                        <a href="{{ route('global_messages.edit', $message->id) }}" class="btn btn-warning btn-sm">Editar</a>
                        <form action="{{ route('global_messages.destroy', $message->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir esta mensagem?')">Excluir</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Nenhuma mensagem encontrada.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
