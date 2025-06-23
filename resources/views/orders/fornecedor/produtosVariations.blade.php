@extends('layouts.app')
@section('title', $viewData['title'])
@section('conteudo')
<!-- Estilos -->
<link rel="stylesheet" href="{{ asset('css/layout_integracao.css') }}">
    <style>
        .offcanvas {
            width: 300px;
        }

        #importSuccessModal {
            width: 400px;
            /* Ajuste para o tamanho desejado */
        }

        @media (min-width: 768px) {
            #importSuccessModal {
                width: 500px;
                /* Largura em telas médias ou maiores */
            }
        }

        @media (min-width: 1200px) {
            #importSuccessModal {
                width: 600px;
                /* Largura em telas grandes */
            }
        }

        .list-group-item {
            padding: 15px;
            /* Deixe o espaçamento mais agradável */
            border: 1px solid #ddd;
            /* Adicione uma borda leve */
            border-radius: 8px;
            /* Arredonde os cantos */
            margin-bottom: 10px;
            /* Separe os itens */
        }

        .list-group-item img {
            object-fit: contain;
            /* Garante que a imagem não seja distorcida */
        }

        .list-group-item div {
            font-size: 14px;
            /* Tamanho consistente para o texto */
        }

        .circle-pizza {
            position: relative;
            width: 80px;
            height: 80px;

            overflow: hidden;
            border: 2px solid #ccc;
        }

        .slice {
            position: absolute;
            width: 50%;
            height: 50%;
            background-size: cover;
            background-position: center;
        }

        /* Divide em 4 quadrantes */
        .slice-1 {
            top: 0;
            left: 0;
        }

        .slice-2 {
            top: 0;
            right: 0;
        }

        .slice-3 {
            bottom: 0;
            left: 0;
        }

        .slice-4 {
            bottom: 0;
            right: 0;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const importBlingButton = document.getElementById('importBling');

            if (importBlingButton) {
                importBlingButton.addEventListener('click', function() {
                    this.disabled = true;
                    this.innerHTML = 'Importando... <i class="bi bi-hourglass-split"></i>';

                    const userId = {{ Auth::user()->id }};

                    fetch(`/api/v1/productsBling?user_id=${userId}`, {
                            method: "GET",
                            headers: {
                                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                "Accept": "application/json",
                            },
                        })
                        .then(response => response.json())
                        .then(dados => {
                            if (dados.error) {
                                alert("Erro ao importar produtos: " + dados.error);
                            } else {
                                alert("Produtos importados com sucesso!");

                                // Adicionar os produtos no offcanvas
                                const importedProductsList = document.getElementById(
                                    'importedProductsList');
                                importedProductsList.innerHTML = ""; // Limpa a lista antes de adicionar

                                dados.data.forEach(product => {
                                    const listItem = `
                            <li class="list-group-item">
                                <div class="d-flex align-items-center justify-content-between">
                                    <!-- Imagem -->
                                    <div class="d-flex align-items-center" style="flex: 1;">
                                        <img src="${product.imagemURL || 'https://via.placeholder.com/50'}"
                                            alt="${product.nome}"
                                            style="width: 60px; height: auto; margin-right: 15px; border-radius: 5px;">
                                        <div>
                                            <!-- Nome -->
                                            <strong>${product.nome}</strong><br>
                                            <!-- Estoque e Preço -->
                                            <span>Estoque: ${product.estoque?.saldoVirtualTotal ?? 'N/A'} - R$: ${product.preco ?? '0.00'}</span>
                                        </div>
                                    </div>
                                    <!-- Botão -->
                                    <button class="btn btn-primary btn-sm" onclick='importProduct(${JSON.stringify(product)})'>Importar</button>

                                </div>
                            </li>
                        `;

                                    importedProductsList.insertAdjacentHTML('beforeend',
                                        listItem);
                                });
                                // Mostrar o offcanvas
                                const importSuccessModal = new bootstrap.Offcanvas(document
                                    .getElementById('importSuccessModal'));
                                importSuccessModal.show();
                            }
                        })
                });
            }
        });

        function importProduct(product) {
            const button = document.querySelector(`button[onclick='importProduct(${JSON.stringify(product)})']`);

            // Alterar texto e desativar botão enquanto importa
            button.disabled = true;
            button.innerHTML = 'Importando... <i class="bi bi-hourglass-split"></i>';

            // Dados do produto que serão enviados
            const productData = {
                name: product.nome,
                price: product.preco,
                stock: product.estoque?.saldoVirtualTotal ?? 1,
                description: product.descricaoCurta || "Sem descrição disponível",
                brand: product.marca || "Marca não informada",
                ean: product.codigo || "EAN não disponível",
                termometro: 100,
                fee: 1,
                taxaFee: 1,
                PriceWithFee: product.preco,
                height: product.altura || 1,
                width: product.largura || 1,
                length: product.comprimento || 1,
                photos: [product.imagemURL],
                id_categoria: product.categoria || 1,
                priceKit: product.preco,
            };


            // Requisição POST para enviar os dados ao storeBling
            fetch(`/storeBling`, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify(productData),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        button.innerHTML = 'Importado <i class="bi bi-check-circle"></i>';
                        button.classList.remove('btn-primary');
                        button.classList.add('btn-success');
                    } else {
                        button.disabled = false;
                        button.innerHTML = 'Importar';
                        alert("Erro ao importar o produto: " + data.error);
                    }
                })
                .catch(error => {
                    console.error("Erro na requisição:", error);
                    button.disabled = false;
                    button.innerHTML = 'Importar';
                    alert("Erro ao se comunicar com o servidor. Verifique os logs.");
                });
        }
    </script>


    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!--- MODAL QUE SELECIONA O MOTORISTA --->

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
            setTimeout(() => {
                closeMessage();
            }, 5000); // Fecha automaticamente em 5s

            function closeMessage() {
                let msg = document.querySelector(".message-container");
                if (msg) msg.style.display = "none";
            }
        </script>
        {{ session()->forget('msg') }}
    @endif


    <div class="modal fade" id="exampleModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel"
        tabindex="-1">
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
                                    <form method="POST" id="formIntegracao"
                                        action="{{ route('IntegrarProdutoVariation') }}" enctype="multipart/form-data">
                                        @csrf

                                        <ul id="listaVariacoes" class="list-unstyled"></ul>

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

                                        <input type="hidden" name="variacoes_json" id="variacoes_json">

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
    <div class="modal fade" id="exampleModalToggle2" tabindex="-1" aria-labelledby="modalProdutosLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalProdutosLabel">Variações do Produto</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Fechar"></button>
                </div>
                <div class="modal-body" id="modalProdutoBody">
                    <p>Carregando variações...</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>



    <div class="offcanvas offcanvas-start" tabindex="-1" id="importSuccessModal"
        aria-labelledby="importSuccessModalLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="importSuccessModalLabel">Produtos Importados</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <ul id="importedProductsList" class="list-group">
                <!-- Os produtos importados serão listados aqui -->
            </ul>
        </div>
    </div>


    <div class="container-fluid px-4">

        <div class="card mt-2">
            @if ($errors->any())
                <ul class="alert alert-danger list-unstyled">
                    @foreach ($errors->all() as $error)
                        <li>-> {{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            @if (session('msg'))
                <div class="alert alert-success" role="alert">
                    {{ session('msg') }}
                    {{ session()->forget('msg') }}
                </div>
            @endif

            @if (session('msg_error'))
                <div class="alert alert-danger" role="alert">
                    {{ session('msg_error') }}
                    {{ session()->forget('msg_error') }}
                </div>
            @endif


            <div class="card-header">
                Gerenciar Variações
            </div>
            <div class="card-body">
                <table class="table table-hover border rounded shadow-sm overflow-hidden">
                    <thead class="text-white" style="background-color: #4a69bd; border-radius: 12px;">
                        <tr>
                            <th scope="col" class="py-3 px-3">Variações</th>
                            <th scope="col" class="py-3 px-3">ID</th>
                            <th scope="col" class="py-3 px-3">Nome</th>
                            <th scope="col" class="py-3 px-3">Preço Afiliado</th>
                            <th scope="col" class="py-3 px-3">Integrar</th>
                            <th scope="col" class="py-3 px-3">Ver Variações</th>
                            <th scope="col" class="py-3 px-3">Edit</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @foreach ($viewData['products'] as $product)
                            <tr class="align-middle" id="linhasProduct">
                                @php
                                    $variations = json_decode($product['variation_data'], true);
                                    $firstImage = $variations[0]['picture_ids'][0] ?? null;
                                    $variationCount = count($variations);
                                @endphp

                                @if ($product->image)
                                    <td style="width: 150px;" class="py-3">
                                        <div class="position-relative d-inline-block">
                                            @if ($firstImage)
                                                <img src="{{ $firstImage }}" alt="Imagem"
                                                    style="width: 80px; height: auto; border-radius: 8px; border: 1px solid #dee2e6;">
                                            @else
                                                <span class="text-muted">Sem imagem</span>
                                            @endif

                                            <span
                                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">
                                                + {{ $variationCount }}
                                            </span>
                                        </div>
                                    @else
                                    <td>
                                        @php
                                            $variationImages = [];
                                            foreach (json_decode($product->variation_data, true) ?? [] as $v) {
                                                foreach ($v['picture_ids'] ?? [] as $pic) {
                                                    if (count($variationImages) < 4) {
                                                        $variationImages[] = $pic;
                                                    }
                                                }
                                            }
                                        @endphp

                                        <div class="position-relative d-inline-block">
                                            <div class="circle-pizza">
                                                @foreach (array_slice($variationImages, 0, 4) as $index => $url)
                                                    <div class="slice slice-{{ $index + 1 }}"
                                                        style="background-image: url('{{ $url }}');"></div>
                                                @endforeach
                                            </div>

                                            <span
                                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">
                                                + {{ $variationCount }}
                                            </span>
                                        </div>
                                    </td>
                                @endif
                                <td class="id_product">{{ $product->getId() }}</td>
                                <td>{{ $product->getName() }}</td>
                                <td>R$: {{ number_format($product->getPriceWithFeeMktplace(), 2) }}</td>
                                <td>
                                    <button class="btn btn-success btn-sm px-3 integrar-btn" style="border-radius: 20px;"
                                        data-bs-toggle="modal" data-bs-target="#exampleModalToggle">
                                        Integrar
                                    </button>
                                </td>
                                <td>
                                    <button class="btn btn-primary btn-sm px-3 ver-variacoes" style="border-radius: 20px;"
                                        data-json="{{ $product->variation_data }}">
                                        Produtos <i class="bi bi-basket"></i>
                                    </button>
                                </td>
                                <td>
                                    <a href="{{ route('products.edit', ['id' => $product->getId()]) }}">
                                        <button class="btn btn-outline-primary btn-sm px-3" style="border-radius: 20px;">
                                            <i class="bi bi-pencil-square"></i> Editar
                                        </button>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <input type="hidden" name="auth" id="auth" value="{{ $viewData['token']->access_token }}">
                <div class="d-flex">
                    {!! $viewData['products']->links() !!}
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="id_user" id="id_user" value="{{ Auth::user()->id }}">
    <input type="hidden" name="total" id="total">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.theme.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.3/jquery.mask.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.ver-variacoes').on('click', function() {
                let data = [];

                try {
                    const jsonRaw = $(this).attr('data-json');
                    const parsed = JSON.parse(jsonRaw);
                    data = Array.isArray(parsed) ? parsed : [parsed];
                } catch (e) {
                    console.error('Erro ao interpretar JSON:', e);
                    $('#modalProdutoBody').html(
                        '<p class="text-danger">Erro ao ler os dados da variação.</p>');
                    return;
                }

                let html = '';
                $.each(data, function(index, variation) {
                    // Pega SKU de attributes
                    let sku = 'Sem SKU';
                    if (variation.attributes && Array.isArray(variation.attributes)) {
                        const attr = variation.attributes.find(a => a.id === 'SELLER_SKU');
                        if (attr && attr.value_name) {
                            sku = attr.value_name;
                        }
                    }

                    const preco = variation.price ? parseFloat(variation.price).toFixed(2) : '0.00';
                    const estoque = variation.available_quantity ?? 'N/D';

                    // Galeria de imagens
                    let imagens = '';
                    if (variation.picture_ids && variation.picture_ids.length > 0) {
                        imagens += '<div class="d-flex flex-wrap gap-2">';
                        $.each(variation.picture_ids, function(i, url) {
                            imagens +=
                                `<img src="${url}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px; border: 1px solid #ccc;">`;
                        });
                        imagens += '</div>';
                    } else {
                        imagens = '<p>Sem imagens</p>';
                    }

                    html += `
                        <div class="card shadow-sm mb-3 w-100">
                            <div class="card-body">
                                <div class="d-flex flex-wrap justify-content-between align-items-start">
                                    <div class="col-md-3">
                                        ${imagens}
                                    </div>
                                    <div class="col-md-9">
                                        <h5 class="mb-2">Variação #${index + 1}</h5>
                                        <p class="mb-1"><strong>SKU:</strong> ${sku}</p>
                                        <p class="mb-1"><strong>Preço:</strong> R$ ${preco}</p>
                                        <p class="mb-1"><strong>Estoque:</strong> ${estoque}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });
                $('#modalProdutoBody').html(html);

                // Abre o modal corretamente (caso esteja usando via JS)
                const modal = new bootstrap.Modal(document.getElementById('exampleModalToggle2'));
                modal.show();
            });
        });

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
                    console.log(response);
                    $("#loading-api").addClass('d-none');
                    if (response) {
                        $('#id_prodenv').val(id_produto); // ID DO PRODUTO
                        // Atualiza os campos com os dados recebidos
                        $("#linkMaterial").attr("href", response.link);
                        $("#total").val(response.priceWithFee);
                        $("#name").val(response.title);
                        $("#precoFinal").val(response.priceWithFee);
                        $("#category_id").val(response.category_id);
                        $(".img_integracao_foto").attr('src', response.image);
                        $(".img_integracao_title").html(response.title);
                        $(".img_integracao_ean").html("EAN: " + response.ean);
                        $(".img_integracao_price").html("Preço: " + response.priceWithFee);
                        $('#editor').val(response.description);

                        // LIMPA VARIAÇÕES EXISTENTES
                        $('#listaVariacoes').empty();
                        const variacoes = JSON.parse(response.variation_data || '[]');

                        variacoes.forEach((variacao, index) => {
                            const imagem = variacao.picture_ids?.[0] || '';
                            const atributos = (variacao.attribute_combinations || []).map(
                                attr => `
                            <div class="row mb-2 atributo-row">
                                <div class="col">
                                    <input type="text" name="variation[${index}][atributos_nome][]" value="${attr.name}" class="form-control atributo-nome" placeholder="Nome" required>
                                </div>
                                <div class="col">
                                    <input type="text" name="variation[${index}][atributos_valor][]" value="${attr.value_name}" class="form-control" placeholder="Valor" required>
                                </div>
                                <div class="col-auto">
                                    <button type="button" class="btn btn-outline-danger btn-sm btn-apagar-atributo" title="Remover">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </div>
                            </div>
                        `).join('');

                            const fotos = (variacao.picture_ids || []).map((url, i) => `
                            <div class="position-relative border rounded p-1">
                                <button type="button" class="btn-close position-absolute top-0 end-0 btn-sm btn-remove-image" aria-label="Remover" data-image-index="${i}"></button>
                                <input type="hidden" name="variation[${index}][picture_ids][]" value="${url}">
                                <img src="${url}" alt="Imagem" style="width: 80px; height: 80px; object-fit: cover; border-radius: 4px;">
                            </div>
                        `).join('');

                            $('#listaVariacoes').append(`
                            <li class="variacao-item border rounded shadow-sm p-3 mb-3" data-index="${index}">
                                <div class="cabecalho d-flex align-items-center gap-3">
                                    ${imagem ? `<img src="${imagem}" style="width: 80px; height: 80px; object-fit: cover; border-radius: 6px; border: 1px solid #ccc;">` : ''}
                                    <h5 class="m-0">Variação #${index + 1}</h5>
                                </div>
                                <div class="conteudo-variacao mt-3">

                                    <div class="text-end mt-2">
                                        <button type="button" class="btn btn-danger btn-sm btn-excluir-variacao">
                                            <i class="bi bi-trash"></i> Excluir Variação
                                        </button>
                                    </div>

                                    <div class="d-flex flex-wrap gap-2 mt-3 picture-group" data-index="${index}">
                                        ${fotos}
                                    </div>

                                    <input type="hidden" name="variation[${index}][sku]" value="${variacao.attributes?.[0]?.value_name || ''}">
                                    <input type="hidden" name="variation[${index}][nome]" value="${variacao.attribute_combinations?.map(a => a.value_name).join(' ') || ''}">

                                    <div class="mb-2 mt-3 d-none">
                                        <label>Preço:</label>
                                        <input type="number" step="0.01" class="form-control mt-2 price-var" name="variation[${index}][price]" value="${variacao.price || 0}" readonly>
                                    </div>

                                    <div class="mb-2">
                                        <label>Estoque:</label>
                                        <input type="number" class="form-control" name="variation[${index}][available_quantity]" value="${variacao.available_quantity || 0}" readonly>
                                    </div>

                                    <div class="atributos-list">${atributos}</div>
                                </div>
                            </li>
                        `);
                        });
                    }
                },
                error: function(error) {
                    $('#result').html('<option> Produto Digitado Não Existe! </option>');
                }
            });
        });


        $('#formIntegracao').on('submit', function(e) {
            const variations = [];

            $('#listaVariacoes .variacao-item').each(function() {
                const $item = $(this);
                const index = $item.data('index');

                const sku = $item.find(`input[name="variation[${index}][sku]"]`).val();
                const price = parseFloat($item.find(`input[name="variation[${index}][price]"]`).val());
                const quantity = parseInt($item.find(
                    `input[name="variation[${index}][available_quantity]"]`).val());

                const picture_ids = [];
                $item.find(`input[name="variation[${index}][picture_ids][]"]`).each(function() {
                    picture_ids.push($(this).val());
                });

                const attr_nomes = $item.find(`input[name="variation[${index}][atributos_nome][]"]`);
                const attr_valores = $item.find(`input[name="variation[${index}][atributos_valor][]"]`);
                const attribute_combinations = [];

                for (let i = 0; i < attr_nomes.length; i++) {
                    attribute_combinations.push({
                        name: $(attr_nomes[i]).val(),
                        value_name: $(attr_valores[i]).val()
                    });
                }

                variations.push({
                    attributes: [{
                        id: "SELLER_SKU",
                        value_name: sku
                    }],
                    attribute_combinations,
                    price,
                    available_quantity: quantity,
                    picture_ids
                });
            });

            // Debug opcional
            console.log(JSON.stringify(variations, null, 2));

            // Preenche o campo oculto
            $('#variacoes_json').val(JSON.stringify(variations));
        });

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

            let ultimoValor = null;

            setInterval(function () {
                let atual = $('#valorProdutoDisplay').val();
                if (atual !== ultimoValor) {
                    ultimoValor = atual;
                    let valorNumerico = atual.replace(',', '.');
                    $('.price-var').val(valorNumerico);
                }
            }, 300); // Verifica a cada 300ms


            // Eventos
            valorAgregadoInput.addEventListener('input', atualizarValorProduto);
            valorAgregadoSelect.addEventListener('change', atualizarValorProduto);
            precoFixoInput.addEventListener('input', atualizarValorProduto);

            $('input[name="preco"]').mask('000.000.000,00', {
                reverse: true
            });


             var token = $('#auth').val();

             $(document).ready(function() {
            var valorProduto = 0;
            var i = 0;

            var token = $('#auth').val();


            $.ajax({
                url: "/meli/categories",
                type: "GET",
                success: function(response) {

                    if (response) {
                        // SHOW ALL RESULT QUERY
                        var index = [];
                        $.each(response, function(i, item) {
                            index[i] = '<option class="option-size" value=' + item.id + '>' +
                                item.name + '</option>';
                        });

                        if (i == 0) {
                            // PEGA A ALTERACAO DAS CATEGORIAS
                            $("#categorias").change(function() {
                                var ids = $(this).children("option:selected").val();
                                var name = $(this).children("option:selected").text();
                                var content_category = '<li class="list-group-item">' + name +
                                    '</li>';
                                $(".content_categorias").append(content_category);
                                $("#id_categoria").val(
                                    ids); // COLOCA O ID DA CATEGORIA NO CAMPO
                                getCategory(ids);
                            });
                        }

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
                     url: "/meli/subcategories/" + category,
                    type: "GET",
                    success: function(response) {
                        if (response) {
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
                                     url: "/meli/subcategories/attributes/" + category,
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
        });
    </script>

@endsection
