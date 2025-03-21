@extends('layouts.loginNew')
@section('conteudo')
    <h3 class="mb-4 text-center text-white">Registrar</h3>
    {{-- <form method="POST" action="{{ route('register') }}"> --}}
        <form action="#" method="post"></form>
        @csrf

          <!-- Campo Confirmar Senha -->
          <div class="mb-3">
            <label for="hotmart-confirm" class="form-label">
                Código Hotmart
            </label>
            <input id="hotmart-confirm" type="text"
                   class="form-control"
                   name="hotmart" required>
        </div>

        <!-- Campo Nome -->
        <div class="mb-3">
            <label for="name" class="form-label">{{ __('Name') }}</label>
            <input id="name" type="text"
                   class="form-control @error('name') is-invalid @enderror"
                   name="name" value="{{ old('name') }}"
                   required autocomplete="name" autofocus>
            @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <!-- Campo Email -->
        <div class="mb-3">
            <label for="email" class="form-label">{{ __('Email Address') }}</label>
            <input id="email" type="email"
                   class="form-control @error('email') is-invalid @enderror"
                   name="email" value="{{ old('email') }}"
                   required autocomplete="email">
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <!-- Campo Senha -->
        <div class="mb-3">
            <label for="password" class="form-label">{{ __('Password') }}</label>
            <input id="password" type="password"
                   class="form-control @error('password') is-invalid @enderror"
                   name="password" required autocomplete="new-password">
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <!-- Campo Confirmar Senha -->
        <div class="mb-3">
            <label for="password-confirm" class="form-label">
                {{ __('Confirm Password') }}
            </label>
            <input id="password-confirm" type="password"
                   class="form-control"
                   name="password_confirmation" required autocomplete="new-password">
        </div>

        <!-- Checkbox para Aceitar Termos e Condições -->
        <div class="mb-3 form-check">
            <input class="form-check-input @error('accept_terms') is-invalid @enderror"
                   type="checkbox"
                   name="accept_terms"
                   id="accept_terms"
                   value="1"
                   {{ old('accept_terms') ? 'checked' : '' }} required>
            <label class="form-check-label" for="accept_terms">
                Eu li e aceito os
                <a href="#" data-bs-toggle="modal" data-bs-target="#termosModal" style="color:#0d6efd;">
                    Termos e Condições
                </a>
            </label>
            @error('accept_terms')
                <span class="invalid-feedback d-block" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <!-- Botão de Registro -->
        <button type="submit" class="btn btn-primary w-100">
            {{ __('Register') }}
        </button>
    </form>

    <!-- Modal para exibir os Termos e Condições -->
    <div class="modal fade" id="termosModal" tabindex="-1" aria-labelledby="termosModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl">
            <!-- modal-dialog-scrollable permite rolagem no conteúdo do modal -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="termosModalLabel">Termos e Condições de Uso da Plataforma AfiliDrop</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body" style="color: #333;">
                    <p>
                        <strong>TERMOS E CONDIÇÕES DE USO DA PLATAFORMA AFILIDROP</strong><br>
                        Este contrato estabelece os termos e condições de prestação de serviços entre Maximo Company LTDA,
                        inscrita no CNPJ 48.930.389/0001-09, com sede à R. Maj. Arthur Franco Mourão, 1043 - Centro, Leme - SP, 13611-490,
                        doravante denominada Maximo Company LTDA, titular da marca AfiliDrop, e o(a) Afiliado(a), pessoa física ou jurídica,
                        identificado(a) no momento do cadastro na plataforma, doravante denominado(a) Afiliado(a). As partes acima identificadas
                        têm entre si justo e acordado o presente Contrato, mediante as cláusulas e condições a seguir:
                    </p>
                    <p>
                        <strong>CLÁUSULA 1 - OBJETO DO CONTRATO</strong><br>
                        1.1. O presente contrato tem como objeto a prestação de serviços pela AfiliDrop ao Afiliado, por meio de tecnologia
                        que permite ao Afiliado: Comercializar em sua conta produtos fornecidos exclusivamente por fornecedores da AfiliDrop,
                        através de plataformas de e-commerce, respeitando todas as políticas da AfiliDrop. Utilizar a conta do Mercado Livre
                        ou outras plataformas designadas pela AfiliDrop, o endereço da AfiliDrop R. Maj. Arthur Franco Mourão, 1043 - Centro,
                        Leme - SP, 13611-490, para facilitar a conferência, logística e devoluções de produtos. A AfiliDrop atua como prestadora
                        de serviços, intermediando o acesso a produtos de fornecedores para que os Afiliados possam comercializá-los, sem
                        assumir responsabilidades diretas sobre os processos de venda e atendimento ao cliente.
                    </p>
                    <p>
                        <strong>CLÁUSULA 2 - USO E CONFIDENCIALIDADE</strong><br>
                        2.1. O Afiliado reconhece que a plataforma AfiliDrop está em sua versão beta e em fase de implantação, tendo sido
                        convidado(a) para participar dos testes iniciais de maneira restrita e confidencial.<br>
                        2.2. É expressamente vedado ao Afiliado: Filmar, divulgar ou compartilhar qualquer informação de processos,
                        funcionalidades, ou dados de acesso, sem prévia autorização por escrito da Maximo Company LTDA. Divulgar login e senha
                        da conta da plataforma AfiliDrop a terceiros, até nova autorização por meio de atualização deste contrato.
                    </p>
                    <p>
                        <strong>CLÁUSULA 3 - REGRAS DE USO DA PLATAFORMA</strong><br>
                        3.1. O Afiliado deverá: Utilizar exclusivamente os produtos disponibilizados pela AfiliDrop para venda na conta do
                        Mercado Livre ou outra plataforma associada. Utilizar exclusivamente o endereço da Maximo Company LTDA R. Maj. Arthur
                        Franco Mourão, 1043 - Centro, Leme - SP, 13611-490, para postagem e devolução nos Correios ou agências dos marketplaces
                        ou das plataformas associadas.<br>
                        3.2. Não será permitido: Comercializar produtos que não sejam disponibilizados pela AfiliDrop dentro da conta do
                        Mercado Livre ou em outras plataformas associadas. Manter anúncios de produtos que não estejam vinculados à plataforma
                        AfiliDrop, sob pena de suspensão da usabilidade da plataforma.<br>
                        3.3. Caso sejam recebidos em nosso endereço produtos não associados à plataforma AfiliDrop, a Maximo Company LTDA
                        poderá: Descartar o produto ao retornar para o endereço registrado. Excluir automaticamente os anúncios vinculados.
                        Aplicar penalidades de suspensão ou cancelamento do acesso à plataforma, conforme análise da equipe AfiliDrop.
                    </p>
                    <p>
                        <strong>CLÁUSULA 4 - DIREITOS DE IMAGEM, VÍDEO, DESCRIÇÃO E FICHA TÉCNICA</strong><br>
                        4.1. A AfiliDrop disponibilizará imagens, vídeos, descrições e fichas técnicas dos produtos exclusivamente para
                        anúncios vinculados à plataforma AfiliDrop.
                    </p>
                    <p>
                        <strong>CLÁUSULA 5 - BENEFÍCIOS E CONDIÇÕES FINANCEIRAS</strong><br>
                        5.1. O Afiliado convidado para participar da fase beta terá 60 dias de acesso gratuito à plataforma ou até atingir o
                        limite de 20 vendas, o que ocorrer primeiro.
                    </p>
                    <p>
                        <strong>CLÁUSULA 6 - NATUREZA DA RELAÇÃO</strong><br>
                        6.1. A AfiliDrop é uma prestadora de serviços que oferece suporte aos Afiliados na comercialização de produtos por
                        meio de sua plataforma, disponibilizando tecnologia, logística e intermediação com fornecedores.<br>
                        6.2. Este contrato estabelece a prestação de serviços pela AfiliDrop aos Afiliados, sem qualquer vínculo empregatício,
                        societário, de representação, mandato, associação ou agência.
                    </p>
                    <p>
                        <strong>CLÁUSULA 7 - DESVINCULAÇÃO E ENCERRAMENTO</strong><br>
                        7.1. O Afiliado poderá solicitar, a qualquer momento, o encerramento do uso da plataforma AfiliDrop, comunicando
                        formalmente a equipe AfiliDrop por meio dos canais oficiais de atendimento. Cel: (19) 98888-8332<br>
                        7.2. Após a solicitação de encerramento, a Maximo Company LTDA terá um prazo de 30 dias para desvincular completamente
                        a conta do Afiliado da plataforma AfiliDrop. Além disso, todos os anúncios criados através da tecnologia da AfiliDrop
                        deverão ser excluídos pela AfiliDrop.<br>
                        7.3. O Afiliado reconhece que, após o encerramento, não poderá mais acessar os recursos, suporte ou benefícios exclusivos
                        oferecidos pela AfiliDrop.
                    </p>
                    <p>
                        <strong>CLÁUSULA 8 - DISPOSIÇÕES GERAIS</strong><br>
                        8.1. Este contrato poderá ser atualizado pela Maximo Company LTDA mediante comunicação prévia ao Afiliado por meio da
                        plataforma AfiliDrop.
                    </p>
                    <p>
                        <strong>CLÁUSULA 9 - SUPORTE E INSTABILIDADES DA PLATAFORMA</strong><br>
                        9.1. O Afiliado reconhece que, por se tratar de uma plataforma em versão beta, a AfiliDrop pode apresentar instabilidades
                        ou falhas pontuais, e que a Maximo Company LTDA não garante a total disponibilidade ou funcionamento ininterrupto da
                        plataforma durante este período.
                    </p>
                    <p>
                        <strong>CLÁUSULA 10 - RESPONSABILIDADE E LIMITAÇÕES</strong><br>
                        10.1. A AfiliDrop atua exclusivamente como prestadora de serviços, intermediando o acesso dos Afiliados a fornecedores.
                        A AfiliDrop não assume responsabilidade direta pelas transações entre o Afiliado e o cliente final.
                    </p>
                    <p>
                        <strong>CLÁUSULA 11 - PENALIDADES</strong><br>
                        11.1. O descumprimento de qualquer cláusula deste contrato poderá resultar em: Suspensão temporária de acesso à
                        plataforma; Cancelamento permanente do cadastro; Exclusão de todos os anúncios criados com auxílio da plataforma.
                    </p>
                    <!-- ... demais cláusulas ... -->
                </div><!-- modal-body -->
            </div><!-- modal-content -->
        </div><!-- modal-dialog -->
    </div><!-- modal -->
@endsection
