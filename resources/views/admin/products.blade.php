@extends('layouts.app')
@section('title', $viewData['title'])
@section('conteudo')

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

    <!--- MODAL QUE SELECIONA O MOTORISTA --->

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


                                        <div class="card mb-3" style="max-width: 540px;">
                                            <div class="row g-0">
                                              <div class="col-md-4">
                                                <img class="img-fluid rounded-start img_integracao_foto">
                                              </div>
                                              <div class="col-md-8">
                                                <div class="card-body">
                                                  <h5 class="card-title img_integracao_title"></h5>
                                                  <p class="card-text img_integracao_ean"></p>
                                                  <p class="card-text img_integracao_price"></p>
                                                </div>
                                              </div>
                                            </div>
                                          </div>

                                        <input type="hidden" class="form-control" name="id_prodenv" id="id_prodenv">

                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col">
                                                    <div class="mb-3 row">
                                                        <label
                                                            class="col-lg-2 col-md-6 col-sm-12 col-form-label">Nome:</label>
                                                        <div class="col-lg-10 col-md-6 col-sm-12">
                                                            <input name="name" id="name" type="text"
                                                                class="form-control">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="exampleFormControlTextarea1">Descrição do Anúncio</label>
                                                    <textarea name="editor" id="editor" value="ds" rows="3"></textarea>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <p class="col-lg-2 col-md-6 col-sm-12 col-form-label">Acréssimo</p>
                                                <div class="col">
                                                    <div class="mb-3 row">

                                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                                            <label>%</label>
                                                            <input id="acressimoP" class="form-control porcem">
                                                        </div>

                                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                                            <label>R$</label>
                                                            <input id="acressimoR" type="text" class="form-control porcem">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <p class="col-lg-2 col-md-6 col-sm-12 col-form-label">Desconto </p>
                                                <div class="col">
                                                    <div class="mb-3 row">

                                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                                            <label>%</label>
                                                            <input id="descontoP" type="text"
                                                                class="form-control porcem">
                                                        </div>

                                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                                            <label>R$</label>
                                                            <input id="descontoR" type="text"
                                                                class="form-control porcem">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
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

                                            <div class="col-md-12">
                                                <div class="col">
                                                    <div class="mb-3 row">
                                                        <label
                                                            class="col-lg-2 col-md-6 col-sm-12 col-form-label">Preço:</label>
                                                        <div class="col-lg-3 col-md-6 col-sm-12">
                                                            <input name="price" id="precoFinal" type="text"
                                                                class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="col">
                                                    <div class="mb-3 row">
                                                        <div class="col-lg-8 col-md-6 col-sm-12">
                                                            <div class="form-check">
                                                                <input type="hidden" class="form-control" name="category_id" id="category_id">
                                                                <input class="form-check-input" type="checkbox" name="category_default" id="flexCheckChecked"
                                                                    checked>
                                                                <label class="form-check-label" for="flexCheckChecked">
                                                                    Usar Categoria Padrão Selecionada
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="col">
                                                    <div class="mb-3 row">
                                                        <label class="col-lg-2 col-md-12 col-sm-12 col-form-label">Categorias:</label>
                                                        <div class="col-lg-8 col-md-12 col-sm-12">
                                                            <select class="form-select" id="categorias" aria-label="Default select example">
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
                                        </div>


                                        <div id="formContainer"></div>

                                        <input type="hidden" class="form-control" name="id_categoria"
                                            id="id_categoria">

                                          <div class="clearfix spinner-border text-success float-end d-none loading-integracao mt-4" style="width: 3rem; height: 3rem;" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                          </div>

                                        <button type="submit" class="btn btn-success mt-4 botao_integracao">Finalizar Integração</button>
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
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-headingTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#flush-collapseTwo" aria-expanded="false"
                                    aria-controls="flush-collapseTwo">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value=""
                                            id="flexCheckChecked" checked>
                                        <label class="form-check-label" for="flexCheckChecked">
                                            Shopee
                                        </label>
                                    </div>
                                </button>
                            </h2>
                            <div id="flush-collapseTwo" class="accordion-collapse collapse"
                                aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">Placeholder content for this accordion, which is intended to
                                    demonstrate the <code>.accordion-flush</code> class. This is the second item's accordion
                                    body. Let's imagine this being filled with some actual content.</div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-headingThree">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#flush-collapseThree" aria-expanded="false"
                                    aria-controls="flush-collapseThree">
                                    B2W
                                </button>
                            </h2>
                            <div id="flush-collapseThree" class="accordion-collapse collapse"
                                aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">Placeholder content for this accordion, which is intended to
                                    demonstrate the <code>.accordion-flush</code> class. This is the third item's accordion
                                    body. Nothing more exciting happening here in terms of content, but just filling up the
                                    space to make it look, at least at first glance, a bit more representative of how this
                                    would look in a real-world application.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid px-4">

        <h2 class="mt-4">Gerenciador de Produtos</h2>

        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
            <a href="{{ route('products.create') }}"><button class="btn btn-success me-md-2" type="button">Novo Produto
                    <i class="bi bi-patch-plus"></i></button></a>
        </div>

        <div class="accordion" id="accordionExample">
            <div class="card mt-2">
                <div class="card-header" id="headingOne">
                    <h2 class="mb-0">
                        <button class="btn btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne"
                            aria-expanded="true" aria-controls="collapseOne">
                            <i class="bi bi-search"></i> Filtros de Busca
                        </button>
                    </h2>
                </div>

                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                    <div class="card-body bg-light rounded-3 p-4">
                        <form class="row g-3" action="{{ route('products.index') }}" method="GET">
                            @csrf
                            <!-- Campo Nome -->
                            <div class="col-md-4">
                                <label for="validationDefault01" class="form-label">Nome</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                                    <input type="text" class="form-control" name="nome"
                                           value="{{ isset($viewData['filtro']['nome']) ? $viewData['filtro']['nome'] : '' }}"
                                           id="validationDefault01" placeholder="Iphone 13">
                                </div>
                            </div>

                            <!-- Campo Preço -->
                            <div class="col-md-4">
                                <label for="preco" class="form-label">Preço R$</label>
                                <div class="input-group">
                                    <select name="preco_condicao" class="form-select" style="max-width: 140px;">
                                        <option value=">">Maior que</option>
                                        <option value="<">Menor que</option>
                                    </select>
                                    <span class="input-group-text">R$</span>
                                    <input type="text" name="preco"
                                           value="{{ isset($viewData['filtro']['preco']) ? $viewData['filtro']['preco'] : '' }}"
                                           class="form-control" id="preco" placeholder="0,00">
                                </div>
                            </div>

                            <!-- Campo Estoque -->
                            <div class="col-md-4">
                                <label for="validationDefaultUsername" class="form-label">Estoque</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-box-seam"></i></span>
                                    <input type="text" name="estoque"
                                           value="{{ isset($viewData['filtro']['estoque']) ? $viewData['filtro']['estoque'] : '' }}"
                                           class="form-control" id="validationDefaultUsername" placeholder="0">
                                </div>
                            </div>

                            <!-- Campo Categoria -->
                            <div class="col-md-4">
                                <label for="validationDefault04" class="form-label">Categoria</label>
                                <select class="form-select mt-2" name="categoria" id="categoria" required
                                    aria-label="Default select example">
                                    @foreach ($viewData['categorias'] as $categoria)
                                        <option class="bg-dark text-white" disabled>{{ $categoria['nome'] }}</option>
                                        @foreach ($categoria['subcategory'] as $subcategoria)
                                            <option value="{{ $subcategoria->id }}"> - {{ $subcategoria->name }}
                                            </option>
                                        @endforeach
                                    @endforeach
                                </select>
                            </div>

                            <!-- Botão Filtrar -->
                            <div class="col-md-12 text-end mt-3">
                                <button class="btn btn-primary" type="submit">
                                    <i class="bi bi-filter"></i> Filtrar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>




                <div class="card-body">
                    <tbody>
                        @foreach ($viewData['products'] as $product)
                            <section id="linhasProduct">
                                <span class="d-none id_product">{{ $product->getId() }}</span>
                                <div class="container py-1">
                                    <div class="row justify-content-center mb-3">
                                        <div class="col-md-12 col-xl-12">
                                            <div class="card shadow-0 border rounded-3">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-12 col-lg-3 col-xl-3 mb-4 mb-lg-0">
                                                            <div class="bg-image hover-zoom ripple rounded ripple-surface">
                                                                <img src="{!! Storage::disk('s3')->url('produtos/' . $product->getId() . '/' . $product->getImage()) !!}" style="width: 70%"
                                                                    alt="{{ $product->getName() }}" />
                                                                <a href="#!">
                                                                    <div class="hover-overlay">
                                                                        <div class="mask"
                                                                            style="background-color: rgba(253, 253, 253, 0.15);">
                                                                        </div>
                                                                    </div>
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                                            <h5>{{ $product->getName() }}</h5>
                                                            <div class="d-flex flex-row">
                                                                <div class="text-danger mb-1 me-2">
                                                                    Estoque:
                                                                </div>
                                                                <span>{{ $product->available_quantity }}</span>
                                                            </div>
                                                            <div class="mt-1 mb-0 text-muted small">
                                                                <span>100% cotton</span>
                                                                <span class="text-primary"> • </span>
                                                                <span>Light weight</span>
                                                                <span class="text-primary"> • </span>
                                                                <span>Best finish<br /></span>
                                                            </div>
                                                            <div class="mb-2 text-muted small">
                                                                <span>Unique design</span>
                                                                <span class="text-primary"> • </span>
                                                                <span>For men</span>
                                                                <span class="text-primary"> • </span>
                                                                <span>Casual<br /></span>
                                                            </div>
                                                            <p class="text-truncate mb-4 mb-md-0">
                                                                {{ $product->getDescription() }}
                                                            </p>
                                                        </div>
                                                        <div
                                                            class="col-md-6 col-lg-3 col-xl-3 border-sm-start-none border-start">
                                                            <div class="d-flex flex-row align-items-center mb-1">
                                                                <h4 class="mb-1 me-1">R$:
                                                                    {{ number_format($product->priceWithFee, 2) }}</h4>
                                                                {{-- <span class="text-danger"><s>$20.99</s></span> --}}
                                                            </div>
                                                            <h6 class="text-success">Envio Imediato</h6>
                                                            <div class="d-flex flex-column mt-4">
                                                                <a class="btn btn-primary btn-sm" href="{{route('products.show', ['id' => $product->getId()])}}">ver mais</a>
                                                                <button data-mdb-button-init data-mdb-ripple-init
                                                                    class="btn btn-success btn-sm mt-2"
                                                                    data-bs-toggle="modal" href="#exampleModalToggle"
                                                                    role="button">Integrar</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </section>
                        @endforeach
                        <div class="d-flex py-2">
                            {!! $viewData['products']->links() !!}
                        </div>
                </div>
            </div>
        </div>

        <input type="hidden" name="id_user" id="id_user" value="{{ Auth::user()->id }}">
        <input type="hidden" name="total" id="total">

    @endsection

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.theme.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.js"></script>

    <script>
        var valorProduto = 0;
        var i = 0;
        $(document).ready(function() {

            $('input[name="preco"]').mask('000.000.000,00', {reverse: true});

            $('.botao_integracao').click(function(event){
                $(".loading-integracao").removeClass('d-none');
                // Obtém a posição do elemento de loading
                // Altura da janela do navegador
            });


            $("section#linhasProduct").click(function() {

                var id_produto = $(this).find("span:eq(0)")
                    .text(); // Supondo que a segunda célula da linha contenha um texto específico

                // LIMPA O HISTORICO
                $('.adicionarHistorico').empty();
                $('#id_prodenv').val(id_produto); // ID DO PRODUTO

                var id_user = $('#id_user').val();
                $.ajax({
                    url: "/api/v1/product/" + id_produto,
                    type: "GET",
                    success: function(response) {
                        $("#loading-api").addClass('d-none');
                        if (response) {
                            valorProduto = response.priceWithFee;
                            $("#total").val(response.priceWithFee);
                            $("#name").val(response.title);
                            $("#precoFinal").val(response.priceWithFee);
                            $("#category_id").val(response.category_id);
                            $(".img_integracao_foto").attr('src',response.image);
                            $(".img_integracao_title").append(response.title);
                            $(".img_integracao_ean").append("EAN : " + response.ean);
                            $(".img_integracao_price").append("Preço: " + response.priceWithFee);
                            ClassicEditor
                                .create(document.querySelector('#editor'))
                                .then(editor => {
                                    editor.ui.view.editable.element.style.height = '250px';
                                    editor.setData(response.description);
                                })
                                .catch(error => {
                                    console.error('Houve um erro ao inicializar o editor:',
                                        error);
                                });
                        }
                    },
                    error: function(error) {
                        $('#result').html(
                            '<option> Produto Digitado Não Existe! </option>'
                        );
                    }
                });

                $.ajax({
                    url: "/api/v1/getHistoryById",
                    type: "GET",
                    data: {
                        id: id_produto,
                        id_user: id_user
                    },
                    success: function(response) {
                        if (response) {
                            var index = [];
                            $.each(response.dados, function(i, item) {
                                index[i] = '<li class="list-group-item"> Nome: ' + item
                                    .name + '  | ID: ' + item.id_ml +
                                    '   | Criado em : ' + item.created_at + '</li>';
                            });

                            var arr = jQuery.makeArray(index);
                            arr.reverse();
                            $(".adicionarHistorico").append(arr);
                        }
                    },
                    error: function(error) {
                        $('#result').html(
                            '<option> Produto Digitado Não Existe! </option>'
                        );
                    }
                });
            });

            //
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

            $("form").submit(function(event) {
                // event.preventDefault();
                $("#BtnCadastrar").addClass('d-none');
                $('#carregando').removeClass('d-none');
            });




            $('#descontoP').keyup(function() {
                // VALOR TOTAL
                var total = $('#total').val();
                var totalCalculado = total;

                if ($('#descontoP').val().length >= 1) {
                    var porcem = $('#descontoP').val();
                    totalCalculado = parseFloat(total) - parseFloat(calculaPorcemtagem(total,
                        porcem));
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
                // VALOR TOTAL
                var total = $('#total').val();
                var totalCalculado = total;

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
                        $('#precoFinal').val(parseFloat(total).toFixed(2));
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
                // VALOR TOTAL
                var total = $('#total').val();
                var totalCalculado = total;
                if ($('#acressimoP').val().length >= 1) {
                    var porcem = $('#acressimoP').val();
                    totalCalculado = parseFloat(total) + parseFloat(calculaPorcemtagem(total,
                        porcem));
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
                // VALOR TOTAL
                var total = $('#total').val();
                var totalCalculado = total;
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

            $('#total').val($('#valorTotalInput').val());

            /**
             * FUNCAO QUE PEGA VALOR DIGITADO NO INPUT
             */

            function getFormattedDate(date) {
                var year = date.getFullYear();

                var month = (1 + date.getMonth()).toString();
                month = month.length > 1 ? month : '0' + month;

                var day = date.getDate().toString();
                day = day.length > 1 ? day : '0' + day;

                return month + '/' + day + '/' + year;
            }

        });
    </script>
