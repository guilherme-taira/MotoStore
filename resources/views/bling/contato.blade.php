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
                <form action="{{route('contatos.store')}}" method="POST" id="contactForm">
                    <!-- CSRF Token -->
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="text" name="integracao_bling_id" value={{$viewData['integracao_bling']}}>
                    <!-- Nome -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="nome" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="nome" name="nome" maxlength="255" required>
                        </div>
                    </div>

                    <!-- Email e Celular -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" class="form-control" id="email" name="email" maxlength="255">
                        </div>
                        <div class="col-md-6">
                            <label for="celular" class="form-label">Celular</label>
                            <input type="text" class="form-control" id="celular" name="celular" maxlength="20">
                        </div>
                    </div>

                    <!-- Tipo e Situação -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Tipo</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" id="tipoF" name="tipo" value="F" required>
                                <label class="form-check-label" for="tipoF">Física</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" id="tipoJ" name="tipo" value="J" required>
                                <label class="form-check-label" for="tipoJ">Jurídica</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Situação</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" id="situacaoA" name="situacao" value="A" required>
                                <label class="form-check-label" for="situacaoA">Ativo</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" id="situacaoI" name="situacao" value="I" required>
                                <label class="form-check-label" for="situacaoI">Inativo</label>
                            </div>
                        </div>
                    </div>

                    <!-- RG e Documento -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="rg" class="form-label">RG</label>
                            <input type="text" class="form-control" id="rg" name="rg" maxlength="20">
                        </div>
                        <div class="col-md-6">
                            <label for="numeroDocumento" class="form-label">Número do Documento</label>
                            <input type="text" class="form-control" id="numeroDocumento" name="numeroDocumento" maxlength="20">
                        </div>
                    </div>

                    <!-- CEP, Endereço, Bairro -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="cep" class="form-label">CEP</label>
                            <input type="text" class="form-control" id="cep" name="cep" maxlength="10" required>
                        </div>
                        <div class="col-md-4">
                            <label for="endereco" class="form-label">Endereço</label>
                            <input type="text" class="form-control" id="endereco" name="endereco" maxlength="255" required>
                        </div>
                        <div class="col-md-4">
                            <label for="bairro" class="form-label">Bairro</label>
                            <input type="text" class="form-control" id="bairro" name="bairro" maxlength="255" required>
                        </div>
                    </div>

                    <!-- Município, UF -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="municipio" class="form-label">Município</label>
                            <input type="text" class="form-control" id="municipio" name="municipio" maxlength="255" required>
                        </div>
                        <div class="col-md-6">
                            <label for="uf" class="form-label">UF</label>
                            <select class="form-select" id="uf" name="uf" required>
                                <option selected disabled>Selecione o estado</option>
                                <option value="AC">AC</option>
                                <option value="AL">AL</option>
                                <option value="AP">AP</option>
                                <option value="AM">AM</option>
                                <option value="BA">BA</option>
                                <option value="CE">CE</option>
                                <option value="DF">DF</option>
                                <option value="ES">ES</option>
                                <option value="GO">GO</option>
                                <option value="MA">MA</option>
                                <option value="MT">MT</option>
                                <option value="MS">MS</option>
                                <option value="MG">MG</option>
                                <option value="PA">PA</option>
                                <option value="PB">PB</option>
                                <option value="PR">PR</option>
                                <option value="PE">PE</option>
                                <option value="PI">PI</option>
                                <option value="RJ">RJ</option>
                                <option value="RN">RN</option>
                                <option value="RS">RS</option>
                                <option value="RO">RO</option>
                                <option value="RR">RR</option>
                                <option value="SC">SC</option>
                                <option value="SP">SP</option>
                                <option value="SE">SE</option>
                                <option value="TO">TO</option>
                            </select>
                        </div>
                    </div>

                    <!-- Número e Complemento -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="numero" class="form-label">Número</label>
                            <input type="text" class="form-control" id="numero" name="numero" maxlength="20" required>
                        </div>
                        <div class="col-md-6">
                            <label for="complemento" class="form-label">Complemento</label>
                            <input type="text" class="form-control" id="complemento" name="complemento" maxlength="255">
                        </div>
                    </div>

                    <!-- Botão de Enviar -->
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Enviar</button>
                    </div>
                </form>
            </div>


        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    </div>
@endsection
