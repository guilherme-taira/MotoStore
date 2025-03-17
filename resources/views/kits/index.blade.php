@extends('layouts.app')
@section('conteudo')
<style>

    .badge-new {
        position: absolute;
        top: 10px;
        right: 10px;
        background-color: orange;
        color: white;
        padding: 5px 10px;
        font-size: 0.8rem;
        font-weight: bold;
        border-radius: 5px;
        z-index:1000;
    }

            /* Card Principal */
    .product-form-card {
        background-color: #fff;
        border: 1px solid #e5e5e5;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 15px;
    }

    /* Labels */
    .form-label {
        font-weight: bold;
        color: #333;
    }

    /* Inputs e Selects */
    .form-control, .form-select {
        border-radius: 6px;
        box-shadow: none;
        border-color: #ccc;
    }

    /* Seções do Formulário */
    .form-section {
        border-top: 1px solid #ddd;
        margin-top: 20px;
        padding-top: 20px;
    }

    /* Botão Principal */
    .btn-success {
        border-radius: 6px;
        font-weight: bold;
    }

    /* Loading Spinner */
    .loading-integracao {
        width: 2.5rem;
        height: 2.5rem;
    }

    /* Ajuste para Alinhamento */
    .row .col {
        padding-bottom: 15px;
    }
           .product-title {
        display: -webkit-box;
        -webkit-line-clamp: 2; /* Limita a 2 linhas */
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis; /* Adiciona reticências */
        font-size: 1rem;
        font-weight: bold;
        color: #333;
        margin-bottom: 8px;
        line-height: 1.4rem;
    }
            /* Botões de ação */
    .btn-primary, .btn-success {
        font-weight: bold;
        text-transform: uppercase;
        border: none;
        padding: 6px 12px;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background: #4C566A;
        color: #fff;
        transform: scale(1.05);
    }

    .btn-success:hover {
        background: #218838;
        color: #fff;
        transform: scale(1.05);
    }
            /* Botões principais */
       /* Estilo do card de produto */
       .product-card {
        background-color: #fff; /* Fundo branco */
        border: 1px solid #e5e5e5;
        border-radius: 10px;
        padding: 15px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }

    .product-card:hover {
        transform: scale(1.03); /* Efeito ao passar o mouse */
    }

    /* Imagem do produto */
    .product-img {
        width: 100%;
        max-height: 200px;
        object-fit: contain;
        margin-bottom: 10px;
    }

    /* Etiqueta de desconto e novidade */
    .badge-discount {
        position: absolute;
        top: 10px;
        left: 10px;
        background-color: #FF5252;
        color: white;
        font-size: 0.8rem;
        border-radius: 4px;
        padding: 2px 8px;
    }

    .badge-new {
        position: absolute;
        top: 10px;
        right: 10px;
        background-color: #FF9800;
        color: white;
        font-size: 0.8rem;
        border-radius: 4px;
        padding: 2px 8px;
    }

    /* Preços */
    .product-price {
        font-size: 1.2rem;
        font-weight: bold;
        color: #E91E63; /* Cor vermelha para preço */
    }

    .product-price-old {
        text-decoration: line-through;
        color: #9E9E9E;
        font-size: 0.9rem;
    }

    /* Ícones e ações */
    .product-actions i {
        color: #777;
        font-size: 1rem;
        margin-right: 10px;
        cursor: pointer;
        transition: color 0.2s;
    }

    .product-actions i:hover {
        color: #E91E63;
    }

    /* Avaliação */
    .product-rating {
        color: #FFD700;
        font-size: 0.9rem;
    }
    /* Fundo escuro semi-transparente */
    .message-container {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100vh;
        background: rgba(0, 0, 0, 0.8);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    /* Card com efeito tecnológico */
    .message-card {
        background: linear-gradient(135deg, #141e30 0%, #243b55 100%);
        border-radius: 15px;
        padding: 20px;
        text-align: center;
        box-shadow: 0 0 20px rgba(0, 255, 255, 0.6);
        animation: pulseNeonSuccess 1.5s infinite alternate;
        max-width: 400px;
        position: relative;
    }

    /* Título */
    .message-content h3 {
        color: #0ff;
        text-transform: uppercase;
        margin-bottom: 10px;
        font-weight: bold;
    }

    /* Texto da mensagem */
    .message-content p {
        color: #fff;
        font-size: 16px;
    }

    /* Botão de fechar */
    .close-btn {
        position: absolute;
        top: 10px;
        right: 15px;
        font-size: 20px;
        color: #0ff;
        cursor: pointer;
        transition: 0.3s;
    }

    .close-btn:hover {
        color: #fff;
    }

    /* Animação neon */
    @keyframes pulseNeonSuccess {
        from {
            box-shadow: 0 0 10px rgba(0, 255, 255, 0.6);
        }
        to {
            box-shadow: 0 0 30px rgba(0, 255, 255, 1);
        }
    }

    /* Fundo escuro semi-transparente */
.error-container {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100vh;
    background: rgba(0, 0, 0, 0.8);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

/* Card tecnológico de erro */
.error-card {
    background: linear-gradient(135deg, #FFB005FF 0%, #FFEE01FF 100%);
    border-radius: 15px;
    padding: 20px;
    text-align: center;
    box-shadow: 0 0 20px rgba(255, 0, 0, 0.6);
    animation: pulseNeon 1.5s infinite alternate;
    max-width: 650px;
    position: relative;
}

/* Título */
.error-content h3 {
    color: #000000FF;
    text-transform: uppercase;
    margin-bottom: 10px;
    font-weight: bold;
}

/* Lista de erros */
.error-content ul {
    color: #000000;
    font-size: 16px;
    text-align: left;
    list-style: none;
    padding: 0;
}

.error-content li::before {
    content: "⚠ ";
    color: #EFD658FF;
    font-weight: bold;
    margin-right: 5px;
}

/* Botão de fechar */
.close-btn {
    position: absolute;
    top: 10px;
    right: 15px;
    font-size: 20px;
    color: #FF0000FF;
    cursor: pointer;
    transition: 0.3s;
}

.close-btn:hover {
    color: #fff;
}

/* Animação neon pulsante */
@keyframes pulseNeon {
    from {
        box-shadow: 0 0 10px rgba(255, 0, 0, 0.6);
    }
    to {
        box-shadow: 0 0 30px rgba(255, 0, 0, 1);
    }
}
    </style>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!--- MODAL QUE SELECIONA O MOTORISTA --->

    @if ($errors->any())
    <div class="error-container">
        <div class="error-card">
            <div class="error-content">
                <span class="close-btn" onclick="closeError()">×</span>
                <h3>Erro ao Integrar o produto</h3>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <script>
        setTimeout(() => { closeError(); }, 15000); // Fecha automaticamente em 7s

        function closeError() {
            let msg = document.querySelector(".error-container");
            if (msg) msg.style.display = "none";
        }
    </script>
@endif


    @if (session('msg'))
        <div class="message-container">
            <div class="message-card">
                <div class="message-content">
                    <span class="close-btn" onclick="closeMessage()">×</span>
                    <h3>Sucesso!</h3>
                    <p>{{ session('msg') }}</p>
                </div>
            </div>
        </div>
        <script>
            setTimeout(() => { closeMessage(); }, 5000); // Fecha automaticamente em 5s

            function closeMessage() {
                let msg = document.querySelector(".message-container");
                if (msg) msg.style.display = "none";
            }
        </script>
        {{ session()->forget('msg') }}
    @endif


    <div class="modal fade" id="exampleModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalToggleLabel">Integrações MarketPlace</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="accordion accordion-flush" id="accordionFlushExample">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-headingOne">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#flush-collapseOne" aria-expanded="false"
                                    aria-controls="flush-collapseOne">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked"
                                            checked>
                                        <label class="form-check-label" for="flexCheckChecked">
                                            Mercado Livre
                                        </label>
                                    </div>
                                </button>
                            </h2>

                            <div class="spinner-overlay" id="loading-api">
                                <div class="spinner-border spinner-big text-light" role="status">
                                    <span class="visually-hidden">Carregando...</span>
                                </div>
                            </div>

                            <div id="flush-collapseOne" class="accordion-collapse collapse"
                                aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">

                                <div class="accordion-body">
                                    <form method="POST" action="{{ route('IntegrarProduto') }}"
                                        enctype="multipart/form-data">
                                        @csrf

                                        <!-- Card do Produto -->
                                        <div class="card mb-4 product-form-card">
                                            <div class="row g-0">
                                                <!-- Imagem do Produto -->
                                                <div class="col-md-4 text-center">
                                                    <img class="img-fluid rounded-start img_integracao_foto" alt="Produto">
                                                </div>
                                                <!-- Informações do Produto -->
                                                <div class="col-md-8">
                                                    <div class="card-body">
                                                        <h5 class="card-title img_integracao_title">Nome do Produto</h5>
                                                        <p class="card-text img_integracao_ean">EAN: 123456789</p>
                                                        <p class="card-text img_integracao_price">Preço: R$ 0,00</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <input type="hidden" class="form-control" name="id_prodenv" id="id_prodenv">
                                        <!-- Campo Nome -->
                                        <div class="row">
                                            <div class="col-md-12">
                                                Título do Anúncio: <div id="contador" class="text-end">0/60</div>
                                                <input type="text" class="form-control" name="name" id="name"
                                                    placeholder="Digite o nome do produto">
                                                <div class="progress mt-2">
                                                    <div id="progress-bar" class="progress-bar" role="progressbar"
                                                        style="width: 0%;" aria-valuenow="0" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Campo Descrição -->
                                        <div class="row form-section">
                                            <div class="col-md-12">
                                                <label class="form-label" for="editor">Descrição do Anúncio</label>
                                                <textarea name="editor" id="editor" rows="4" class="form-control" placeholder="Digite a descrição"></textarea>
                                            </div>
                                        </div>

                                        <div class="row form-section gap-3">
                                            <div class="col-md-6 mb-4">
                                                <p class="col-lg-4 col-md-6 col-sm-12 col-form-label">Tipo de Anúncio</p>
                                                <div class="col">
                                                    <div class="mb-3 row">
                                                        <select name="tipo_anuncio" class="form-control"
                                                            aria-label=".form-select-sm example" required>
                                                            <option value="gold_special">Clássico</option>
                                                            <option value="gold_pro">Premium</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-5 mb-4">
                                                <p class="col-lg-4 col-md-12 col-sm-12 col-form-label">Material de Apoio
                                                </p>
                                                <div class="col">
                                                    <a class="linkMaterial btn btn-success" id="linkMaterial"
                                                        target="_blank">Baixar Material de Apoio</a>
                                                </div>
                                            </div>
                                        </div>


                                        <!-- Valor Agregado -->
                                        <div class="row form-section">
                                            <div class="col-md-6">
                                                <label class="form-label" for="editValorAgregado">Valor Agregado</label>
                                                <select id="editValorAgregado" class="form-select" name="valor_tipo"
                                                    required>
                                                    <option value="">Selecione uma opção</option>
                                                    <option value="acrescimo_reais">Acréscimo R$</option>
                                                    <option value="acrescimo_porcentagem">Acréscimo %</option>
                                                    <option value="desconto_reais">Desconto R$</option>
                                                    <option value="desconto_porcentagem">Desconto %</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label" for="valorAgregadoInput">Valor</label>
                                                <input type="text" class="form-control" id="valorAgregadoInput"
                                                    name="valor_agregado" value="0" required>
                                            </div>
                                        </div>

                                        <!-- Checkbox para preço fixo -->
                                        <div class="row form-section">
                                            <div class="row-md-2">
                                                <input type="checkbox" class="form-check-input" id="precoFixoCheckbox"
                                                    name="precoFixo">
                                                <label class="form-check-label" for="precoFixoCheckbox">Ativar Preço
                                                    Fixo</label>
                                                <small id="precoFixoCheckbox" class="form-text text-muted">Não use virgula
                                                    no preço, coloque ponto ex: 35.90.</small>
                                            </div>

                                            <!-- Input para Preço Fixo -->
                                            <div class="col-md-3">
                                                <label for="precoFixoInput" class="form-label">Preço Fixo</label>
                                                <input type="text" class="form-control" id="precoFixoInput"
                                                    name="precoFixo" placeholder="Digite o preço fixo" required disabled>
                                            </div>

                                            <!-- Hidden input para isPorcem -->
                                            <input type="hidden" id="isPorcem" name="isPorcem" value="0">
                                        </div>
                                        <!-- Preço e Total -->
                                        <div class="row form-section">
                                            <div class="col-md-4">
                                                <label class="form-label" for="precoFinal">Preço:</label>
                                                <input name="price" id="precoFinal" type="text"
                                                    class="form-control" readonly>
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label" for="valorProdutoDisplay">Total:</label>
                                                <input name="totalInformado" id="valorProdutoDisplay" type="text"
                                                    class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-md-6 mt-4">
                                            <div class="col">
                                                <div class="mb-3 row">
                                                    <div class="col-lg-8 col-md-6 col-sm-12">
                                                        <div class="form-check">
                                                            <input type="hidden" class="form-control" name="category_id"
                                                                id="category_id">
                                                            <input class="form-check-input" type="checkbox"
                                                                name="category_default" id="flexCheckChecked" checked>
                                                            <label class="form-check-label" for="flexCheckChecked">
                                                                Usar Categoria Padrão Selecionada
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Categoria -->
                                        <div class="row form-section">
                                            <div class="col-md-6">
                                                <div class="col">
                                                    <div class="mb-3 row">
                                                        <label
                                                            class="col-lg-2 col-md-12 col-sm-12 col-form-label">Categorias:</label>
                                                        <div class="col-lg-8 col-md-12 col-sm-12">
                                                            <select class="form-select" id="categorias"
                                                                aria-label="Default select example">
                                                                <option selected disabled>Selecionar</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="col">
                                                    <div class="mb-3 row">
                                                        <ol class="list-group list-group-numbered content_categorias">
                                                        </ol>
                                                    </div>
                                                </div>
                                            </div>


                                            <div id="formContainer"></div>

                                            <input type="hidden" class="form-control" name="id_categoria"
                                                id="id_categoria">
                                        </div>

                                        <!-- Spinner e Botão -->
                                        <div class="row form-section">
                                            <div class="col-md-12 text-end">
                                                <div class="spinner-border text-success loading-integracao d-none"
                                                    role="status">
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
                                                <button type="submit" class="btn btn-success botao_integracao">Finalizar
                                                    Integração</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <!--- HISTORICO DO PRODUTO --->

                                <ul class="list-group ">
                                    <li class="list-group-item active" aria-current="true">Histórico</li>
                                    <div class="adicionarHistorico"></div>
                                </ul>

                                <!---  FINAL DO HISTORICO  --->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--- FINAL DO MODAL ---->


    <div class="card mb-4">
        <div class="card-header">
            Lista de Kits

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a href="{{ route('kits.create') }}"><button class="btn btn-success me-md-2" type="button">Novo
                        Kit <i class="bi bi-basket2"></i></button></a>
            </div>

        </div>
        <div class="card-body">
            @if (!empty($msg))
                <div class="alert alert-success" role="alert">
                    {{ $msg }}
                </div>
            @endif

            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Imagem</th>
                            <th scope="col">Name</th>
                            <th scope="col">Estoque</th>
                            <th scope="col">Publico</th>
                            <th scope="col">Integrar</th>
                            <th scope="col">Edit</th>
                            <th scope="col">Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($viewData['kits'] as $product)
                            <section class="col-md-3 mb-4 linhasProduct">
                                <tr>
                                    <td class="id_product">{{ $product->id }}</td>
                                    <td>
                                        <img src="{!! Storage::disk('s3')->url('produtos/' . $product->product_id . '/' . $product->image) !!}"
                                            style="height: 50px; max-width: 100%; object-fit: contain;"
                                            alt="{{ $product->title }}">
                                    </td>
                                    <td>{{ $product->title }}</td>
                                    <td>{{ $product->estoque_minimo_afiliado }}</td>
                                    @if ($product->isPublic == 1)
                                        <td><i class="bi bi-check2-square text-success"></i></td>
                                    @else
                                        <td><i class="bi bi-slash-circle text-danger"></i></td>
                                    @endif
                                    <td>
                                        <button class="btn btn-success btn-sm integrar-btn" style="border-radius: 20px;"
                                            data-bs-toggle="modal" data-bs-target="#exampleModalToggle">
                                            Integrar
                                        </button>
                                    </td>
                                    <td>
                                        <a href="{{ route('products.edit', ['id' => $product->id]) }}">
                                            <button class="btn btn-primary btn-sm"><i class="bi bi-pencil-square"></i>
                                                Editar</button>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('products.edit', ['id' => $product->id]) }}">
                                            <button class="btn btn-danger btn-sm"><i class="bi bi-trash"></i>
                                                Deletar</button>
                                        </a>
                                    </td>
                                </tr>
                            </section>
                        @endforeach
                        <div class="d-flex justify-content-center mt-4">
                            {!! $viewData['kits']->links() !!}
                        </div>
                    </tbody>
                </table>
            </div>

        </div>
    @endsection

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.theme.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.3/jquery.mask.min.js"></script>
    <script>
        $(document).ready(function() {


            // FUNÇÂO PRECO AGREGADO
            // Função para atualizar o valor exibido
            const isPorcemInput = document.getElementById('isPorcem');
            const precoFixoCheckbox = document.getElementById('precoFixoCheckbox');
            const valorAgregadoInput = document.getElementById('valorAgregadoInput');
            const valorAgregadoSelect = document.getElementById('editValorAgregado');

            // Função para bloquear/desbloquear campos quando Preço Fixo é ativado
            precoFixoCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    // Ativa o campo Preço Fixo
                    precoFixoInput.disabled = false;

                    // Desativa campos de Valor Agregado
                    valorAgregadoInput.value = ''; // Limpa o valor
                    valorAgregadoInput.disabled = true;
                    valorAgregadoSelect.disabled = true;
                    isPorcemInput.value = '0'; // Garante que isPorcem seja false
                } else {
                    // Desativa o campo Preço Fixo
                    precoFixoInput.disabled = true;
                    precoFixoInput.value = ''; // Limpa o valor

                    // Reativa campos de Valor Agregado
                    valorAgregadoInput.disabled = false;
                    valorAgregadoSelect.disabled = false;
                }
            });

            // Atualiza o placeholder dinamicamente conforme a seleção do select
            valorAgregadoSelect.addEventListener('change', function() {
                const selectedOption = valorAgregadoSelect.value;

                switch (selectedOption) {
                    case 'acrescimo_reais':
                        valorAgregadoInput.placeholder = 'Digite o acréscimo em R$';
                        break;
                    case 'acrescimo_porcentagem':
                        valorAgregadoInput.placeholder = 'Digite o acréscimo em %';
                        isPorcemInput.value = '1';
                        break;
                    case 'desconto_reais':
                        valorAgregadoInput.placeholder = 'Digite o desconto em R$';
                        break;
                    case 'desconto_porcentagem':
                        valorAgregadoInput.placeholder = 'Digite o desconto em %';
                        isPorcemInput.value = '1';
                        break;
                    default:
                        valorAgregadoInput.placeholder = 'Digite o valor';
                        isPorcemInput.value = '0';
                        break;
                }
            });

            function atualizarValorProduto() {

                const tipoAgregado = valorAgregadoSelect.value; // Opção selecionada (R$ ou %)
                const valorAgregado = parseFloat(valorAgregadoInput.value) || 0;
                const precoFixo = parseFloat($("#precoFixoInput").val().replace(',', '.')) || 0;

                basePrice = parseFloat($('#precoFinal').val()) || 0; // Preço base do produto

                let novoValor = basePrice; // Começa com o preço base

                // Prioridade para preço fixo
                if (precoFixo > 0) {
                    novoValor = precoFixo;
                } else {
                    // Aplica o cálculo com base no tipo selecionado
                    if (tipoAgregado === 'acrescimo_reais') {
                        novoValor = basePrice + valorAgregado;
                    } else if (tipoAgregado === 'acrescimo_porcentagem') {
                        novoValor = basePrice + (basePrice * (valorAgregado / 100));
                    } else if (tipoAgregado === 'desconto_reais') {
                        novoValor = basePrice - valorAgregado;
                    } else if (tipoAgregado === 'desconto_porcentagem') {
                        novoValor = basePrice - (basePrice * (valorAgregado / 100));
                    }

                }
                // Garante que o valor não seja negativo
                if (novoValor < 0) novoValor = 0;
                // Atualiza o display do valor
                $("#valorProdutoDisplay").val(novoValor.toFixed(2).replace('.', ','));
            }

            // Eventos
            valorAgregadoInput.addEventListener('input', atualizarValorProduto);
            valorAgregadoSelect.addEventListener('change', atualizarValorProduto);
            precoFixoInput.addEventListener('input', atualizarValorProduto);



            // FINAL DA FUNÇÂO

            function atualizarProgresso() {
                var caracteresDigitados = $('#name').val().length;
                $('#contador').text(caracteresDigitados + '/60');
                var progresso = (caracteresDigitados / 60) * 100;
                $('#progress-bar').css('width', progresso + '%').attr('aria-valuenow', progresso);

                if (caracteresDigitados > 60) {
                    $('#name').val($('#name').val().substr(0, 60));
                    $('#contador').text(60 + '/60');
                    alert("O valor não pode exceder 60 caracteres.");
                }
            }

            // Ativar a função quando o usuário digitar
            $('#name').on('keyup', atualizarProgresso);

            $.ajax({
                url: "https://api.mercadolibre.com/sites/MLB/categories",
                type: "GET",
                success: function(response) {
                    if (response) {

                        // SHOW ALL RESULT QUERY
                        var index = [];
                        $.each(response, function(i, item) {
                            index[i] = '<option class="option-size" value=' + item.id + '>' +
                                item.name + '</option>';

                            if (i == 0) {
                                // PEGA A ALTERACAO DAS CATEGORIAS
                                $("#categorias").change(function() {
                                    var ids = $(this).children("option:selected").val();
                                    var name = $(this).children("option:selected")
                                    .text();
                                    var content_category =
                                        '<li class="list-group-item">' + name +
                                        '</li>';
                                    $(".content_categorias").append(content_category);
                                    $("#id_categoria").val(
                                        ids); // COLOCA O ID DA CATEGORIA NO CAMPO
                                    getCategory(ids);
                                });
                            }

                        });

                        var arr = jQuery.makeArray(index);
                        arr.reverse();
                        $("#categorias").html(arr);
                    }
                },
                error: function(error) {
                    $('#result').html(
                        '<option> Produto Digitado Não Existe! </option>'
                    );
                }
            });

            // FUNCAO PARA CHAMAR CATEGORIAS
            function getCategory(category) {
                $.ajax({
                    url: " https://api.mercadolibre.com/categories/" + category,
                    type: "GET",
                    success: function(response) {
                        if (response) {
                            console.log(response);
                            // SHOW ALL RESULT QUERY
                            var index = [];
                            // Adiciona a primeira opção estática
                            index.push('<option class="option-size" >Selecionar</option>');

                            $.each(response.children_categories, function(i, item) {
                                // Crie suas opções dinâmicas aqui
                                var option = '<option class="option-size" value=' + item.id +
                                    '>' + item.name + '</option>';
                                index.push(option);
                            });

                            if (index.length <= 1) {
                                $.ajax({
                                    url: " https://api.mercadolibre.com/categories/" +
                                        category + "/attributes",
                                    type: "GET",
                                    success: function(response) {
                                        if (response) {

                                            const requiredItems = [];
                                            const requiredAttributeNames = ['BRAND',
                                                'MODEL', 'LENGTH', 'HEIGHT'
                                            ];
                                            response.forEach(item => {
                                                if (item.tags && item.tags
                                                    .required === true && !
                                                    requiredAttributeNames.includes(
                                                        item.id)) {
                                                    requiredItems.push(item);
                                                }
                                            });

                                            // Adiciona o h2
                                            var h2 = document.createElement("h2");
                                            h2.textContent = "Campos Obrigatórios";
                                            formContainer.appendChild(h2);

                                            requiredItems.forEach(element => {
                                                // Adiciona o label
                                                var label = document.createElement(
                                                    "label");
                                                label.textContent = element.name;
                                                formContainer.appendChild(label);

                                                if (!element.values) {
                                                    var input = document
                                                        .createElement("input");
                                                    // Define o tipo do input como "text"
                                                    input.type = "text";
                                                    input.className =
                                                        "form-control";
                                                    input.name = element.id;
                                                    // Define um ID para o input (opcional)
                                                    // Adiciona o input ao corpo do documento (ou a qualquer outro elemento desejado)
                                                    formContainer.appendChild(
                                                        input);
                                                } else {
                                                    var selectField = document
                                                        .createElement("select");
                                                    for (var i = 0; i < element
                                                        .values.length; i++) {
                                                        var option = document
                                                            .createElement(
                                                                "option");
                                                        selectField.className =
                                                            "form-control";
                                                        selectField.name = element
                                                            .id;
                                                        option.text = element
                                                            .values[i].name;
                                                        option.value = element
                                                            .values[i].id;
                                                        selectField.appendChild(
                                                            option);

                                                    }
                                                    formContainer.appendChild(
                                                        selectField);
                                                }

                                            });
                                        }
                                    }
                                });
                            }
                            $("#categorias").html(index.join(''));
                        }
                    },
                    error: function(error) {
                        $('#result').html(
                            '<option> Produto Digitado Não Existe! </option>'
                        );
                    }
                });

            }


            $(document).on('click', '.integrar-btn', function(e) {
                e.preventDefault();

                // Localiza a seção que contém os dados do produto
                var $section = $(this).closest('.linhasProduct');
                // Pega o texto do td que contém o id (garanta que o td tenha a classe id_product)
                var $tr = $(this).closest('tr');
                var id_produto = $tr.find('.id_product').text().trim();
                $('#id_prodenv').val(''); // Limpa o campo de ID

                // Agora, execute sua requisição AJAX ou outras operações usando id_produto
                $.ajax({
                    url: "/api/v1/product/" + id_produto,
                    type: "GET",
                    success: function(response) {
                        $("#loading-api").addClass('d-none');
                        if (response) {
                            $('#id_prodenv').val(id_produto); // ID DO PRODUTO
                            // Atualiza os campos com os dados recebidos
                            $("#linkMaterial").attr("href", response.link);
                            $("#total").val(response.priceWithFee);
                            $("#name").val(response.title);
                            $("#precoFinal").val(response.priceKit);
                            $("#category_id").val(response.category_id);
                            $(".img_integracao_foto").attr('src', response.image);
                            $(".img_integracao_title").html(response.title);
                            $(".img_integracao_ean").html("EAN: " + response.ean);
                            $(".img_integracao_price").html("Preço: " + response.priceWithFee);
                            $('#editor').val(response.description);
                            atualizarProgresso();
                        }
                    },
                    error: function(error) {
                        $('#result').html('<option> Produto Digitado Não Existe! </option>');
                    }
                });
            });



            $("form").submit(function(event) {
                // event.preventDefault();
                $("#BtnCadastrar").addClass('d-none');
                $('#carregando').removeClass('d-none');
            });


            // VALOR TOTAL
            var total = $('#total').val();
            var totalCalculado = total;

            $('#precoFinal').val(parseFloat(total).toFixed(2));

            // MASCARA DE PORCENTAGEM
            $('.porcem').mask('Z9999.999', {
                translation: {
                    'Z': {
                        pattern: /[\-\+]/,
                        optional: true
                    }
                }
            });

            $('#descontoP').keyup(function() {
                if ($('#descontoP').val().length >= 1) {
                    var porcem = $('#descontoP').val();
                    totalCalculado = parseFloat(total) - parseFloat(calculaPorcemtagem(total, porcem));
                    $('#precoFinal').val(parseFloat(totalCalculado).toFixed(2));

                    $('#acressimoP').prop("disabled", true).css({
                        'background-color': 'red'
                    });
                    $('#acressimoR').prop("disabled", true).css({
                        'background-color': 'red'
                    });;
                    $('#descontoR').prop("disabled", true).css({
                        'background-color': 'red'
                    });
                } else {
                    totalCalculado = parseFloat(total);
                    $('#precoFinal').val(parseFloat(totalCalculado).toFixed(2));
                    $('#acressimoP').prop("disabled", false).css({
                        'background-color': 'white'
                    });
                    $('#acressimoR').prop("disabled", false).css({
                        'background-color': 'white'
                    });;
                    $('#descontoR').prop("disabled", false).css({
                        'background-color': 'white'
                    });
                }
            });

            $('#descontoR').keyup(function() {
                $('#descontoR').keyup(function() {
                    var reais = $('#descontoR').val();
                    totalCalculado = parseFloat(total) - parseFloat(reais);
                    $('#precoFinal').val(parseFloat(totalCalculado).toFixed(2));
                    if ($('#descontoR').val().length >= 1) {
                        $('#acressimoR').prop("disabled", true).css({
                            'background-color': 'red'
                        });
                        $('#acressimoP').prop("disabled", true).css({
                            'background-color': 'red'
                        });;
                        $('#descontoP').prop("disabled", true).css({
                            'background-color': 'red'
                        });
                    } else {
                        $('#precoFinal').val(parseFloat(totalCalculado).toFixed(2));
                        $('#acressimoR').prop("disabled", false).css({
                            'background-color': 'white'
                        });
                        $('#acressimoP').prop("disabled", false).css({
                            'background-color': 'white'
                        });;
                        $('#descontoP').prop("disabled", false).css({
                            'background-color': 'white'
                        });
                    }
                });
            });

            $('#acressimoP').keyup(function() {
                if ($('#acressimoP').val().length >= 1) {
                    var porcem = $('#acressimoP').val();
                    totalCalculado = parseFloat(total) + parseFloat(calculaPorcemtagem(total, porcem));
                    $('#precoFinal').val(parseFloat(totalCalculado).toFixed(2));
                    $('#acressimoR').prop("disabled", true).css({
                        'background-color': 'red'
                    });
                    $('#descontoP').prop("disabled", true).css({
                        'background-color': 'red'
                    });;
                    $('#descontoR').prop("disabled", true).css({
                        'background-color': 'red'
                    });
                } else {
                    totalCalculado = parseFloat(total);
                    $('#precoFinal').val(parseFloat(totalCalculado).toFixed(2));
                    $('#acressimoR').prop("disabled", false).css({
                        'background-color': 'white'
                    });
                    $('#descontoR').prop("disabled", false).css({
                        'background-color': 'white'
                    });
                    $('#descontoP').prop("disabled", false).css({
                        'background-color': 'white'
                    });
                }
            });

            $('#acressimoR').keyup(function() {
                if ($('#acressimoR').val().length >= 1) {
                    var reais = $('#acressimoR').val();
                    totalCalculado = parseFloat(total) + parseFloat(reais);
                    $('#precoFinal').val(parseFloat(totalCalculado).toFixed(2));

                    $('#acressimoP').prop("disabled", true).css({
                        'background-color': 'red'
                    });
                    $('#descontoP').prop("disabled", true).css({
                        'background-color': 'red'
                    });;
                    $('#descontoR').prop("disabled", true).css({
                        'background-color': 'red'
                    });
                } else {
                    totalCalculado = parseFloat(total) + 0;
                    $('#precoFinal').val(parseFloat(totalCalculado).toFixed(2));
                    $('#acressimoP').prop("disabled", false).css({
                        'background-color': 'white'
                    });
                    $('#descontoP').prop("disabled", false).css({
                        'background-color': 'white'
                    });;
                    $('#descontoR').prop("disabled", false).css({
                        'background-color': 'white'

                    });
                }
            });

            function calculaPorcemtagem(valor, porcem) {
                //60 x 25% = 160 (25/100) = 160 x 0,25 = 40.
                return valor * (porcem / 100);
            }

            // TOKEN DE ASSINATURA
            // let _token = $('meta[name="csrf-token"]').attr('content');
            // SETA O VALOR TOTAL DO KIT
            $('#total').val($('#valorTotalInput').val());
            /**
             * FUNCAO QUE PEGA VALOR DIGITADO NO INPUT
             */

            $("#search").keyup(function() {

                var id = $("#search").val();

                console.log(id);
                $.ajax({
                    url: "/getProductByName",
                    type: "GET",
                    data: {
                        id: id,
                    },
                    success: function(response) {
                        if (response) {
                            $('#result').removeClass('d-none');
                            // CONVERT ARRAY IN JSON FOR EACH FUNCTION
                            var json = $.parseJSON(response.dados);
                            // SHOW ALL RESULT QUERY
                            var index = [];
                            $.each(json, function(i, item) {
                                index[i] =
                                    '<option class="option-size" value=' +
                                    item
                                    .id + '>' + item
                                    .title + '</option>';
                            });

                            var arr = jQuery.makeArray(index);
                            arr.reverse();
                            $("#result").html(arr);

                            $("select").change(function() {
                                $('#id').val($(this).children(
                                    "option:selected").val());
                                $('#name').val($(this).children(
                                        "option:selected")
                                    .text());

                                var number = $('#id').val();
                                if (number > 0) {
                                    $("#btnFinalizar").removeClass(
                                        'd-none');
                                    $('#name,#id').addClass(
                                        'p-3 mb-2 bg-warning text-dark');
                                }
                            });

                        }
                    },
                    error: function(error) {
                        $('#result').html(
                            '<option> Produto Digitado Não Existe! </option>');
                    }
                });
            });

        });
    </script>
