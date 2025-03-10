@extends('layouts.app')
@section('conteudo')
    <style>
        #loading-api {
            /* Garante que o loading fique oculto ao carregar a página */
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            /* Opacidade para escurecer o fundo */
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            /* Mantém sobre todos os elementos */
        }

        /* Estilização da Galeria */
        .gallery-container {
            display: flex;
            flex-wrap: nowrap;
            /* Impede que os itens quebrem para a próxima linha */
            gap: 10px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background: #f9f9f9;
            overflow-x: auto;
            /* Adiciona a rolagem horizontal */
            white-space: nowrap;
            /* Mantém os itens na mesma linha */
            scrollbar-width: thin;
            /* Para Firefox */
            scrollbar-color: #888 #f1f1f1;
            /* Personaliza a cor da barra de rolagem */
        }


        .gallery-item {
            position: relative;
            width: 120px;
            height: 120px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border: 2px solid #ccc;
            border-radius: 5px;
            background: #fff;
            cursor: grab;
            transition: all 0.3s;
        }

        .gallery-item img {
            max-width: 100%;
            max-height: 100%;
            object-fit: cover;
            border-radius: 5px;
        }

        .gallery-item a {
            position: absolute;
            bottom: 5px;
            background: red;
            color: white;
            padding: 5px;
            font-size: 12px;
            text-decoration: none;
            border-radius: 3px;
            cursor: pointer;
        }

        .dragging {
            opacity: 0.5;
        }

        /* Rótulo "Imagem Principal" */
        .main-image-label {
            position: absolute;
            top: -10px;
            background: #007bff;
            color: white;
            padding: 3px 6px;
            font-size: 14px;
            border-radius: 3px;
        }


        /* Placeholder visível para evitar "pulos" */
        .sortable-placeholder {
            border: 2px dashed #007bff;
            background: rgba(0, 123, 255, 0.1);
            width: 120px;
            height: 120px;
            visibility: visible !important;
        }

        #imagePreview {
            display: flex;
            flex-wrap: nowrap;
            /* Permite apenas rolagem horizontal */
            gap: 10px;
            overflow-x: auto;
            /* Scroll horizontal */
            overflow-y: hidden;
            /* Remove o scroll vertical */
            padding: 5px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
        }

        #imagePreview .image-item {
            flex: 0 0 auto;
            width: 120px;
            /* Largura fixa */
            height: 120px;
            /* Altura fixa */
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            /* Esconde partes que ultrapassam o contêiner */
            border: 1px solid #ddd;
            border-radius: 5px;
            position: relative;
            /* Necessário para rótulos e botões */
            background-color: #fff;
        }

        #imagePreview .image-item img {
            max-width: 100%;
            max-height: 100%;
            object-fit: cover;
            /* Mantém as proporções da imagem */
            border-radius: 5px;
        }

        #imagePreview::-webkit-scrollbar {
            height: 8px;
            /* Largura da barra de rolagem horizontal */
        }

        #imagePreview::-webkit-scrollbar-thumb {
            background-color: #bbb;
            /* Cor da barra de rolagem */
            border-radius: 4px;
            /* Bordas arredondadas */
        }

        #imagePreview::-webkit-scrollbar-thumb:hover {
            background-color: #999;
            /* Cor ao passar o mouse */
        }

        .delete-button {
            position: absolute;
            top: -10px;
            right: 5px;
            background-color: #dc3545;
            color: #fff;
            border: none;
            padding: 5px;
            font-size: 12px;
            border-radius: 50%;
            cursor: pointer;
        }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="auth-user-id" content="{{ Auth::user()->id }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jsoneditor/9.10.0/jsoneditor.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jsoneditor/9.10.0/jsoneditor.min.js"></script>

    <script>
        function serializeFormData() {
            let inputs = document.querySelectorAll("#formContainer input, #formContainer select");
            let formContainer = document.getElementById("formContainer");
            // Criamos um objeto para armazenar os valores
            let formData = {};
            let jsonData = [];
            // Percorre todos os inputs e selects dentro do formContainer
            formContainer.querySelectorAll("input, select").forEach(field => {
                let fieldData = {
                    id: field.name, // Nome do campo
                    value: field.value, // Valor selecionado/preenchido
                    values: [{
                        id: field.value, // O ID da opção selecionada ou valor do input
                        name: field.value // Nome igual ao valor preenchido
                    }]
                };
                // Adiciona o objeto formatado ao array
                jsonData.push(fieldData);
            });


            inputs.forEach(input => {
                if (input.tagName === "SELECT") {
                    // Define a opção correta como "selected"
                    let selectedOption = input.options[input.selectedIndex];
                    if (selectedOption) {
                        selectedOption.setAttribute("selected", "selected");
                    }
                } else {
                    // Adiciona o atributo "value" nos inputs para salvar corretamente
                    input.setAttribute("value", input.value);
                }
            });

            // Salva o JSON no input hidden
            document.getElementById("atributos_json").value = JSON.stringify(jsonData);
            // Captura o HTML atualizado com os valores preenchidos
            let formContainerHTML = document.getElementById("formContainer").innerHTML;
            // Salva no input hidden antes do envio
            document.getElementById("atributos_html").value = formContainerHTML;
        }



        function serializeFormHTML() {
            let inputs = document.querySelectorAll("#formContainer input, #formContainer select");

            inputs.forEach(input => {
                if (input.tagName === "SELECT") {
                    // Define a opção correta como "selected"
                    let selectedOption = input.options[input.selectedIndex];
                    if (selectedOption) {
                        selectedOption.setAttribute("selected", "selected");
                    }
                } else {
                    // Adiciona o atributo "value" nos inputs para salvar corretamente
                    input.setAttribute("value", input.value);
                }
            });

            // Captura o HTML atualizado com os valores preenchidos
            let formContainerHTML = document.getElementById("formContainer").innerHTML;

            // Verifica se os valores foram corretamente incluídos
            console.log("HTML atualizado antes do envio:", formContainerHTML);

            // Salva no input hidden antes do envio
            document.getElementById("atributos_html").value = formContainerHTML;
        }



        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput'); // Campo de pesquisa
            const searchButton = document.getElementById('searchButton'); // Botão de pesquisa
            const searchResults = document.getElementById(
                'searchResults'); // Div para exibir os resultados da pesquisa

            searchButton.addEventListener('click', function(event) {
                event.preventDefault(); // Evita o envio do formulário, se houver

                const searchTerm = searchInput.value.trim(); // Valor do campo de pesquisa
                if (!searchTerm) {
                    searchResults.innerHTML = '<p class="text-danger">Digite um termo para buscar.</p>';
                    return;
                }

                // Alterar o botão para o estado de carregamento
                searchButton.disabled = true;
                const originalText = searchButton.innerHTML;
                searchButton.innerHTML =
                    `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Carregando...`;

                fetch(`/api/v1/products/search?q=${encodeURIComponent(searchTerm)}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Erro na requisição');
                        }
                        return response.json();
                    })
                    .then(data => {
                        // Limpa o conteúdo anterior
                        searchResults.innerHTML = '';

                        if (data.products.data.length > 0) {
                            data.products.data.forEach(product => {
                                const productItem = `
                        <div class="product-item border-bottom mb-2 pb-2 d-flex align-items-center">
                            <!-- Imagem do produto -->
                            <img src="${product.imagem_url}" alt="${product.title}" class="rounded me-3" style="width: 80px; height: 80px; object-fit: cover;">

                            <!-- Informações do produto -->
                            <div class="flex-grow-1">
                                <h6 class="mb-1">${product.title}</h6>
                                <span class="text-muted">Preço: R$${product.priceKit.toFixed(2)}</span>
                                <span class="text-muted ms-3">Estoque Disponível: ${product.available_quantity}</span>

                                <!-- Campo de entrada para a quantidade desejada (opcional) -->
                                <div class="mt-2">
                                    <input type="hidden" id="stock-${product.id}" class="form-control form-control-sm w-25" min="1" max="${product.available_quantity}" value="1">
                                </div>
                            </div>

                            <!-- Botão para adicionar ao kit -->
                           <button id="btnAddToKit-${product.id}" class="btn btn-primary btn-sm ms-auto" type="button" onclick="addProductToKit(${product.id})">
                                Adicionar ao Kit
                            </button>

                        </div>
                        `;
                                searchResults.innerHTML += productItem;
                            });
                        } else {
                            searchResults.innerHTML =
                                '<p class="text-muted">Nenhum produto encontrado.</p>';
                        }
                    })
                    .catch(error => {
                        console.error('Erro ao buscar produtos:', error);
                        searchResults.innerHTML =
                            '<p class="text-danger">Erro ao buscar produtos. Tente novamente mais tarde.</p>';
                    })
                    .finally(() => {
                        // Restaura o botão para o estado normal
                        searchButton.disabled = false;
                        searchButton.innerHTML = originalText;
                    });
            });
        });

        function addProductToKit(productId) {
            // Seleciona o botão pelo id
            const button = document.getElementById("btnAddToKit-" + productId);
            // Guarda o texto original do botão
            const originalText = button.innerHTML;

            // Define o botão para estado de loading
            button.disabled = true;
            button.innerHTML =
                '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Carregando...';

            // Obtém os dados necessários
            const userId = document.querySelector('meta[name="auth-user-id"]').content;
            const productHiddenField = document.querySelector('input[name="product_id"]'); // ID do produto sendo editado
            const productEditingId = productHiddenField.value;
            const csrfTokenElement = document.querySelector('meta[name="csrf-token"]');
            const csrfToken = csrfTokenElement ? csrfTokenElement.content : null;

            // Monta os dados para enviar
            const data = {
                product_id: productEditingId,
                id_product_kit: productId,
                quantity: 1,
                user_id: userId
            };

            // Envia a requisição com fetch
            fetch('/api/v1/kitsAddProduct', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify(data)
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erro na requisição');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        alert(`Produto ID ${productId} adicionado ao kit com sucesso.`);
                        window.location.reload();
                    } else {
                        alert(`Erro ao adicionar produto ao kit: ${data.message}`);
                    }
                })
                .catch(error => {
                    console.error('Erro ao adicionar produto ao kit:', error);
                    alert('Erro ao adicionar produto ao kit. Tente novamente mais tarde.');
                })
                .finally(() => {
                    // Restaura o botão para o estado normal
                    button.disabled = false;
                    button.innerHTML = originalText;
                });
        }

        // Adicionar produto ao kit
        document.getElementById('addProductBtn').addEventListener('click', function() {
            const selectedProduct = productResults.querySelector('.active');

            if (selectedProduct) {
                const productId = selectedProduct.dataset.productId;
                const productPrice = parseFloat(selectedProduct.dataset.productPrice);
                const productTitle = selectedProduct.textContent;

                // Adicionar o produto na lista do kit
                const li = document.createElement('li');
                li.className = 'd-flex align-items-center mb-3 p-3 border rounded shadow-sm';
                li.innerHTML = `
                <input class="form-check-input me-2 kit-checkbox" type="checkbox" value="${productPrice}" name="products[${productId}][id]" checked>
                <div class="flex-grow-1">
                    <h6 class="mb-1">${productTitle}</h6>
                    <span class="text-muted">Preço: R$${productPrice.toFixed(2)}</span>
                </div>
            `;
                kitItemList.appendChild(li);

                // Fechar o modal
                const addProductModal = bootstrap.Modal.getInstance(document.getElementById('addProductModal'));
                addProductModal.hide();
            }
        });

        // Ativar item na lista de resultados ao clicar
        productResults.addEventListener('click', function(event) {
            if (event.target.classList.contains('list-group-item')) {
                document.querySelectorAll('.list-group-item').forEach(item => item.classList.remove('active'));
                event.target.classList.add('active');
            }
        });


        document.addEventListener('DOMContentLoaded', function() {
            const confirmChangesModalElement = document.getElementById('confirmChangesModal');
            const confirmChangesModal = new bootstrap.Modal(confirmChangesModalElement);
            // Seleciona os checkboxes e o elemento do total
            const checkboxes = document.querySelectorAll('.kit-checkbox');
            const totalKitElement = document.getElementById('totalKit');

            // Função para recalcular o total
            function updateTotal() {
                let total = 0; // Inicializa o total em 0
                checkboxes.forEach(checkbox => {
                    if (checkbox.checked) {
                        total += parseFloat(checkbox.value); // Soma o valor dos checkboxes marcados
                    }
                });
                // Atualiza o total no elemento HTML
                totalKitElement.textContent = total.toLocaleString('pt-BR', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }

            // Adiciona evento de mudança a cada checkbox
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateTotal);
            });

            const form = document.querySelector('form');
            let changesMade = false;

            // Exemplo de como disparar o modal em caso de alterações
            form.addEventListener('submit', function(event) {
                if (changesMade) {
                    event.preventDefault(); // Impedir envio do formulário
                    confirmChangesModal.show(); // Exibir o modal de confirmação
                }
            });

            // Confirmar envio do formulário
            document.getElementById('confirmSubmit').addEventListener('click', function() {
                changesMade = false; // Resetar alterações
                form.submit(); // Submeter o formulário
            });

            // Atualiza o total ao carregar a página (caso necessário)
            updateTotal();
        });


        document.addEventListener('DOMContentLoaded', function() {

            const kitAlert = document.getElementById('kitAlert');
            const confirmChangesModal = new bootstrap.Modal(document.getElementById('confirmChangesModal'));
            const form = document.querySelector('form');
            const checkboxes = document.querySelectorAll('input[type="checkbox"][name^="products"]');
            let changesMade = false; // Flag para verificar alterações

            // Detectar mudanças nos checkboxes
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', () => {
                    changesMade = true; // Marcar que houve alterações
                    kitAlert.classList.remove('d-none'); // Mostrar alerta
                    kitAlert.classList.add('show');

                    // Fechar o alerta automaticamente após 2 segundos
                    setTimeout(() => {
                        kitAlert.classList.remove('show');
                        kitAlert.classList.add('d-none');
                    }, 4000);
                });
            });


        });

        document.addEventListener('DOMContentLoaded', function() {
            const kitAlert = document.getElementById('kitAlert');
            const removedAlert = document.getElementById('removedAlert');
            const removedMessage = document.getElementById('removedMessage');
            const checkboxes = document.querySelectorAll('input[type="checkbox"][name^="products"]');
            const confirmChangesModal = new bootstrap.Modal(document.getElementById('confirmChangesModal'));
            const form = document.querySelector('form');
            let changesMade = false; // Flag para verificar alterações

            // Detectar mudanças nos checkboxes
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', () => {
                    const productId = checkbox.value;
                    const productPrice = parseFloat(checkbox.closest('li').querySelector(
                        'input[name$="[priceKit]"]').value);
                    const productTitle = checkbox.closest('li').querySelector('h6')
                        .textContent; // Captura o título do produto

                    if (checkbox.checked) {
                        // Produto marcado (adição)
                        changesMade = true;
                        kitAlert.classList.remove('d-none');
                        kitAlert.classList.add('show');
                    } else {
                        // Produto desmarcado (remoção)
                        changesMade = true;

                        // Mostrar alerta lateral de remoção
                        removedAlert.classList.remove('d-none');
                        removedAlert.classList.add('show');
                        removedMessage.textContent =
                            `Produto "${productTitle}" foi removido. Valor: - R$ ${productPrice.toFixed(2)}`;

                        // Esconder o alerta após 2 segundos
                        setTimeout(() => {
                            removedAlert.classList.remove('show');
                            removedAlert.classList.add('d-none');
                        }, 4000);
                    }

                    updateTotal(); // Recalcular o total
                });
            });


        });

        // Fechar o alerta manualmente
        removedAlert.querySelector('.btn-close').addEventListener('click', () => {
            removedAlert.classList.add('d-none');
        });

        // Atualizar o total
        function updateTotal() {
            let total = 0;
            checkboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    const price = parseFloat(checkbox.closest('li').querySelector('input[name$="[priceKit]"]')
                        .value);
                    total += price;
                }
            });
            document.getElementById('totalValue').textContent = `Total: R$ ${total.toFixed(2)}`;
        }

        // Inicializar o cálculo do total
        updateTotal();
    </script>
    <style>
        .alert {
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>


    <div class="card mb-4">
        <div class="card-header">
            Editar Produto
        </div>

        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>{{ session('success') }}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif


            @if (session('message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>{{ session('message') }}</strong>
                    @if (session('removed_products') && count(session('removed_products')) > 0)
                        <ul>
                            @foreach (session('removed_products') as $product)
                                <li>{{ $product }}</li>
                            @endforeach
                        </ul>
                    @endif
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!--- FINAL DO MODAL ---->
            <div id="liveAlertPlaceholder"></div>

            <div class="position-fixed end-0 p-3"
                style="top: 0; z-index: 1050; display: flex; flex-direction: column; gap: 10px;">
                <!-- Alerta de remoção -->
                <div id="removedAlert" class="alert alert-info alert-dismissible fade d-none shadow" role="alert">
                    <button type="button" class="btn-close" aria-label="Close"></button>
                    <span id="removedMessage"></span>
                </div>

                <!-- Alerta de modificação -->
                <div id="kitAlert" class="alert alert-warning alert-dismissible fade d-none" role="alert">
                    O kit foi alterado. Verifique as mudanças antes de salvar.
                    <button type="button" class="btn-close" aria-label="Close"></button>
                </div>
            </div>

            @if (isset($viewData['product']->isKit) && $viewData['product']->isKit)
                <div class="accordion" id="productFormAccordion">
                    <div class="accordion-item shadow-sm border-0">
                        <h2 class="accordion-header" id="headingKit">
                            <button class="accordion-button bg-white text-dark collapsed" type="button"
                                data-bs-toggle="collapse" data-bs-target="#collapseKit" aria-expanded="false"
                                aria-controls="collapseKit" style="font-weight: 600;">
                                <i class="bi bi-box-seam me-2"></i> Informações do Kit
                            </button>
                        </h2>
                        <div id="collapseKit" class="accordion-collapse collapse" aria-labelledby="headingKit"
                            data-bs-parent="#productFormAccordion">
                            <div class="accordion-body">
                                <!-- Botão para abrir o modal de adicionar produtos -->
                                <div class="mb-3 text-end">
                                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#addProductModal">
                                        <i class="bi bi-plus-circle me-1"></i> Adicionar Novo Produto
                                    </button>
                                </div>
                                <div class="row mb-3">
                                    <ul class="list-unstyled" id="kitItemList">
                                        @php
                                            $totalPrice = 0; // Variável para armazenar o total
                                            $fee = 0;
                                        @endphp

                                        @foreach ($viewData['kitProducts']['kitItems'] as $product)
                                            @php
                                                $fee += $product->available_quantity * $product->fee;
                                                $totalPrice += $product->priceKit * $product->available_quantity; // Soma o preço do item ao total
                                            @endphp
                                            <li class="d-flex align-items-center mb-3 p-3 border rounded shadow-sm">
                                                <!-- Checkbox -->
                                                <input class="form-check-input me-2 kit-checkbox" type="checkbox"
                                                       value="{{ $product->id }}" id="product-{{ $product->id }}"
                                                       name="products[{{ $product->id }}][id]" checked>

                                                <!-- Imagem do Produto -->
                                                <img src="{!! Storage::disk('s3')->url('produtos/' . $product->id . '/' . $product->image) !!}"
                                                     alt="{{ $product->title }}"
                                                     class="rounded me-3"
                                                     style="width: 80px; height: 80px; object-fit: cover;">

                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">{{ $product->title }}</h6>
                                                    <span class="text-muted">Preço:
                                                        R${{ number_format($product->priceKit, 2, ',', '.') }}</span>
                                                    <span class="text-muted ms-3">Quantidade Selecionada:
                                                        {{ $product->available_quantity }}</span>

                                                    <!-- Formulário para atualizar quantidade -->
                                                    <form action="{{ route('updateQuantidadeNoKit', ['productId' => $product->product_id, 'kitId' => $product->id_product_kit]) }}"
                                                          method="POST" class="update-quantity-form mt-2">
                                                        @csrf
                                                        <div class="input-group input-group-sm" style="width: 150px;">
                                                            <input name="stock" id="stock-{{ $product->id }}"
                                                                   value="{{ $product->available_quantity ?? 1 }}"
                                                                   type="number" class="form-control">
                                                            <button type="submit" id="btnSalvarQuantidade{{ $product->id }}" class="btn btn-success">
                                                                <i class="bi bi-arrow-clockwise me-1"></i>
                                                            </button>
                                                        </div>
                                                    </form>

                                                    <!-- Botão Delete para remover o produto do kit -->
                                                    <form action="{{ route('kits.deleteProduct', ['productId' => $product->product_id, 'kitId' => $product->id_product_kit]) }}"
                                                          method="POST"
                                                          class="mt-2"
                                                          onsubmit="return confirm('Tem certeza que deseja remover este produto do kit?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm">
                                                            <i class="bi bi-trash"></i> Deletar
                                                        </button>
                                                    </form>

                                                    <!-- Inputs ocultos para dados adicionais -->
                                                    <input type="hidden" name="products[{{ $product->id }}][priceKit]"
                                                           value="{{ $product->priceKit }}">
                                                    <input type="hidden" name="products[{{ $product->id }}][quantity]" value="1">
                                                    <input type="hidden" name="products[{{ $product->id }}][available_quantity]"
                                                           value="{{ $product->available_quantity }}">
                                                </div>
                                            </li>

                                        @endforeach
                                    </ul>
                                </div>


                                <!-- Exibir o total no final -->
                                <div class="mt-4 text-end">
                                    <h5>Total do Kit: R$<span
                                            id="totalKit">{{ number_format($totalPrice, 2, ',', '.') }}</span></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif


            <form method="POST" action="{{ route('products.update', ['id' => $viewData['product']->id]) }}"
                enctype="multipart/form-data" class="needs-validation" onsubmit="serializeFormData()">
                @method('PUT')
                @csrf

                <input type="hidden" name="owner" id="owner" value="{{ Auth::user()->id }}">

                <div class="accordion" id="productFormAccordion">
                    <!-- Informações Básicas -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingBasicInfo">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseBasicInfo" aria-expanded="true"
                                aria-controls="collapseBasicInfo">
                                Informações Básicas
                            </button>
                        </h2>
                        <div id="collapseBasicInfo" class="accordion-collapse collapse show"
                            aria-labelledby="headingBasicInfo" data-bs-parent="#productFormAccordion">
                            <div class="accordion-body">
                                <div class="row mb-3">
                                    <!-- Label e input para upload -->
                                    <!-- Upload de imagem -->
                                    <div class="col-lg-12">
                                        <div class="mb-3 row">
                                            <label for="file"
                                                class="col-lg-2 col-md-6 col-sm-12 col-form-label">Imagem:</label>
                                            <div class="col-lg-10 col-md-6 col-sm-12">
                                                <input class="form-control" type="file" id="file"
                                                    name="photos[]" multiple>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Campo oculto para armazenar a imagem principal -->
                                    <input type="hidden" id="image" name="image"
                                        value="{{ $viewData['product']->image }}">

                                    <!-- Contêiner para exibir imagens -->
                                    <div class="col-lg-12 mt-4">
                                        <ul class="gallery-container" id="gallery-container">
                                            @foreach ($viewData['photos'] as $index => $foto)
                                                <li class="gallery-item" data-url="{{ $foto['id'] }}">

                                                    <img src="{{ $foto['url'] }}"
                                                        alt="{{ $viewData['product']->getName() }}">

                                                    <!-- Exibir "Foto Principal" apenas na primeira imagem -->
                                                    @if ($index === 0)
                                                        <span class="main-image-label">Foto Principal</span>
                                                    @endif

                                                    <a href="#"
                                                        class="delete-btn delete-icon icone-lixeira">Excluir</a>
                                                </li>
                                            @endforeach
                                        </ul>

                                        <!-- Loading Overlay -->
                                        <div id="loading-api" class="d-none">
                                            <div class="spinner-border text-light" role="status">
                                                <span class="visually-hidden">Carregando...</span>
                                            </div>
                                        </div>

                                    </div>
                                </div>


                                <div class="row mb-3">
                                    <div class="col {{ $viewData['product']->isKit ? 'd-none' : null }}">
                                        <label for="isPublic">Ativo / Público:</label>
                                        <select name="isPublic" class="form-control" required>
                                            <option value="1"
                                                {{ $viewData['product']->isPublic == 1 ? 'selected' : '' }}>SIM</option>
                                            <option value="0"
                                                {{ $viewData['product']->isPublic == 0 ? 'selected' : '' }}>NÃO</option>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label for="isNft">NFT:</label>
                                        <select name="isNft" class="form-control" required>
                                            <option value="1">SIM</option>
                                            <option value="0" selected>NÃO</option>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label for="isExclusivo">Exclusivo</label>
                                        <select name="isExclusivo" class="form-control" required>
                                            <option value="1"
                                                {{ $viewData['product']->isExclusivo == 1 ? 'selected' : '' }}>SIM</option>
                                            <option value="0"
                                                {{ $viewData['product']->isExclusivo == 0 ? 'selected' : '' }}>NÃO</option>
                                        </select>
                                    </div>
                                    <div class="col mb-3">
                                        <label for="informacaoadicional" class="form-label">Informações
                                            Adicionais:</label>
                                        <input name="informacaoadicional" type="text"
                                            value="{{ $viewData['product']->informacaoadicional }}" class="form-control">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="link" class="form-label">Link do Material:</label>
                                        <input name="link" type="text" value="{{ $viewData['product']->link }}"
                                            class="form-control">
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col">
                                        <label for="title" class="form-label">Nome:</label>
                                        <input name="title" type="text" value="{{ $viewData['product']->title }}"
                                            class="form-control" required>
                                    </div>
                                    <div class="col-lg-4">
                                        <label for="brand" class="form-label">Marca:</label>
                                        <input name="brand" type="text" value="{{ $viewData['product']->brand }}"
                                            class="form-control" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>





                    <!-- Preço e Estoque -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingPricing">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapsePricing" aria-expanded="false" aria-controls="collapsePricing">
                                Preço e Estoque
                            </button>
                        </h2>
                        <div id="collapsePricing" class="accordion-collapse collapse" aria-labelledby="headingPricing"
                            data-bs-parent="#productFormAccordion">
                            <div class="accordion-body">
                                <div class="row mb-3">
                                    <!-- Preço R$ -->
                                    <div class="col-lg-3">
                                        <label for="precoNormal">Preço R$:</label>
                                        <input name="price" id="precoNormal"
                                            value="{{ $viewData['product']->isKit ? number_format($totalPrice, 2) : $viewData['product']->price }}"
                                            type="text" class="form-control" required readonly>
                                        @error('price')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Preço Promocional -->
                                    <div class="col-lg-3">
                                        <label for="pricePromotion">Preço Promocional:</label>
                                        <input name="pricePromotion" value="0" type="text" class="form-control">
                                    </div>

                                    <!-- Estoque -->
                                    <div class="col-lg-3">
                                        <label for="available_quantity">Estoque:</label>
                                        <input name="available_quantity" type="number"
                                            value="{{ $viewData['product']->available_quantity }}" class="form-control"
                                            required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <!-- Estoque Mínimo Afiliado -->
                                    <div class="col-lg-3">
                                        <label for="estoque_minimo_afiliado">Estoque Mínimo Afiliado:</label>
                                        <input name="estoque_minimo_afiliado" id="estoque_minimo_afiliado"
                                            value="{{ $viewData['product']->estoque_minimo_afiliado }}" type="number"
                                            class="form-control">
                                    </div>

                                    <!-- Percentual de Estoque -->
                                    <div class="col-lg-3">
                                        <label for="percentual_estoque">Percentual de Estoque:</label>
                                        <input name="percentual_estoque" id="percentual_estoque"
                                            value="{{ $viewData['product']->percentual_estoque }}" type="text"
                                            class="form-control">
                                    </div>

                                    <!-- Estoque do Afiliado -->
                                    <div class="col-lg-3">
                                        <label for="estoque_afiliado">Estoque do Afiliado:</label>
                                        <input name="estoque_afiliado" id="estoque_afiliado"
                                            value="{{ $viewData['product']->estoque_afiliado }}" type="number"
                                            class="form-control" readonly>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <!-- Mínimo de Unidades no Kit -->
                                    <div class="col-lg-3">
                                        <label for="min_unidades_kit">Mínimo de Unidades no Kit:</label>
                                        <input name="min_unidades_kit" id="min_unidades_kit"
                                            value="{{ $viewData['product']->min_unidades_kit }}" type="number"
                                            class="form-control">
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <label for="id_bling" class="form-label font-weight-bold">ID Bling
                                        (Obrigatório):</label>
                                    <input name="id_bling" id="id_bling"
                                        value="{{ $viewData['product']->id_bling ?? old('id_bling') }}" type="text"
                                        class="form-control border-danger text-danger bg-light font-weight-bold" required
                                        style="border-width: 2px; font-size: 1.2rem; box-shadow: 0px 0px 10px rgba(255,0,0,0.5);">
                                    @error('id_bling')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>


                                <div class="row mb-3 mt-2">
                                    <!-- Botões de Ação -->
                                    <div class="col-lg-6">
                                        <label for="acao" class="form-label">Ação:</label>
                                        <select id="acao" name="acao" class="form-select">
                                            <option value="">Selecione uma ação</option>
                                            <option value="notificar"
                                                {{ old('acao', $viewData['product']->acao) == 'notificar' ? 'selected' : '' }}>
                                                Notificação Vendedor
                                            </option>
                                            <option value="pausar"
                                                {{ old('acao', $viewData['product']->acao) == 'pausar' ? 'selected' : '' }}>
                                                Pausar Anúncio
                                            </option>
                                        </select>
                                    </div>


                                    <div class="col-lg-6">
                                        <button type="button" disabled class="btn btn-primary">Mostrar Anúncios
                                            Afetados</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Atributos -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingAttributes">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseAttributes" aria-expanded="false"
                                aria-controls="collapseAttributes">
                                Atributos
                            </button>
                        </h2>
                        <div id="collapseAttributes" class="accordion-collapse collapse"
                            aria-labelledby="headingAttributes" data-bs-parent="#productFormAccordion">
                            <div class="accordion-body">

                                <div class="mb-3">
                                    <label class="form-label">Descrição:</label>
                                    <textarea required class="form-control" name="description" rows="3">{{ $viewData['product']->description }}</textarea>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-lg-2">
                                        <label for="termometro">Valor Termômetro:</label>
                                        <input type="number" name="termometro" id="termometro" value="100"
                                            min="0" max="150" class="form-control">
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="gtin">GTIN / EAN:</label>
                                        <input name="gtin" value="{{ $viewData['product']->gtin }}"
                                            class="form-control" required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-lg-2">
                                        <label for="width">Largura:</label>
                                        <input name="width" value="{{ $viewData['product']->width }}"
                                            class="form-control" required>
                                    </div>
                                    <div class="col-lg-2">
                                        <label for="height">Altura:</label>
                                        <input name="height" value="{{ $viewData['product']->height }}"
                                            class="form-control" required>
                                    </div>
                                    <div class="col-lg-2">
                                        <label for="length">Comprimento:</label>
                                        <input name="length" value="{{ $viewData['product']->length }}"
                                            class="form-control" required>
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <label for="tipo_anuncio">Tipo de Anúncio:</label>
                                    <select name="tipo_anuncio" id="tipo_anuncio" class="form-control"
                                        aria-label=".form-select-sm example" required>
                                        @php
                                            $tiposAnuncio = [
                                                'gold_special' => 'Clássico',
                                                'gold_pro' => 'Premium',
                                            ];
                                            $tipoAtual = $viewData['product']->listing_type_id ?? '';
                                        @endphp

                                        @foreach ($tiposAnuncio as $valor => $label)
                                            <option value="{{ $valor }}"
                                                {{ $tipoAtual == $valor ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="row mb-3">

                                    <div class="col-md-8 d-none">
                                        <label for="preco" class="form-label">Fornecedores
                                            <div id="loadingF" class="spinner-border spinner-border-sm d-none"
                                                role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </label>
                                        <div class="input-group">
                                            <select id="fornecedor-select" name="fornecedor_id" class="form-select"
                                                style="max-width: 240px;">
                                                <option value="">Selecione..</option>
                                                @foreach ($viewData['fornecedor'] as $fornecedor)
                                                    <option class="bg-warning" value="{{ $fornecedor->id }}"
                                                        {{ old('fornecedor', $viewData['product']->fornecedor_id ?? '') == $fornecedor->id ? 'selected' : '' }}>
                                                        {{ $fornecedor->name }}
                                                    </option>
                                                @endforeach
                                            </select>

                                            <span class="input-group-text"><i class="bi bi-person-lines-fill"></i></span>
                                            <input type="text" id="fornecedor-input" class="form-control"
                                                placeholder="digite o nome do fornecedor">

                                        </div>
                                    </div>

                                    <div class="col-md-6 mt-2">
                                        <label for="categoria">Categorias:</label>
                                        <select class="form-select mt-2" name="subcategoria" id="categoria" required
                                            aria-label="Default select example">
                                            @foreach ($viewData['categorias'] as $categoria)
                                                <option class="bg-dark text-white" disabled>{{ $categoria['nome'] }}
                                                </option>
                                                @foreach ($categoria['subcategory'] as $subcategoria)
                                                    <option value="{{ $subcategoria->id }}"
                                                        {{ (old('categoria') ?? ($viewData['product']->subcategoria ?? '')) == $subcategoria->id ? 'selected' : '' }}>
                                                        - {{ $subcategoria->name }}
                                                    </option>
                                                @endforeach
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <label>Categoria Mercado Livre:</label>

                                    <div class="alert alert-primary" id="mercadoLivreCategoria" role="alert">

                                    </div>

                                    <div class="input-group">
                                        <select class="form-select" id="categorias" aria-label="Default select example"
                                            required>
                                            <option selected disabled>Selecionar</option>
                                        </select>
                                        <button type="button" class="btn btn-secondary" id="resetButton">Reset</button>
                                    </div>

                                    <input type="hidden" class="form-control"
                                        value="{{ $viewData['product']->category_id }}" name="category_id"
                                        id="id_categoria">

                                    <div class="col-md-12 mt-3">
                                        <div class="col">
                                            <div class="mb-3 row">
                                                <ol class="list-group list-group-numbered content_categorias">
                                                </ol>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="formContainer">
                                    {!! $viewData['product']->atributos_html !!}
                                </div>

                                <div id="jsonViewer" style="height: 400px; border: 1px solid #ccc;"></div>


                            </div>
                        </div>
                    </div>

                    <!-- Taxas -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingFees">
                            <button class="accordion-button collapsed" id="taxesTabButton" type="button"
                                data-bs-toggle="collapse" data-bs-target="#collapseFees" aria-expanded="false"
                                aria-controls="collapseFees">
                                Taxas
                            </button>
                        </h2>
                        @if ($viewData['product']->isKit == 1)
                            <div id="collapseFees" class="accordion-collapse collapse" aria-labelledby="headingFees"
                                data-bs-parent="#productFormAccordion">
                                <div class="accordion-body">
                                    <div class="row mb-3">
                                        <div class="col">
                                            {{-- <p class="col-lg-2 col-md-6 col-sm-12 col-form-label">Acréssimo </p> --}}
                                            <div class="col d-none">
                                                <div class="mb-3 row">
                                                    <label class="col-lg-2 col-md-6 col-sm-12 col-form-label">%</label>
                                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                                        <input id="acressimoP" name="valorProdFornecedor"
                                                            class="form-control porcem" value="{{ old('acressimoP') }}"
                                                            type="hidden" readonly>
                                                    </div>
                                                    <label class="col-lg-2 col-md-6 col-sm-12 col-form-label">R$</label>
                                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                                        <input id="acressimoR" name="valorProdFornecedor" type="hidden"
                                                            class="form-control porcem"
                                                            value="{{ $viewData['product']->isKit ? $fee : $viewData['product']->valorProdFornecedor }}"
                                                            readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mb-3 row d-none">

                                                <label class="col-lg-2 col-md-6 col-sm-12 col-form-label">Liquído: </label>
                                                <div class="col-lg-3 col-md-6 col-sm-12">
                                                    <input name="valorProdFornecedor"
                                                        value="{{ $viewData['product']->valorProdFornecedor }}"
                                                        id="precoLiquido" type="hidden" class="form-control" readonly>
                                                </div>
                                            </div>


                                            <div class="col-md-12">
                                                <div class="col">
                                                    <!-- Mensagem de cálculo -->
                                                    <div id="calculoMensagem" class="mt-2 p-2 text-center fw-bold"
                                                        style="background-color: black; color: #FFD700; border-radius: 5px; font-size: 16px;">
                                                        Cálculo: (Preço Kit + Tarifas) ÷ 0,95 = R$: Preço por anunciar.
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12 mt-3">
                                                <div class="col">
                                                    <div class="mb-3 row">

                                                        <label class="col-lg-1 col-md-3 col-sm-12 col-form-label">Tarifa
                                                            R$: </label>
                                                        <div class="col-lg-2 col-md-3 col-sm-12">
                                                            <input name="fee"
                                                                value="{{ number_format($fee, 2, '.', '') }}"
                                                                id="fee" type="text" class="form-control"
                                                                readonly>
                                                        </div>

                                                        <label class="col-lg-1 col-md-3 col-sm-12 col-form-label">Taxa %:
                                                        </label>
                                                        <div class="col-lg-2 col-md-6 col-sm-12">
                                                            <input name="taxaFee" id="taxaFee" type="text"
                                                                value="4.99" class="form-control" readonly>
                                                        </div>

                                                        {{-- <label class="col-lg-1 col-md-6 col-sm-12 col-form-label">Final:
                                                        </label> --}}
                                                        <div class="col-lg-2 col-md-3 col-sm-12 d-none">
                                                            <input name="priceWithFee" id="PriceWithFee" type="hidden"
                                                                class="form-control"
                                                                value="{{ $viewData['product']->priceWithFee }}" readonly>
                                                        </div>
                                                        <label class="col-lg-2 col-md-6 col-sm-12 col-form-label">Preço
                                                            Kit + Tarifas:
                                                        </label>
                                                        <div class="col-lg-2 col-md-3 col-sm-12">
                                                            <input name="priceKit" id="priceKit" type="text"
                                                                class="form-control"
                                                                value="{{ number_format(($viewData['product']->priceKit ? $viewData['product']->priceKit : $viewData['product']->price + $fee) / 0.95, 2, '.', '') }}"
                                                                readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div id="collapseFees" class="accordion-collapse collapse" aria-labelledby="headingFees"
                                data-bs-parent="#productFormAccordion">
                                <div class="accordion-body">
                                    <div class="row mb-3">
                                        <div class="col">
                                            <p class="col-lg-2 col-md-6 col-sm-12 col-form-label">Acréssimo </p>
                                            <div class="col">
                                                <div class="mb-3 row">
                                                    <label class="col-lg-2 col-md-6 col-sm-12 col-form-label">%</label>
                                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                                        <input id="acressimoP" name="valorProdFornecedor"
                                                            class="form-control porcem" value="{{ old('acressimoP') }}">
                                                    </div>
                                                    <label class="col-lg-2 col-md-6 col-sm-12 col-form-label">R$</label>
                                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                                        <input id="acressimoR" name="valorProdFornecedor" type="text"
                                                            class="form-control porcem"
                                                            value="{{ $viewData['product']->isKit ? $fee : $viewData['product']->valorProdFornecedor }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-md-6 col-sm-12 col-form-label">Bruto:</label>
                                                <div class="col-lg-3 col-md-6 col-sm-12">
                                                    <input id="precoFinal" value="{{ old('price') }}" type="text"
                                                        class="form-control">
                                                </div>
                                                <label class="col-lg-2 col-md-6 col-sm-12 col-form-label">Liquído: </label>
                                                <div class="col-lg-3 col-md-6 col-sm-12">
                                                    <input name="valorProdFornecedor"
                                                        value="{{ $viewData['product']->valorProdFornecedor }}"
                                                        id="precoLiquido" type="text" class="form-control">
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="col">
                                                    <div class="mb-3 row">

                                                        <label class="col-lg-2 col-md-3 col-sm-12 col-form-label">Taxa %:
                                                        </label>
                                                        <div class="col-lg-1 col-md-6 col-sm-12">
                                                            <input name="taxaFee" id="taxaFee" type="text"
                                                                value="4.99" class="form-control">
                                                        </div>

                                                        <label class="col-lg-1 col-md-6 col-sm-12 col-form-label">Final:
                                                        </label>
                                                        <div class="col-lg-2 col-md-3 col-sm-12">
                                                            <input name="priceWithFee" id="PriceWithFee" type="text"
                                                                class="form-control"
                                                                value="{{ $viewData['product']->priceWithFee }}">
                                                        </div>
                                                        <label class="col-lg-2 col-md-6 col-sm-12 col-form-label">Preço
                                                            Kit:
                                                        </label>
                                                        <div class="col-lg-2 col-md-3 col-sm-12">
                                                            <input name="priceKit" id="priceKit" type="text"
                                                                class="form-control"
                                                                value="{{ $viewData['product']->priceKit }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            let jsonData = {!! $viewData['product']->atributos_json !!};

                            let container = document.getElementById("jsonViewer");
                            let options = {
                                mode: "tree", // Exibe como árvore
                                search: false, // Remove o campo de pesquisa
                                enableSort: false, // Remove o botão de ordenação
                                enableTransform: false // Remove o botão de transformação
                            };

                            let editor = new JSONEditor(container, options);
                            editor.set(jsonData);

                            // Expande automaticamente todos os nós
                            editor.expandAll();
                        });
                    </script>

                    <!-- Campo hidden para armazenar o HTML serializado -->
                    <input type="hidden" name="atributos_html" id="atributos_html">
                    <!-- Novo campo para armazenar o JSON -->
                    <input type="hidden" name="atributos_json" id="atributos_json">

                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">Atualizar <i class="bi bi-hdd"></i></button>
                    </div>
            </form>


            <!-- Modal para Adicionar Novo Produto ao Kit -->
            <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addProductModalLabel">Adicionar Novo Produto ao Kit</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Campo Hidden para armazenar o ID do produto sendo editado -->
                            <input type="hidden" name="product_id" value="{{ $viewData['product']->id }}">

                            <!-- Campo de pesquisa -->
                            <div class="mb-3">
                                <label for="searchInput" class="form-label">Pesquisar Produto</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="searchInput"
                                        placeholder="Digite o nome do produto">
                                    <button id="searchButton" type="button" class="btn btn-primary">Buscar</button>
                                </div>
                            </div>

                            <!-- Resultados da busca -->
                            <div id="searchResults" class="mt-3">
                                <p class="text-muted">Use o campo acima para buscar produtos.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>





        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.theme.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.3/jquery.mask.min.js"></script>
    <!-- Highlight.js Script -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.7.0/highlight.min.js"></script>
    <script>
        hljs.highlightAll();
    </script>

    <script>
        // Função para atualizar o valor exibido
        const isPorcemInput = document.getElementById('isPorcem');
        const precoFixoCheckbox = document.getElementById('precoFixoCheckbox');
        const valorAgregadoInput = document.getElementById('valorAgregadoInput');
        const valorAgregadoSelect = document.getElementById('editValorAgregado');



        function atualizarValorProduto() {

            let novoValor = parseFloat($('#precoNormal').val()) || 0;
            let tarifaTotal = parseFloat($("#fee").val().replace(',', '.')) || 0;

            // Garante que o valor não seja negativo
            if (novoValor < 0) novoValor = 0;
            // Atualiza o display do valor
            if ($("#precoNormal").val() > 0) {
                var result = (novoValor + tarifaTotal) / 0.95;
                $("#priceKit").val(result.toFixed(2));
            }
        }

        atualizarValorProduto();


        (function() {
            let devtoolsOpen = false;

            // Detecta a abertura do DevTools (F12, Ctrl+Shift+I, etc.)
            let element = new Image();
            Object.defineProperty(element, 'id', {
                get: function() {
                    devtoolsOpen = true;
                    window.location.reload(); // Recarrega a página
                }
            });
            console.log("%c", element);

            // Monitora mudanças no `readonly`
            document.addEventListener("DOMContentLoaded", function() {
                let inputs = document.querySelectorAll("input[readonly]");

                inputs.forEach(input => {
                    // Força o readonly
                    input.setAttribute("readonly", "readonly");

                    // Evita alterações pelo DevTools
                    Object.defineProperty(input, "readOnly", {
                        value: true,
                        writable: false
                    });
                });
            });

            // Previne atalhos do teclado (F12, Ctrl+Shift+I, etc.)
            document.addEventListener("keydown", function(event) {
                if (event.key === "F12" ||
                    (event.ctrlKey && event.shiftKey && (event.key === "I" || event.key === "J" || event.key ===
                        "C")) ||
                    (event.ctrlKey && event.key === "U")) {
                    event.preventDefault();
                    window.location.reload(); // Recarrega a página
                }
            });

            // Impede o clique direito
            document.addEventListener("contextmenu", function(event) {
                event.preventDefault();
            });
        })();
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const idBlingInput = document.getElementById('id_bling');

            idBlingInput.addEventListener('input', function() {
                if (idBlingInput.value.trim() !== '') {
                    // Quando preenchido, aplica estilo verde
                    idBlingInput.classList.remove('border-danger', 'text-danger');
                    idBlingInput.classList.add('border-success', 'text-success');
                    idBlingInput.style.boxShadow = '0px 0px 10px rgba(0, 255, 0, 0.5)';
                } else {
                    // Quando vazio, volta ao estilo vermelho
                    idBlingInput.classList.remove('border-success', 'text-success');
                    idBlingInput.classList.add('border-danger', 'text-danger');
                    idBlingInput.style.boxShadow = '0px 0px 10px rgba(255, 0, 0, 0.5)';
                }
            });
        });

        $(document).ready(function() {

            // Quando o estoque é alterado
            $('input[name="available_quantity"]').on('input', function() {
                // Pega o valor do estoque atual
                const availableQuantity = parseFloat($(this).val()) || 0;

                // Pega o percentual do estoque
                const percentualEstoque = parseFloat($('#percentual_estoque').val()) || 0;

                // Calcula o estoque do afiliado
                const estoqueAfiliado = Math.floor((availableQuantity * percentualEstoque) / 100);

                // Atualiza o campo de estoque do afiliado
                $('#estoque_afiliado').val(estoqueAfiliado);

            });

            // Quando o percentual do estoque é alterado
            $('#percentual_estoque').on('input', function() {
                // Pega o valor do percentual
                const percentualEstoque = parseFloat($(this).val()) || 0;

                // Pega o valor do estoque atual
                const availableQuantity = parseFloat($('input[name="available_quantity"]').val()) || 0;

                // Calcula o estoque do afiliado
                const estoqueAfiliado = Math.floor((availableQuantity * percentualEstoque) / 100);

                // Atualiza o campo de estoque do afiliado
                $('#estoque_afiliado').val(estoqueAfiliado);
            });

            // Adicionar evento para novas imagens no input
            $('#file').on('change', function(e) {
                const files = e.target.files;

                Array.from(files).forEach((file) => {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        addImageToPreview(event.target.result, file.name,
                            false); // Passa o nome real do arquivo
                    };
                    reader.readAsDataURL(file);
                });
            });

            // Função para adicionar imagens ao preview
            function addImageToPreview(imageUrl, fileName, isExisting = false) {
                const mainLabel = `<span class="main-image-label d-none">Imagem Principal</span>`;
                const deleteButton = `
                <span class="icone-lixeira position-absolute top-0 end-0 m-2 p-2 bg-light rounded-circle">
                    <i class="fas fa-trash-alt text-danger"></i>
                </span>`;

                $('#imagePreview').append(`
                <div class="image-item position-relative" data-url="${fileName}" data-existing="${isExisting}">
                    <img src="${imageUrl}" class="img-fluid">
                    ${mainLabel}
                    ${deleteButton}
                </div>
            `);

                updateImageCount();
                updateMainImageLabel();
            }

            // Remover imagem
            window.removeImage = function(button) {
                const imageContainer = $(button).closest('.image-item');
                const isExisting = imageContainer.data('existing');

                if (isExisting) {
                    console.log('Removendo imagem existente:', imageContainer.data(
                        'url')); // Lógica para backend, se necessário
                }

                imageContainer.remove();
                updateImageCount();
                updateMainImageLabel();
            };

            $("#gallery-container").sortable({
                tolerance: "pointer", // Melhora a resposta ao arrastar
                cursor: "move", // Muda o cursor para "mão de arrasto"
                opacity: 0.8, // Reduz a opacidade do item enquanto é arrastado
                revert: 200, // Animação suave ao soltar
                axis: "x", // Restringe o movimento apenas na horizontal
                scroll: false, // Evita que a página role enquanto arrasta
                containment: "parent", // Evita que os itens saiam da galeria
                helper: function(e, ui) {
                    ui.clone().css("position", "absolute"); // Corrige o problema de subir para o topo
                    return ui.clone();
                },
                zIndex: 9999, // Garante que o item fique no topo ao arrastar
                placeholder: "sortable-placeholder", // Evita que os itens "pulem"
                forceHelperSize: true, // Mantém o tamanho do item ao arrastar
                forcePlaceholderSize: true, // Mantém espaço visível ao arrastar
                distance: 5, // Evita que cliques acidentais ativem o sortable
                stop: function() {
                    saveImageOrder();
                    updateMainImageLabel();
                }
            });


            // Excluir imagem ao clicar no botão
            $(document).on('click', '.delete-btn', function(e) {
                e.preventDefault();
                $(this).closest('.gallery-item').fadeOut(200, function() {
                    $(this).remove();
                    updateMainImageLabel();
                });
            });

            // Função para capturar e enviar a ordem das imagens ao backend
            function saveImageOrder() {
                let imageOrder = [];
                $("#gallery-container .gallery-item").each(function(index) {
                    let imageId = $(this).attr("data-url"); // Pegamos a URL como identificador
                    imageOrder.push({
                        id: imageId,
                        position: index + 1
                    }); // Criamos um array com a nova ordem
                });

                // Enviar via AJAX para Laravel
                $.ajax({
                    url: "/salvar-ordem-imagens", // Rota Laravel para salvar
                    type: "POST",
                    data: {
                        images: imageOrder
                    },
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") // CSRF Token Laravel
                    },
                    success: function(response) {
                        console.log("Ordem salva com sucesso!", response);
                    },
                    error: function(error) {
                        console.error("Erro ao salvar ordem!", error);
                    }
                });
            }

            function updateMainImageLabel() {
                // Remove todos os rótulos antigos de "Foto Principal"
                $('.main-image-label').remove();

                // Pega a primeira imagem da lista e adiciona o rótulo
                let firstItem = $('#gallery-container .gallery-item').first();
                if (firstItem.length) {
                    firstItem.append('<span class="main-image-label">Foto Principal</span>');
                }
            }


            updateMainImageLabel(); // Garante que o rótulo seja atualizado ao carregar a página
            // Ativar a função quando houver alteração no campo #precoNormal
            $('#precoNormal').on('input', function() {
                const acressimoRField = $('#acressimoR'); // Campo que deve ser acionado

                // Simula uma interação manual para ativar o evento keyup no campo #acressimoR
                const currentValue = acressimoRField.val();
                acressimoRField.val(''); // Limpa temporariamente o valor
                setTimeout(() => {
                    acressimoRField.val(currentValue); // Reinsere o valor
                    acressimoRField.trigger('keyup'); // Dispara o evento keyup
                }, 50); // Atraso para simular interação real

                // Adiciona a classe para piscar
                $('#taxesTabButton').addClass('blink-tab');

                // Remove o piscar após 3 segundos
                setTimeout(function() {
                    $('#taxesTabButton').removeClass('blink-tab');
                }, 2000); // 3 segundos
            }); // Atraso para simular interação real



            $('#fornecedor-input').on('input', function() {
                let inputVal = $(this).val();

                if (inputVal.length >= 1) { // Começa a buscar após 2 caracteres
                    $.ajax({
                        url: '/api/v1/fornecedores',
                        data: {
                            name: inputVal
                        },
                        success: function(data) {
                            // Esconde o spinner após o carregamento
                            $("#spinner").removeClass('d-none');
                            // Limpa as opções existentes
                            $('#fornecedor-select').empty().append(
                                '<option value="">Selecione um fornecedor</option>');

                            // Adiciona os novos fornecedores ao select
                            $.each(data, function(index, fornecedor) {
                                $('#fornecedor-select').append('<option value="' +
                                    fornecedor.id + '">' + fornecedor.name +
                                    '</option>');
                            });

                            // Esconde o spinner após o carregamento
                            $("#spinner").addClass('d-none');
                        },
                        error: function(xhr) {
                            console.error('Erro ao buscar fornecedores', xhr);
                        }
                    });
                } else {
                    // Limpa o select se menos de 2 caracteres
                    $('#fornecedor-select').empty().append(
                        '<option value="">Selecione um fornecedor</option>');
                }
            });

            var i = 0;
            // Função para resetar o conteúdo
            $('#resetButton').on('click', function() {
                getAllCategorias(); // VOLTA TUDO AS CATEGORIAS
                // Limpa a lista de categorias selecionadas
                $('.content_categorias').empty();

                // Limpa o campo de ID de categoria
                $('#id_categoria').val('');
                // Reseta o select para a opção padrão
                $('#categorias').empty();
            });

            getAllCategorias();

            function getAllCategorias() {
                $.ajax({
                    url: "https://api.mercadolibre.com/sites/MLB/categories",
                    type: "GET",
                    success: function(response) {
                        if (response) {
                            // SHOW ALL RESULT QUERY
                            var index = [];
                            $.each(response, function(i, item) {
                                index[i] = '<option class="option-size" value=' + item.id +
                                    '>' +
                                    item.name + '</option>';
                            });

                            if (i == 0) {
                                // PEGA A ALTERACAO DAS CATEGORIAS
                                $("#categorias").off("change").on("change", function() {
                                    var ids = $(this).children("option:selected").val();
                                    var name = $(this).children("option:selected").text();
                                    var content_category = '<li class="list-group-item">' +
                                        name +
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
            }

            // FUNCAO PARA CHAMAR CATEGORIAS
            function getCategory(category) {
                $.ajax({
                    url: " https://api.mercadolibre.com/categories/" + category,
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
                                    url: "https://api.mercadolibre.com/categories/" + category +
                                        "/attributes",
                                    type: "GET",
                                    success: function(response) {
                                        if (response) {
                                            const requiredItems = [];
                                            const excludedAttributes = ['BRAND', 'MODEL',
                                                'LENGTH', 'HEIGHT'
                                            ];

                                            response.forEach(item => {

                                                if (item.tags && item.tags
                                                    .required && !excludedAttributes
                                                    .includes(item.id)) {
                                                    console.log(item);
                                                    requiredItems.push(item);
                                                }
                                            });

                                            // Limpa o container antes de adicionar os novos elementos
                                            $("#formContainer").empty();

                                            if (requiredItems.length > 0) {
                                                // Cria um card para organizar melhor o formulário
                                                var card = document.createElement("div");
                                                card.className = "card shadow-sm mb-3";

                                                var cardBody = document.createElement(
                                                    "div");
                                                cardBody.className = "card-body";

                                                var fieldset = document.createElement(
                                                    "fieldset");

                                                var legend = document.createElement(
                                                    "legend");
                                                legend.textContent = "Campos Obrigatórios";
                                                legend.className = "h5 mb-3";
                                                fieldset.appendChild(legend);

                                                var row = document.createElement("div");
                                                row.className =
                                                    "row"; // Organiza os campos em duas colunas

                                                requiredItems.forEach(element => {
                                                    // Cria um grupo de formulário dentro da coluna
                                                    var colDiv = document
                                                        .createElement("div");
                                                    colDiv.className =
                                                        "col-md-6 col-sm-12 mb-3"; // Ajuste responsivo

                                                    var formGroup = document
                                                        .createElement("div");
                                                    formGroup.className =
                                                        "form-group";

                                                    // Adiciona o label
                                                    var label = document
                                                        .createElement("label");
                                                    label.textContent = element
                                                        .name;
                                                    formGroup.appendChild(label);

                                                    if (!element.values || element
                                                        .values.length === 0) {
                                                        var input = document
                                                            .createElement("input");
                                                        input.type = "text";
                                                        input.className =
                                                            "form-control form-control-sm";
                                                        input.name = element.id;
                                                        formGroup.appendChild(
                                                            input);
                                                    } else {
                                                        var selectField = document
                                                            .createElement(
                                                                "select");
                                                        selectField.className =
                                                            "form-control form-control-sm";
                                                        selectField.name = element
                                                            .id;

                                                        // Adiciona a opção padrão
                                                        var defaultOption = document
                                                            .createElement(
                                                                "option");
                                                        defaultOption.text =
                                                            "Selecione uma opção";
                                                        defaultOption.value = "";
                                                        selectField.appendChild(
                                                            defaultOption);

                                                        // Adiciona as opções do select
                                                        element.values.forEach(
                                                            value => {
                                                                var option =
                                                                    document
                                                                    .createElement(
                                                                        "option"
                                                                    );
                                                                option.text =
                                                                    value.name;
                                                                option.value =
                                                                    value.id;
                                                                selectField
                                                                    .appendChild(
                                                                        option);
                                                            });

                                                        formGroup.appendChild(
                                                            selectField);
                                                    }

                                                    colDiv.appendChild(formGroup);
                                                    row.appendChild(colDiv);
                                                });

                                                fieldset.appendChild(row);
                                                cardBody.appendChild(fieldset);
                                                card.appendChild(cardBody);
                                                document.getElementById("formContainer")
                                                    .appendChild(card);

                                            }
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


            getNameCategory($("#id_categoria").val());

            // FUNCAO PARA CHAMAR CATEGORIAS
            function getNameCategory(category) {
                $.ajax({
                    url: " https://api.mercadolibre.com/categories/" + category,
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
                                    url: " https://api.mercadolibre.com/categories/" + category,
                                    type: "GET",
                                    success: function(response) {
                                        $("#mercadoLivreCategoria").append(response.name);
                                    }
                                });
                            }

                        }
                    },
                    error: function(error) {
                        $('#result').html(
                            '<option> Produto Digitado Não Existe! </option>'
                        );
                    }
                });

            }


            $(".icone-lixeira").click(function(event) {
                event.preventDefault(); // Previne comportamento padrão do link

                if (confirm("Tem certeza que deseja apagar esta foto?")) {
                    var button = $(this); // Botão de exclusão clicado
                    var imageContainer = button.closest('.col'); // Container da imagem
                    var urlImagem = button.siblings("img").attr("src");

                    $("#loading-api").removeClass('d-none');

                    // Enviar requisição AJAX para excluir a imagem
                    $.ajax({
                        type: "POST",
                        url: "/api/v1/deleteFoto",
                        data: {
                            'imagem': urlImagem
                        },
                        success: function(response) {
                            // Exibe alerta de sucesso
                            const alertPlaceholder = document.getElementById(
                                'liveAlertPlaceholder');

                            const alert = (message, type) => {
                                const wrapper = document.createElement('div');
                                wrapper.innerHTML = `
                            <div class="alert alert-${type} alert-dismissible" role="alert">
                                <div>${message}</div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        `;

                                alertPlaceholder.append(wrapper);
                            };

                            alert(response.res, 'success');

                            // Remove a imagem com efeito de desaparecimento
                            imageContainer.fadeOut(500, function() {
                                $(this).remove();
                            });

                        },
                        error: function(xhr, status, error) {
                            console.error("Erro ao deletar a foto:", error);
                        },
                        complete: function() {
                            $("#loading-api").addClass('d-none');
                        }
                    });
                }
            });


            $('#feeClass').change(function() {
                var selectedValue = $(this).val();
                // Remover input anterior, se existir
                $('#inputContainer').empty();

                // Criar novo input com a máscara apropriada
                if (selectedValue === '1') {
                    $("#precoFinal").val($("#precoNormal").val());
                    $('#exampleModal').modal('show');
                } else if (selectedValue === '2') {
                    $("#precoFinal").val($("#precoNormal").val());
                    $('#exampleModal').modal('show');

                }

            });

            // VALOR TOTAL
            var total = $('#precoNormal').val();
            var totalCalculado = total;
            var totalLiquido = 0;
            var valorProduto = 0;
            var taxa = $("#taxaFee").val();

            function calculaPorcemtagem(valor, porcem) {
                //60 x 25% = 160 (25/100) = 160 x 0,25 = 40.
                return valor * (porcem / 100);
            }

            $('#precoFinal').val(parseFloat(total).toFixed(2));


            $('#acressimoP').keyup(function() {

                total = $('#precoNormal').val();
                $('#precoFinal').val(parseFloat(total).toFixed(2));

                if ($('#acressimoP').val().length >= 1) {
                    var porcem = $('#acressimoP').val();
                    totalCalculado = parseFloat($('#precoNormal').val()) + parseFloat(calculaPorcemtagem($(
                        '#precoNormal').val(), porcem));
                    $('#precoFinal').val(parseFloat(totalCalculado).toFixed(2));

                    totalLiquido = parseFloat($('#precoFinal').val()) - parseFloat($('#precoNormal').val());
                    $("#precoLiquido").val(totalLiquido.toFixed(2));

                    $('#acressimoR').prop("disabled", true).css({
                        'background-color': '#cecece'
                    });
                    $('#descontoP').prop("disabled", true).css({
                        'background-color': '#cecece'
                    });;
                    $('#descontoR').prop("disabled", true).css({
                        'background-color': '#cecece'
                    });
                } else {
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

            function convertBrazilianToNumber(value) {
                // Remove os pontos de milhares e substitui a vírgula decimal por um ponto
                let number = parseFloat(value.replace(/\./g, '').replace(',', '.'));
                return isNaN(number) ? 0 : number; // Retorna 0 se não for um número válido
            }


            $('#acressimoR').keyup(function() {

                total = $('#precoNormal').val();
                precoFinal = $('#precoFinal').val(parseFloat(total).toFixed(2));


                if ($('#acressimoR').val().length >= 1) {
                    var reais = $('#acressimoR').val();
                    totalCalculado = parseFloat(total) + parseFloat(reais);

                    $('#precoFinal').val(parseFloat(totalCalculado).toFixed(2));

                    // valor com as taxas calculo final
                    valorProduto = (parseFloat($("#precoFinal").val()) / 0.95);

                    valorKit = (parseFloat(total) / 0.90);
                    $("#priceKit").val(parseFloat(valorKit).toFixed(2));
                    // claculo do valor liquido
                    totalLiquido = parseFloat($('#precoFinal').val()) - parseFloat($('#precoNormal').val());
                    // preço liquido final
                    $("#precoLiquido").val(totalLiquido.toFixed(2));

                    // coloca o valor final
                    $("#PriceWithFee").val(parseFloat(valorProduto).toFixed(2));

                    $('#acressimoP').prop("disabled", true).css({
                        'background-color': '#cecece'
                    });
                    $('#descontoP').prop("disabled", true).css({
                        'background-color': '#cecece'
                    });;
                    $('#descontoR').prop("disabled", true).css({
                        'background-color': '#cecece'
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
        });
    </script>
@endsection
