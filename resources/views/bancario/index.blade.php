@extends('layouts.app')
@section('title', $viewData['title'])
@section('subtitle', $viewData['subtitle'])
@section('conteudo')

    <!--- MENSAGEM DE CONFIRMAÇÂO DE SUCESSO --->
    @if (session('msg_success'))
        <div class="alert alert-success" role="alert">
            {{ session('msg_success') }}
        </div>
    @endif
    <!--- FIM MENSAGEM DE CONFIRMAÇÂO DE SUCESSO --->

    @if (count($viewData['bancario']) <= 0)
        <div class="alert alert-danger text-center">Não Há Contas Bancárias Cadastrada <a
                href="{{ route('bancario.create') }}"><strong>Criar a Primeira</strong></a></div>
    @else
        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <a href="{{ route('bancario.create') }}"><button class="btn btn-success me-md-2" type="button">Novo Meio de
                    Pagamento <i class="bi bi-patch-plus"></i></button></a>
        </div>

        <div class="card mt-2">
            <div class="card-header">
                Meios de Pagamento
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr class="text-center">
                            <th scope="col">ID</th>
                            <th scope="col">Banco</th>
                            <th scope="col">Agência</th>
                            <th scope="col">Conta</th>
                            <th scope="col">CPF</th>
                            <th scope="col">Nome</th>
                            <th scope="col">Editar</th>
                            <th scope="col">Deletar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($viewData['bancario'] as $bancario)
                            <tr class="text-center">
                                <th scope="row">{{ $bancario->id }}</th>
                                <td>{{ $bancario->Banco }}</td>
                                <td>{{ $bancario->agencia }}</td>
                                <td>{{ $bancario->conta }}</td>
                                <td>{{ $bancario->cpf }}</td>
                                <td>{{ $bancario->nome }}</td>
                                <td> <a href="{{ route('bancario.edit', ['id' => $bancario->id]) }}"><button
                                            class="btn btn-primary text-white float-end"><i
                                                class="bi bi-pencil-fill"></i></button></a></td>

                                <form action="{{ route('bancario.destroy', ['id' => $bancario->id]) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <td><button class="btn btn-danger text-white float-end"><i
                                                class="bi bi-trash3"></i></button></td>
                                </form>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        </div>
    @endif
@endsection
