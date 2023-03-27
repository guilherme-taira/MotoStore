@extends('layouts.app')
@section('title', $viewData['title'])
@section('subtitle', $viewData['subtitle'])
@section('conteudo')

    @if (count($viewData['status']) <= 0)
        <div class="alert alert-danger text-center">Não Há Meios de Pagamentos Cadastrado <a
                href="{{ route('status.create') }}"><strong>Criar a Primeira</strong></a></div>
    @else
        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <a href="{{ route('status.create') }}"><button class="btn btn-success me-md-2" type="button">Novo Status <i class="bi bi-patch-plus"></i></button></a>
        </div>

        <div class="card mt-2">
            <div class="card-header">
                Meios de Pagamento
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Nome</th>
                            <th scope="col">Criado</th>
                            <th scope="col">Última Atualização</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($viewData['status'] as $status)
                            <tr>
                                <th scope="row">{{ $status->id }}</th>
                                <td>{{ $status->nome }}</td>
                                <td>{{ $status->created_at }}</td>
                                <td>{{ $status->updated_at }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        </div>
    @endif
@endsection
