@extends('layouts.app')
@section('conteudo')

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


        <!-- Mensagens de Sucesso -->
        @if(session('successMessage'))
        <div class="alert alert-success">
            {{ session('successMessage') }}
        </div>
        @endif

        <!-- Mensagens de Erro -->
        @if(session('errorMessages'))
        <div class="alert alert-danger">
            <ul>
                @foreach(session('errorMessages') as $errorMessage)
                    <li>{{ $errorMessage }}</li>
                @endforeach
            </ul>
        </div>
        @endif


    <div class="container my-4">
        <!-- Exibir Mensagem de Sucesso -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Sucesso:</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Exibir Mensagem de Erro -->
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Erro:</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

    <div class="container">
            <div class="card-header text-center">
                <h3>Cadastro de Contato para emissão de NF</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('contatos.update', $viewData['contato']->id) }}" method="POST" id="contactForm">
                    <!-- CSRF Token -->
                    @csrf
                    @method('PUT')
                    <!-- Nome -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="nome" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="nome" name="nome" maxlength="255"
                                   value="{{ old('nome', $viewData['contato']->nome) }}" required>
                        </div>
                    </div>

                    <!-- Email e Celular -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" class="form-control" id="email" name="email" maxlength="255"
                                   value="{{ old('email', $viewData['contato']->email) }}">
                        </div>
                        <div class="col-md-6">
                            <label for="celular" class="form-label">Celular</label>
                            <input type="text" class="form-control" id="celular" name="celular" maxlength="20"
                                   value="{{ old('celular', $viewData['contato']->celular) }}">
                        </div>
                    </div>

                    <!-- Tipo e Situação -->
                    <div class="row mb-3">
                        <!-- Tipo -->
                        <div class="col-md-6">
                            <label class="form-label">Tipo</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" id="tipoF" name="tipo" value="F"
                                       {{ old('tipo', $viewData['contato']->tipo) == 'F' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="tipoF">Física</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" id="tipoJ" name="tipo" value="J"
                                       {{ old('tipo', $viewData['contato']->tipo) == 'J' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="tipoJ">Jurídica</label>
                            </div>
                        </div>

                        <!-- Situação -->
                        <div class="col-md-6">
                            <label class="form-label">Situação</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" id="situacaoA" name="situacao" value="A"
                                       {{ old('situacao', $viewData['contato']->situacao) == 'A' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="situacaoA">Ativo</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" id="situacaoI" name="situacao" value="I"
                                       {{ old('situacao', $viewData['contato']->situacao) == 'I' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="situacaoI">Inativo</label>
                            </div>
                        </div>
                    </div>

                    <!-- RG e Documento -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="rg" class="form-label">IE</label>
                            <input type="text" class="form-control" id="ie" name="ie" maxlength="20"
                                   value="{{ old('ie', $viewData['contato']->rg) }}">
                        </div>
                        <div class="col-md-4">
                            <label for="rg" class="form-label">RG</label>
                            <input type="text" class="form-control" id="rg" name="rg" maxlength="20"
                                   value="{{ old('rg', $viewData['contato']->rg) }}">
                        </div>
                        <div class="col-md-4">
                            <label for="numeroDocumento" class="form-label">Número do Documento</label>
                            <input type="text" class="form-control" id="numeroDocumento" name="numeroDocumento" maxlength="20"
                                   value="{{ old('numeroDocumento', $viewData['contato']->numeroDocumento) }}">
                        </div>
                    </div>

                    <!-- CEP, Endereço, Bairro -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="cep" class="form-label">CEP</label>
                            <input type="text" class="form-control" id="cep" name="cep" maxlength="10"
                                   value="{{ old('cep', $viewData['contato']->cep) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="endereco" class="form-label">Endereço</label>
                            <input type="text" class="form-control" id="endereco" name="endereco" maxlength="255"
                                   value="{{ old('endereco', $viewData['contato']->endereco) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="bairro" class="form-label">Bairro</label>
                            <input type="text" class="form-control" id="bairro" name="bairro" maxlength="255"
                                   value="{{ old('bairro', $viewData['contato']->bairro) }}" required>
                        </div>
                    </div>

                    <!-- Município, UF -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="municipio" class="form-label">Município</label>
                            <input type="text" class="form-control" id="municipio" name="municipio" maxlength="255"
                                   value="{{ old('municipio', $viewData['contato']->municipio) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="uf" class="form-label">UF</label>
                            <select class="form-select" id="uf" name="uf" required>
                                <option disabled {{ old('uf', $viewData['contato']->uf) ? '' : 'selected' }}>Selecione o estado</option>
                                @foreach(['AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN', 'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'] as $estado)
                                    <option value="{{ $estado }}" {{ old('uf', $viewData['contato']->uf) == $estado ? 'selected' : '' }}>
                                        {{ $estado }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Número e Complemento -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="numero" class="form-label">Número</label>
                            <input type="text" class="form-control" id="numero" name="numero" maxlength="20"
                                   value="{{ old('numero', $viewData['contato']->numero) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="complemento" class="form-label">Complemento</label>
                            <input type="text" class="form-control" id="complemento" name="complemento" maxlength="255"
                                   value="{{ old('complemento', $viewData['contato']->complemento) }}">
                        </div>
                    </div>

                    <!-- Botão de Enviar -->
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Enviar</button>
                    </div>
                </form>
            </div>


        <!-- Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
        <script>
                $('#cep').mask('00000-000');
                $('#celular').mask('(00) 00000-0000');
        </script>
    </div>
@endsection
