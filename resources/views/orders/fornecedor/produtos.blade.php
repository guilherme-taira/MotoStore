@extends('layouts.app')
@section('title', $viewData['title'])
@section('conteudo')
<style>
.offcanvas {
    width: 300px;
}
#importSuccessModal {
    width: 400px; /* Ajuste para o tamanho desejado */
}

@media (min-width: 768px) {
    #importSuccessModal {
        width: 500px; /* Largura em telas médias ou maiores */
    }
}

@media (min-width: 1200px) {
    #importSuccessModal {
        width: 600px; /* Largura em telas grandes */
    }
}

.list-group-item {
    padding: 15px; /* Deixe o espaçamento mais agradável */
    border: 1px solid #ddd; /* Adicione uma borda leve */
    border-radius: 8px; /* Arredonde os cantos */
    margin-bottom: 10px; /* Separe os itens */
}

.list-group-item img {
    object-fit: contain; /* Garante que a imagem não seja distorcida */
}

.list-group-item div {
    font-size: 14px; /* Tamanho consistente para o texto */
}

</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const importBlingButton = document.getElementById('importBling');

    if (importBlingButton) {
        importBlingButton.addEventListener('click', function () {
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
                        const importedProductsList = document.getElementById('importedProductsList');
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

                        importedProductsList.insertAdjacentHTML('beforeend', listItem);
                    });
                        // Mostrar o offcanvas
                        const importSuccessModal = new bootstrap.Offcanvas(document.getElementById('importSuccessModal'));
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


    <div class="offcanvas offcanvas-start" tabindex="-1" id="importSuccessModal" aria-labelledby="importSuccessModalLabel">
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

        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
            @if(Auth::user()->forncecedor == 1)
            <a href="{{ route('products.create') }}">
                <button class="btn btn-success me-md-2" type="button">Novo Produto <i class="bi bi-patch-plus"></i></button>
            </a>
            @endif
            <button id="importBling" class="btn btn-primary" type="button">Importar Bling <i class="bi bi-cloud-arrow-down"></i></button>
        </div>


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
                Manage Products
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Imagem</th>
                            <th scope="col">ID</th>
                            <th scope="col">Name</th>
                            <th scope="col">Preço Fornecedor</th>
                            <th scope="col">Preço Afiliado</th>
                            <th scope="col">Preço Kit</th>
                            <th scope="col">Estoque</th>
                            <th scope="col">Ativo</th>
                            <th scope="col">Visualizações</th>
                            <th scope="col">Edit</th>
                            {{-- <th scope="col">Delete</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($viewData['products'] as $product)
                            <tr id="linhasProduct">
                                <td style="width: 100px;"><img src="{!! Storage::disk('s3')->url('produtos/' . $product->getId() . '/' . $product->getImage()) !!}" style="width: 120px; height: auto;" alt="{{ $product->getName() }}"></td>
                                <td class="id_product">{{ $product->getId() }}</td>
                                <td>{{ $product->getName() }}</td>
                                <td>R$: {{ number_format($product->getPrice(),2) }}</td>
                                <td>R$: {{ number_format($product->getPriceWithFeeMktplace(),2) }}</td>
                                <td>R$: {{ number_format($product->getPriceKit(),2) }}</td>
                                <td>{{ $product->getStock() }}</td>
                                @if ($product->isPublic == 1)
                                    <td><i class="bi bi-check2-square text-success"></i></td>
                                @else
                                    <td><i class="bi bi-slash-circle text-danger"></i></td>
                                @endif
                                <td>{{$product->acessos}}</td>
                                <td><a href="{{ route('products.edit', ['id' => $product->getId()]) }}"><button
                                            class="btn btn-primary btn-sm"><i
                                                class="bi bi-pencil-square"></i>Editar</button>
                                    </a></td>
                                {{-- <td><a href="{{ route('products.edit', ['id' => $product->getId()]) }}"><button
                                            class="btn btn-danger btn-sm"><i class="bi bi-trash"></i>Deletar</button> </a>
                                </td> --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="d-flex">
                    {!! $viewData['products']->links() !!}
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="id_user" id="id_user" value="{{ Auth::user()->id }}">
    <input type="hidden" name="total" id="total">
@endsection



