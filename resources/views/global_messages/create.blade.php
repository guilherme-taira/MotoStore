
@extends('layouts.app')

@section('conteudo')
<div class="container">
    <h1 class="my-4">Criar Nova Mensagem</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('global_messages.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="title" class="form-label">Título</label>
            <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required>
        </div>
        <div class="mb-3">
            <label for="content" class="form-label">Conteúdo</label>
            <textarea class="form-control" id="content" name="content" rows="4" required>{{ old('content') }}</textarea>
        </div>
        <div class="mb-3">
            <label for="start_at" class="form-label">Início</label>
            <input type="datetime-local" class="form-control" id="start_at" name="start_at" value="{{ old('start_at') }}" required>
        </div>
        <div class="mb-3">
            <label for="end_at" class="form-label">Fim</label>
            <input type="datetime-local" class="form-control" id="end_at" name="end_at" value="{{ old('end_at') }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="{{ route('global_messages.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
