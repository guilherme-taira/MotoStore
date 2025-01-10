@extends('layouts.app')
@section('conteudo')
<div class="container">

    <h1>Integrações Bling</h1>

    <div aria-live="polite" aria-atomic="true" class="position-relative">
        <div class="toast-container position-absolute top-50 end-0 translate-middle-y p-3">
            <!-- Toast de Sucesso -->
            <div id="successToast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
                <div class="d-flex">
                    <div class="toast-body">
                        {{ session('success') ?? 'Operação realizada com sucesso!' }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
            <!-- Toast de Erro -->
            <div id="errorToast" class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
                <div class="d-flex">
                    <div class="toast-body">
                        {{ session('error') ?? 'Ocorreu um erro durante a operação.' }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        </div>
    </div>

    <a href="{{ route('bling.create') }}" class="btn btn-primary">Adicionar Integração</a>
    <a href="{{ route('contatos.create') }}" class="btn btn-secondary">Adicionar Contato</a>
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

    <hr>
    <h2>Informações do afiliado</h2>

    <table class="table table-striped mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Documento</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($contatos as $contato)
                <tr>
                    <td>{{ $contato->id }}</td>
                    <td>{{ $contato->nome }}</td>
                    <td>{{ $contato->numeroDocumento }}</td>
                    <td>
                        <a href="{{ route('contatos.edit', $contato->id) }}" class="btn btn-warning btn-sm">Editar</a>
                        <form action="{{ route('bling.destroy', $contato->id) }}" method="POST" style="display:inline-block;">
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

  <!-- Hero Start -->
  <input type="hidden" name="id_user" id="id_user" value="{{Auth::user()->id}}">

  <link href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" rel="stylesheet">
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- Bootstrap JS -->
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <!-- DateRangePicker JS -->
  <script src="https://cdn.jsdelivr.net/npm/moment/min/moment.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

 $('#telefone').mask('(00) 00000-0000');
// Função para exibir o toast
function showToast(toastId) {
     var toastElement = document.getElementById(toastId);
     var toast = new bootstrap.Toast(toastElement, { autohide: false }); // Garante que o autohide está desativado
     toast.show();
 }

 document.addEventListener('DOMContentLoaded', function () {
    // Verifica se há uma mensagem de sucesso na sessão
    @if (session('success'))
        showToast('successToast');
    @endif

    // Verifica se há uma mensagem de erro na sessão
    @if (session('error'))
        showToast('errorToast');
    @endif
});



</script>


@endsection

