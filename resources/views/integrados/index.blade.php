@extends('layouts.app')
@section('title', $viewData['title'])
@section('conteudo')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Label fixo no topo */
        .sticky-label {
            position: sticky;
            /* Fixa o elemento no topo */
            top: 0;
            /* Fica sempre no topo do modal */
            z-index: 1050;
            /* Garante que o label fique acima dos demais elementos */
            background-color: #17a2b8;
            /* Cor de fundo (azul Bootstrap) */
            color: white;
            /* Cor do texto */
            text-align: center;
            font-weight: bold;
            font-size: 16px;
            padding: 10px;
            border-radius: 5px;
        }

        .offcanvas {
            width: 800px;
            /* Largura do modal lateral */
        }

        /* Estilo para animação de hover */
        .hover-row:hover {
            background-color: #f8f9fa;
            /* Cinza claro */
            transition: background-color 0.3s ease;
        }

        /* Botão com animação suave */
        .btn-outline-primary {
            transition: all 0.3s ease;
        }

        .btn-outline-primary:hover {
            background-color: #0d6efd;
            color: white;
        }

        /* Imagem estilizada */
        img.rounded-circle {
            transition: transform 0.3s ease;
        }

        img.rounded-circle:hover {
            transform: scale(1.1);
        }

        /* Card estilizado */
        .card {
            border-radius: 10px;
            overflow: hidden;
        }

        .card-header {
            font-weight: bold;
            font-size: 1.2rem;
        }

        /* Ajusta a tabela para não estourar */
        .table {
            margin-bottom: 0;
            /* border-radius: 10px; */
            overflow: hidden;
        }

        .circle-pizza {
            position: relative;
            width: 60px;
            height: 60px;
            border-radius: 50%;
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


    <div class="container-fluid px-4">
        <h2 class="mt-4">Produtos Integrados</h2>

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
            </div>
        @endif

        <!-- Modal Lateral -->
        <div class="offcanvas offcanvas-end" tabindex="-1" id="editModal" aria-labelledby="editModalLabel">
            <div class="offcanvas-header">
                <!-- Label fixo para mostrar o valor -->
                {{-- <div id="valorProdutoLabel" class="sticky-label bg-info text-white py-2 px-3 rounded">
                    Valor do Produto: R$ <span id="valorProdutoDisplay">0,00</span>
                </div> --}}
                <h5 id="editModalLabel">Editar Produto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <div class="mb-3">
                    <img id="editImage" src="" alt="Imagem do Produto" class="img-fluid rounded mb-2"
                        style="width: 100px;">
                </div>
                <form id="editForm" method="POST" action="{{ route('productIntegrado') }}">
                    @csrf <!-- Proteção CSRF -->
                    <input type="hidden" id="editId" name="id">

                    <div class="mb-3">
                        <label for="editName" class="form-label">Nome</label>
                        <input type="text" readonly class="form-control" id="editName" name="name">
                    </div>

                    @csrf
                    <!-- Select para Valor Agregado -->
                    <div class="mb-3">
                        <label for="editValorAgregado" class="form-label">Valor Agregado</label>
                        <select id="editValorAgregado" class="form-select" name="valor_tipo">
                            <option value="">Selecione uma opção</option>
                            <option value="acrescimo_reais">Acréscimo R$</option>
                            <option value="acrescimo_porcentagem">Acréscimo %</option>
                            <option value="desconto_reais">Desconto R$</option>
                            <option value="desconto_porcentagem">Desconto %</option>
                        </select>
                    </div>

                    <!-- Input para o valor agregado -->
                    <div class="mb-3">
                        <label for="valorAgregadoInput" class="form-label">Valor</label>
                        <input type="text" class="form-control" id="valorAgregadoInput" name="valor_agregado"
                            placeholder="Digite o valor" value="0">
                    </div>

                    <!-- Campos adicionais apenas para leitura -->
                    <div class="mb-3">
                        <label for="editAcrescimoReais" class="form-label">Acréscimo (R$)</label>
                        <input type="text" class="form-control" id="editAcrescimoReais" disabled>
                    </div>

                    <div class="mb-3">
                        <label for="editAcrescimoPorcentagem" class="form-label">Acréscimo (%)</label>
                        <input type="text" class="form-control" id="editAcrescimoPorcentagem" disabled>
                    </div>

                    <div class="mb-3">
                        <label for="editDescontoReais" class="form-label">Desconto (R$)</label>
                        <input type="text" class="form-control" id="editDescontoReais" disabled>
                    </div>

                    <div class="mb-3">
                        <label for="editDescontoPorcentagem" class="form-label">Desconto (%)</label>
                        <input type="text" class="form-control" id="editDescontoPorcentagem" disabled>
                    </div>

                    <div class="mb-3">
                        <label for="editPrecoFixo" class="form-label">Preço Fixo</label>
                        <input type="text" class="form-control" id="editPrecoFixo" disabled>
                    </div>

                    <div class="mb-3">
                        <label for="editCreated" class="form-label">Criado em</label>
                        <input type="text" class="form-control" id="editCreated" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="editIntegracao" class="form-label">Integração</label>
                        <input type="text" class="form-control" id="editIntegracao" readonly>
                    </div>
                    <!-- Checkbox para preço fixo -->
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="precoFixoCheckbox" name="precoFixo">
                        <label class="form-check-label" for="precoFixoCheckbox">Ativar Preço Fixo</label>
                    </div>

                    <!-- Input para Preço Fixo -->
                    <div class="mb-3">
                        <label for="precoFixoInput" class="form-label">Preço Fixo</label>
                        <input type="text" class="form-control" id="precoFixoInput" name="precoFixo"
                            placeholder="Digite o preço fixo" disabled>
                    </div>


                    <!-- Outros campos do formulário -->

                    <div class="row">
                        <!-- Select para o campo 'active' -->
                        <div class="col-md-6">
                            <label for="active" class="form-label">Status do Produto</label>
                            <select id="active" name="active" class="form-select">
                                <option value="1">Ativo</option>
                                <option value="0">Inativo</option>
                            </select>
                        </div>

                        <!-- Input para o campo 'estoque_minimo' -->
                        <div class="col-md-6">
                            <label for="estoque_minimo" class="form-label">Estoque Mínimo</label>
                            <input type="number" id="estoque_minimo" name="estoque_minimo" class="form-control"
                                placeholder="Digite o estoque mínimo" min="0" value="0">
                        </div>
                    </div>

                    <!-- Hidden input para isPorcem -->
                    <input type="hidden" id="isPorcem" name="isPorcem" value="0">

                    <button type="submit" class="btn btn-success mt-4">Salvar Alterações</button>
                </form>

            </div>
        </div>

        <div class="card mt-2">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Lista de Produtos</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover table-striped align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Nome</th>
                                <th scope="col">Variação</th>
                                <th scope="col">Imagem</th>
                                <th scope="col">Integrações</th>
                                <th scope="col">ID Loja</th>
                                <th scope="col">Criado</th>
                                <th scope="col">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <pre>
                            @foreach ($viewData['products'] as $product)

                                <tr class="hover-row">
                                    <td class="fw-bold">{{ $product->id }}</td>
                                    <th>{{$product->name}}</th>
                                    <td><span class="badge text-bg-{{ $product->isVariation ? 'success' : 'warning' }}">{{ $product->isVariation ? "SIM" : "NÃO" }}</span></td>
                                    @if ($product->image)
                                        <td>
                                            <img src="{!! Storage::disk('s3')->url('produtos/' . $product->product_id . '/' . $product->image) !!}" alt="{{ $product->image }}"
                                                class="rounded-circle border border-secondary shadow-sm"
                                                style="width: 60px; height: 60px; object-fit: cover;">
                                        </td>
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

                                            <div class="circle-pizza">
                                                @foreach (array_slice($variationImages, 0, 4) as $index => $url)
                                                    <div class="slice slice-{{ $index + 1 }}"
                                                        style="background-image: url('{{ $url }}');"></div>
                                                @endforeach
                                            </div>
                                        </td>
                                    @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-success">{{ $product->id_mercadolivre }}</span>
                                    </td>
                                    <td>{{ $product->product_id }}</td>
                                    <td>{{ $product->created_at }}</td>
                                    <td>

                                        <button class="btn btn-sm btn-outline-primary edit-btn"
                                            data-id="{{ $product->id }}" data-name="{{ $product->name }}"
                                            data-price="{{ $product->priceNotFee }}"
                                            data-image="{{ Storage::disk('s3')->url('produtos/' . $product->product_id . '/' . $product->image) }}"
                                            data-id-loja="{{ $product->product_id }}"
                                            data-created="{{ $product->created_at }}"
                                            data-integracao="{{ $product->id_mercadolivre ?? '' }}"
                                            data-acrescimo-reais="{{ $product->acrescimo_reais ?? '' }}"
                                            data-acrescimo-porcentagem="{{ $product->acrescimo_porcentagem ?? '' }}"
                                            data-desconto-reais="{{ $product->desconto_reais ?? '' }}"
                                            data-desconto-porcentagem="{{ $product->desconto_porcentagem ?? '' }}"
                                            data-active="{{ $product->active }}"
                                            data-precofixo="{{ $product->precofixo ?? '' }}"
                                            data-estoque_minimo="{{ $product->estoque_minimo }}">
                                            <i class="fas fa-edit"></i> Editar
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer text-center">
                    <nav aria-label="Pagination">
                        <ul class="pagination justify-content-center mb-0">
                            {!! $viewData['products']->links() !!}
                        </ul>
                    </nav>
                </div>
            </div>
        </div>

    @endsection

    <script>
        document.addEventListener('DOMContentLoaded', function() {


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

            const editModal = document.getElementById('editModal');
            if (editModal) {
                editModal.style.width = '800px';
            }
            const editButtons = document.querySelectorAll('.edit-btn');

            editButtons.forEach(button => {
                button.addEventListener('click', function() {

                    // Extraindo valores dos atributos data-*
                    const id = button.getAttribute('data-id');
                    const name = button.getAttribute('data-name');
                    const price = button.getAttribute('data-price');
                    const image = button.getAttribute('data-image');
                    const created = button.getAttribute('data-created');
                    const integracao = button.getAttribute('data-integracao');
                    const acrescimoReais = button.getAttribute('data-acrescimo-reais');
                    const acrescimoPorcentagem = button.getAttribute('data-acrescimo-porcentagem');
                    const descontoReais = button.getAttribute('data-desconto-reais');
                    const descontoPorcentagem = button.getAttribute('data-desconto-porcentagem');
                    const precoFixo = button.getAttribute('data-precofixo');
                    const activeStatus = this.getAttribute('data-active');
                    const estoqueMinimo = this.getAttribute('data-estoque_minimo');

                    basePrice = parseFloat(button.getAttribute('data-price')) ||
                        0; // Preço base do produto
                    // Preenchendo os campos do modal

                    document.getElementById('editId').value = id;
                    document.getElementById('editName').value = name;
                    document.getElementById('editImage').src = image; // Se tiver uma tag <img>
                    document.getElementById('editCreated').value = created;
                    document.getElementById('editIntegracao').value = integracao;
                    // Preenchendo os valores adicionais
                    document.getElementById('editAcrescimoReais').value = acrescimoReais || '0.00';
                    document.getElementById('editAcrescimoPorcentagem').value =
                        acrescimoPorcentagem || '0.00';
                    document.getElementById('editDescontoReais').value = descontoReais || '0.00';
                    document.getElementById('editDescontoPorcentagem').value =
                        descontoPorcentagem || '0.00';
                    document.getElementById('editPrecoFixo').value = precoFixo || '0.00';
                    // Define o valor do campo 'active'
                    document.getElementById('active').value = activeStatus;
                    document.getElementById('estoque_minimo').value = estoqueMinimo;

                    // Abre o modal
                    const editModal = new bootstrap.Offcanvas(document.getElementById('editModal'));
                    editModal.show();
                });
            });



            // Função para atualizar o valor exibido
            function atualizarValorProduto() {
                const tipoAgregado = valorAgregadoSelect.value; // Opção selecionada (R$ ou %)
                const valorAgregado = parseFloat(valorAgregadoInput.value) || 0;
                const precoFixo = parseFloat(precoFixoInput.value) || 0;

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
                valorProdutoDisplay.innerText = novoValor.toFixed(2).replace('.', ',');
            }

            // Eventos
            valorAgregadoInput.addEventListener('input', atualizarValorProduto);
            valorAgregadoSelect.addEventListener('change', atualizarValorProduto);
            precoFixoInput.addEventListener('input', atualizarValorProduto);
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.theme.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.3/jquery.mask.min.js"></script>
