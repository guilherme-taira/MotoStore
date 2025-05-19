<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
    <title>{{ $viewData['title'] }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
</head>

<body>
    <div class="container mt-4">

        <button class="btn btn-primary" type="button" id="logHistory" data-bs-toggle="offcanvas"
            data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
            Histórico
        </button>

        <!-- Botão para abrir o modal offcanvas -->
        <button class="btn btn-danger" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasLeft"
            aria-controls="offcanvasLeft">
            Recadastrar Produto
        </button>


        <!-- Modal Offcanvas -->

        <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasLeft" aria-labelledby="offcanvasLeftLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasLeftLabel">Recadastrar Produto Mercado Livre</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                    aria-label="Fechar"></button>
            </div>
            <div class="offcanvas-body">
                <p>Ao informar o ID do produto, você inativará o produto e somente poderá reativá-lo recadastrando-o.
                </p>
                <!-- Formulário para enviar o input e o id -->
                <form id="recadastrarForm" action="{{ route('orders.recadastrar') }}" method="POST">
                    @csrf
                    <input type="hidden" name="token" id="token">
                    <div class="mb-3">
                        <label for="produtoId" class="form-label">ID do Produto</label>
                        <input type="text" class="form-control" id="produtoId" name="produto_id"
                            placeholder="Digite o ID do Produto">
                    </div>
                    <button type="submit" class="btn btn-primary">Recadastrar Produto</button>
                </form>
                <!-- Container para exibir o toast logo abaixo do formulário -->
                <div id="toastMessageContainer" class="mt-3"></div>
            </div>
        </div>




        <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample"
            aria-labelledby="offcanvasExampleLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasExampleLabel">
                    <div class="spinner-border text-primary" id="loadingHistorico" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div> Últimos Registros
                </h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                    aria-label="Close"></button>
            </div>
            <div class="offcanvas-body" id="conteudoHistorico">
                <div>
                    {{-- IMPLEMENTAR LOG --}}
                </div>
            </div>

            <span class="badge text-bg-info text-white mt-4">
                <h5>{{ $viewData['auth'] }}</h5>
            </span>
        </div>

        <h2>Mercado Livre Alterador de Categoria:</h2>

        <!-- Modal -->
        <div class="modal fade mt-2" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">
                            <div class="text-success spinner-border spinner-border-sm" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            Status Produto:
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                Status:
                            </div>
                            <ul class="list-group list-group-flush" id="resultadoServer">

                            </ul>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" data-bs-toggle="modal" id="abrirModal" href="#abrirModal22"
                            role="button">Criar Variações</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="exampleModalToggle2" aria-hidden="true"
            aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalToggleLabel2">Modal 2</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Hide this modal and show the first with the button below.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" data-bs-dismiss="modal">Finalizar</button>
                    </div>
                </div>
            </div>
        </div>


        <form id="formulario">
            <div class="input-group">
                <span class="input-group-text">Anúncio / Anúncio Base</span>
                <input type="text" class="form-control" id="id"
                    placeholder="Código do anúncio que sofrerá Mudança." />
                <input type="text" class="form-control" id="base" placeholder="Código do anúncio base."
                    value="@php echo isset($_GET['id']) ? $_GET['id'] : "" @endphp">
                <div class="input-group-append">
                    <button class="btn btn-primary" id="inserir" type="button">Inserir</button>
                </div>
            </div>



            <label class="form-check-label col-md-8 d-none" id="titulo_anuncio_base" for="titlenovo">
                Título do Anúncio: <div id="contador" class="text-end">0/60</div>
                <input type="text" class="form-control" name="titlenovo" id="titlenovo">
                <div class="progress mt-2">
                    <div id="progress-bar" class="progress-bar" role="progressbar" style="width: 0%;"
                        aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </label>

            <div class="col-md-8 d-none" id="div-sugestoes">
                <label for="validationCustom04" class="form-label">Sugestões de Nomes</label>
                <select id="selectSugestoes" class="form-select bg-warning"></select>
            </div>


            <ol id="titulo-anuncio" class="mt-4"></ol>
            <div class="row p-6 mt-4">
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="text" class="form-control" id="nomeprodutointegrado"
                            placeholder="Nome da categoria..." />
                        <div class="input-group-append">
                            <button class="btn btn-primary" id="pesquisar" type="button">Pesquisar</button>
                        </div>
                    </div>
                </div>
            </div>


            <div class="card mt-4">
                <div class="card-header">
                    Selecione o tipo de cadastro
                </div>
                <div class="card-body">
                    <h5 class="card-title">Tipo de Cadastro</h5>
                    <div class="container mt-4">
                        <label class="form-check-label col-md-8 d-none" id="titulo_anuncio" for="duplicar">
                            Título do Anúncio:
                            <input type="text" class="form-control" name="title_anuncio" id="title_anuncio">
                        </label>
                        <hr>
                        <input class="form-check-input" type="radio" name="tp_cadastro" value="duplicar"
                            id="duplicar">
                        <label class="form-check-label" for="duplicar">
                            Duplicar
                        </label>

                        <input class="form-check-input" type="radio" name="tp_cadastro" value="variacao"
                            id="variacao">
                        <label class="form-check-label" for="variacao">
                            Variação
                        </label>
                    </div>
                </div>
            </div>

            <div id="conteudo-categoria">
                <li class="list-group-item" id="conteudo-radio">
                    <div class="form-check">
                        <input type="radio" name="categoria_mercadolivre" value="10" id="radioCategoria">

                    </div>
                    <div class="form-check">
                        <input type="radio" name="categoria_mercadolivre" value="10" id="radioCategoria">

                    </div>
                    <div class="form-check">
                        <input type="radio" name="categoria_mercadolivre" value="10" id="radioCategoria">

                    </div>
                    <div class="form-check">
                        <input type="radio" name="categoria_mercadolivre" value="10" id="radioCategoria">

                    </div>
                    <div class="form-check">
                        <input type="radio" name="categoria_mercadolivre" value="10" id="radioCategoria">

                    </div>
                </li>
            </div>

            <div class="row p-4">
                <div class="col-md-6">
                    <div class="col">
                        <div class="mb-6 row">
                            <label class="col-lg-2 col-md-3 col-sm-6 col-form-label">Categorias:</label>
                            <select class="form-select" name="categoriaMl" id="categorias"
                                aria-label="Default select example">
                                <option selected>...</option>
                            </select>
                        </div>
                    </div>
                </div>


                <div class="card mt-4 bg-primary smartFiled d-none">
                    <h5 class="card-header">Campos Obrigatórios:</h5>
                    <div class="card-body">
                        <h5 class="card-title">Preencha por favor</h5>
                        <div id="formContainer"></div>
                    </div>
                </div>


                <input type="hidden" class="form-control" name="id_categoria" id="id_categoria">
                <hr class="mt-4">

                <h4>Histórico</h4>
                <div class="col-md-4 p-4">
                    <div class="col">
                        <div class="mb-3 row">
                            <ol class="list-group list-group-numbered content_categorias">

                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <input type="hidden" name="token" id="token">
            <input type="hidden" name="user" id="user">
            <input type="hidden" name="domain" id="domain">

            <div id="conteudo"></div>
            <hr>

            <div class="btn-group mt-4" role="group" aria-label="Botões">
                <button type="button" id="trocar" data-bs-toggle="modal" data-bs-target="#exampleModal"
                    class="btn btn-secondary x-2">Trocar <i class="bi bi-sunset-fill"></i></button>
                <input type="submit" class="btn btn-primary" value="Pesquisar">
            </div>
        </form>
    </div>

    <div class="modal fade" id="abrirModal22" aria-hidden="true" aria-labelledby="exampleModalToggleLabel"
        tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalToggleLabel">Seu Produto Tem Variações?</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Caso possua clique em Criar variações.
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button class="btn btn-primary" id="firstStep" data-bs-target="#abrirModal2"
                        data-bs-toggle="modal">Criar
                        Variações</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="abrirModal2" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2"
        tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalToggle">Aguardando Variação</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="VerificadoVariacao">
                    <div class="spinner-border spinner-border-sm" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    Aguardando você criar a variação... <span id="temporizador">120</span>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success d-none" id="secoundStep" data-bs-target="#exampleModalToggle3"
                        data-bs-toggle="modal">Subir Variações</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="exampleModalToggle3" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2"
        tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalToggleLabel3">Resultado</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="resultado_variacao">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    {{-- AJAX JQUERY SEARCH --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.theme.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.js"></script>

    <script>
        $(document).ready(function() {
            // Intercepta o submit do formulário
            $('#recadastrarForm').on('submit', function(e) {
                e.preventDefault(); // Impede o envio tradicional do formulário
                var $form = $(this);

                $.ajax({
                    url: $form.attr('action'),
                    type: $form.attr('method'),
                    data: $form.serialize(),
                    success: function(response) {
                        if (response.success) {
                            showToast(response.message, 'success');
                        } else {
                            showToast(response.message, 'error');
                        }
                    },
                    error: function(xhr) {
                        showToast("Erro ao recadastrar produto. Tente novamente.", 'error');
                    }
                });
                return false;
            });

            function showToast(message, type) {
                var alertClass = (type === 'success') ? 'alert-success' : 'alert-danger';
                var toastHTML = `
                    <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `;
                $('#toastMessageContainer').html(toastHTML);
            }

            const data = {
                "domains": [{
                        "domain_id": "MLB-SNEAKERS"
                    },
                    {
                        "domain_id": "MLB-BOOTS_AND_BOOTIES"
                    },
                    {
                        "domain_id": "MLB-SWIMWEAR"
                    },
                    // { "domain_id": "MLB-LOAFERS_AND_OXFORDS" },
                    // { "domain_id": "MLB-FOOTBALL_SHOES" },
                    // { "domain_id": "MLB-SANDALS_AND_CLOGS" },
                    // { "domain_id": "MLB-T_SHIRTS" },
                    //{ "domain_id": "MLB-JACKETS_AND_COATS" },
                    {
                        "domain_id": "MLB-DRESSES"
                    },
                    // { "domain_id": "MLB-SWEATSHIRTS_AND_HOODIES" },
                    // { "domain_id": "MLB-BLOUSES" },
                    {
                        "domain_id": "MLB-SHIRTS"
                    },
                    // { "domain_id": "MLB-PAJAMAS" },
                    // { "domain_id": "MLB-PANTS_TEST" },
                    {
                        "domain_id": "MLB-PANTS"
                    },
                    {
                        "domain_id": "MLB-BRAS"
                    },
                    // { "domain_id": "MLB-SHORTS" },
                    // { "domain_id": "MLB-LEGGINGS" },
                    // { "domain_id": "MLB-HEELS_AND_WEDGES" },
                    { "domain_id": "MLB-SLIPPERS" },
                    // { "domain_id": "MLB-FLATS" },
                    // { "domain_id": "MLB-ESPADRILLES" },
                    // { "domain_id": "MLB-HIKING_BOOTS" },
                    // { "domain_id": "MLB-TACTICAL_BOOTS" },
                    // { "domain_id": "MLB-NEOPRENE_BOOTS" },
                    // { "domain_id": "MLB-DANCE_SNEAKERS_AND_SHOES" },
                    // { "domain_id": "MLB-MOTORCYCLE_BOOTS" },
                    // { "domain_id": "MLB-BABIES_FOOTWEAR" },
                    // { "domain_id": "MLB-SAFETY_FOOTWEAR" },
                    // { "domain_id": "MLB-SPORT_T_SHIRTS" },
                    // { "domain_id": "MLB-SWEATERS_AND_CARDIGANS"},
                    // { "domain_id": "MLB-CYCLING_JERSEYS" },
                    // { "domain_id": "MLB-VESTS" },
                    // { "domain_id": "MLB-ROBES" },
                    // { "domain_id": "MLB-THERMAL_SHIRTS" },
                    // { "domain_id": "MLB-FISHING_SHIRTS" },
                    // { "domain_id": "MLB-FOOTBALL_JACKETS" },
                    // { "domain_id": "MLB-BASKETBALL_JERSEYS" },
                    // { "domain_id": "MLB-PONCHOS" },
                    // { "domain_id": "MLB-FASHION_KIMONOS" },
                    // { "domain_id": "MLB-FOOTBALL_SHIRTS" },
                    // { "domain_id": "MLB-AMERICAN_FOOTBALL_JERSEYS" },
                    // { "domain_id": "MLB-FOOTBALL_SWEATSHIRTS_AND_HOODIES" },
                    // { "domain_id": "MLB-BASEBALL_AND_SOFTBALL_JERSEYS" },
                    // { "domain_id": "MLB-FISHING_VESTS" },
                    // { "domain_id": "MLB-RUGBY_JERSEYS" },
                    // { "domain_id": "MLB-SCHOOL_SMOCKS" },
                    // { "domain_id": "MLB-NIGHTGOWNS" },
                    // { "domain_id": "MLB-SPORT_PANTS" },
                    // { "domain_id": "MLB-WADERS" },
                    // { "domain_id": "MLB-FISHING_PANTS" },
                    // { "domain_id": "MLB-TACTICAL_PANTS" },
                    // { "domain_id": "MLB-WORK_PANTS" },
                    // { "domain_id": "MLB-SPORT_SHORTS" },

                    // { "domain_id": "MLB-UNDERPANTS" },
                    // { "domain_id": "MLB-PANTIES" },
                    {
                        "domain_id": "MLB-SKIRTS"
                    },
                    // { "domain_id": "MLB-SPORT_SKIRTS" },
                    // { "domain_id": "MLB-TUTUS" },
                    // { "domain_id": "MLB-SNEAKERS_TEST" }
                ]
            };

            // Função para verificar se o domain_id existe
            function checkDomainId(value) {
                return data.domains.some(domain => domain.domain_id === value);
            }

            // Show loading indicator
            $("#loading").fadeIn();

            $('#titlenovo').on('keyup', function() {
                // Pegue o número de caracteres digitados
                var caracteresDigitados = $(this).val().length;
                // Atualize o contador de caracteres
                $('#contador').text(caracteresDigitados + '/60');
                // Atualize a barra de progresso
                var progresso = (caracteresDigitados / 60) * 100;
                $('#progress-bar').css('width', progresso + '%').attr('aria-valuenow', progresso);

                // Verificar o comprimento do valor inserido
                if ($(this).val().length > 60) {
                    // Se o comprimento exceder 60 caracteres, truncar o valor para 60 caracteres
                    $(this).val($(this).val().substr(0, 60));
                    // Alertar o usuário
                    alert("O valor não pode exceder 60 caracteres.");
                }
            });


            var selectElement = $("#selectSugestoes");
            selectElement.change(function() {
                // Pegue o valor do item selecionado
                var selectedValue = $(this).val();
                // Defina o valor do item selecionado no campo de entrada
                $('#titlenovo').val(selectedValue);
            });

            // PEGA O VALOR DIGITADO NO INPUT
            $("#nome_produto_by_user").on("keyup", function() {
                // Get the value of the input field
                var inputValue = $(this).val();
                $('#dataAnuncio').empty();
                $("#loading").fadeIn();
                getAllProducts($("#user").val(), inputValue, status = "active")
                // getProductByName(inputValue,$("#user").val(),$("#token").val());
            });

            $("#conteudo-categoria").attr("class", "d-none");

            $("input#radioCategoria").change(function() {
                $("#id_categoria").val(this.value);
                $("#categorias").attr("class", "d-none");
            });

            $('#formulario input[id=duplicar]').on('change', function() {
                alert("Preencha o nome do Novo Anúncio:");
                $("#titulo_anuncio").removeClass("d-none");
                $("#title_anuncio").css("background-color", "yellow").focus();
            });

            async function pegarToken() {
                try {
                    // Aguarde a conclusão da requisição assíncrona usando await
                    var resposta = await getToken();
                    var user = await getUserID();
                } catch (error) {
                    // Lidar com erros
                    console.log('Erro:', error);
                }
            }

            pegarToken();
            $("#inserir").click(function() {
                var item = $("#id").val()

                // Divide a string em códigos separados por vírgula
                const codigos = item.split(',');
                console.log(codigos);
                if (codigos !== "") {
                    // Itera sobre cada código e imprime no console
                    codigos.forEach(codigo => {
                        var listItem = $(`<li id="ids_li">${codigo.trim()}</li>`);
                        $("#titulo-anuncio").append(listItem);
                    });
                    $("#id").val("");
                }
            });

            $("#base").on("keyup", function() {
                // Get the value of the input field
                var inputValue = $(this).val();
                $("#titulo_anuncio_base").removeClass('d-none');
                getProduct(inputValue);
            });

            $("#pesquisar").click(function() {
                getCategoryForName($("#nome_produto").val());
            });

            $("#trocar").click(function() {

                const formContainer = document.getElementById('formContainer');
                const inputs = formContainer.querySelectorAll('input, select');
                const formData = [];

                inputs.forEach(input => {
                    const name = input.name;
                    const value = input.value;

                    let optionText = value; // Valor padrão para `name` no caso de ser um `input`

                    if (input.tagName === 'SELECT') {
                        // Pega o texto do <option> selecionado
                        const selectedOption = input.options[input.selectedIndex];
                        optionText = selectedOption ? selectedOption.text : "";
                    }

                    const entry = {
                        id: name,
                        values: [{
                            id: value,
                            name: optionText // Usa o texto do <option> selecionado
                        }]
                    };

                    formData.push(entry);
                });

                $("#resultadoServer").empty();
                var atributos = [];
                var tp_cadastro;
                var ids = [];
                var base = $("#base").val();
                // var title = $("#title_anuncio").val();
                var title = $("#titlenovo").val();
                var sList = "";
                $('input[type=checkbox]').each(function() {
                    atributos.push((this.checked ? $(this).val() : "not_checked"));
                });

                tp_cadastro = $("#formulario").find("input[name=tp_cadastro]:checked").val();

                var access_token = {{ Auth::user()->id }};
                $("li#ids_li").each(function(index, element) {
                    ids.push($(element).text());
                });
                if (base) {
                    console.log("BASE PREENCHIDA");
                    sendProductIdForServer(ids, base, title);
                } else {
                    console.log("BASE VAZIA");
                    // Exemplo de uso
                    const domain = $("#domain").val();
                    console.log(domain);
                    if (checkDomainId(domain)) {
                        $("li#ids_li").each(function(index, element) {
                            pushProduct($(element).text(), $("#token").val(), $("#id_categoria")
                                .val(), formData, true, domain);
                        });
                    } else {
                        $("li#ids_li").each(function(index, element) {
                            pushProduct($(element).text(), $("#token").val(), $("#id_categoria")
                                .val(), formData, false, domain);
                        });
                    }
                }
            });

            $("#formulario").submit(function(e) {
                // FUNCAO PARA PREENCHER TEXTAREA
                let categoriaFinal = $("#id_categoria").val();
                e.preventDefault();

            });

            $('#statusproduto').change(function() {
                var selectedValue = $(this).val();
                var inputValue = $('#nome_produto_by_user').val();
                $('#dataAnuncio').empty();
                $("#loading").fadeIn();
                var data = getAllProducts($("#user").val(), inputValue, selectedValue);
            });


            $("#logHistory").click(function() {
                $("#loadingHistorico").show();
                $("#conteudoHistorico").empty();
                setTimeout(() => {
                    var data = getAllHistoryByUser($("#user").val());
                }, 2000);
            });

            function extrairAnoMesDia(dataString) {
                // Criar um objeto Date com a data fornecida
                const data = new Date(dataString);

                // Extrair os componentes individuais da data
                const ano = data.getFullYear();
                const mes = data.getMonth() + 1; // Os meses são indexados de 0 a 11 em JavaScript
                const dia = data.getDate();

                // Retornar um objeto com ano, mês e dia
                return `${ano}/${mes}/${dia}`;
            }

            function getAllHistoryByUser($user) {

                $.ajax({
                    url: "/api/v1/getHistory",
                    type: "POST",
                    data: {
                        "user": $("#user").val()
                    },
                    success: function(response) {
                        if (response) {
                            // SHOW ALL RESULT QUERY
                            response.forEach(function(element) {
                                console.log(element.PRODUTO);
                                $("#conteudoHistorico").append(`

                                <div class="card mb-3">
                                        <div class="row g-0">
                                            <div class="col-md-4">
                                            <img src="${element.PRODUTO}" class="img-fluid rounded-start" alt="...">
                                            </div>
                                            <div class="col-md-8">
                                            <div class="card-body">
                                                <h5 class="card-title">Tarefa: ${element.ACAO}</h5>
                                                <p class="card-text">Data: ${extrairAnoMesDia(element.TEMPO)} Status: <span class="badge text-bg-${element.SUCESSO}">${element.SUCESSO}</span></p>
                                            </div>
                                            </div>
                                        </div>
                                        </div>
                                `);
                            });

                            $("#loadingHistorico").fadeOut();

                        }
                    },
                });

            }


            $("#botao_anuncio").click(function() {
                setTimeout(() => {
                    var data = getAllProducts($("#user").val(), "", "active");
                    // Hide loading indicator after 2 seconds

                }, 2000);
            });

            // FUNCAO PARA TROCAR DE CATEGORIA
            function pushProduct(product, accessToken, category, array, moda = false, domain = "") {

                var body = {
                    'user': $("#user").val(),
                    "id": product,
                    "categoria": category,
                    "required": array,
                    "token": accessToken,
                    "moda": moda,
                    "domain": domain
                };

                $.ajax({
                    url: `/api/v1/tradeCategoriaApi`,
                    type: "POST",
                    data: JSON.stringify(body),
                    headers: {
                        'Authorization': `Bearer ${accessToken}`,
                        "Content-Type": 'application/json',
                        'Accept': 'application/json'
                    },
                    success: function(response) {
                        console.log(response);
                        if (response) {
                            $("#resultadoServer").append(response);
                            // val("alterado com Sucesso!").css('background-color',
                            //     'yellow');
                        }
                    },
                    error: function(xhr, status, error) {
                        // Lógica para lidar com o erro
                        $("#relatorio_erro").val(xhr.responseText);
                        trataError(xhr.responseText);
                    }
                });
            }

            function getCategoryForName(name) {
                $.ajax({
                    url: "https://api.mercadolibre.com/sites/MLB/domain_discovery/search?limit=5&q=" + name,
                    type: "GET",
                    success: function(response) {
                        $("#conteudo-categoria").removeClass("d-none");
                        if (response) {
                            // SHOW ALL RESULT QUERY
                            var idCategoria = [];
                            var nomeCategoria = [];
                            response.forEach(function(element) {
                                idCategoria.push(element.category_id);
                                nomeCategoria.push(JSON.stringify(element.category_name)
                                    .replace(/["]/g, '') + " (" + (JSON.stringify(element
                                        .domain_name).replace(/["]/g, '')) + ") ");
                            });

                            $("input#radioCategoria").each(function(index, element) {
                                $(element).prop("value", idCategoria[index]);
                                $(element).after(
                                    `
                                    <label class="form-check-label" for="flexCheckDefault">
                                        ${nomeCategoria[index]}
                                    </label>
                                    `
                                );
                            });
                        }
                    },
                    complete: function(xhr, status) {
                        // Função executada sempre que a requisição é concluída (bem-sucedida ou com erro)
                        console.log('Código de Status:', xhr.status);
                    }
                });
            }

            function ConteudoCategory(category) {
                $.ajax({
                    url: "https://api.mercadolibre.com/categories/" + category,
                    type: "GET",
                    success: function(response) {
                        if (response) {
                            var tableHTML = '<table>' +
                                '<tr>' +
                                '<th>Preço</th>' +
                                '<th>Opção de Frete</th>' +
                                '<th>shipping profile</th>' +
                                '<th>simple shipping</th>' +
                                '</tr>' +
                                '<tr>' +
                                '<td>' + response.settings.price + '</td>' +
                                '<td>' + response.settings.shipping_options + '</td>' +
                                '<td>' + response.settings.shipping_profile + '</td>' +
                                '<td>' + response.settings.simple_shipping + '</td>' +
                                '</tr>' +
                                '</table>';
                            // SHOW ALL RESULT QUERY
                            $("#conteudo").append(tableHTML);
                        }
                    },
                    complete: function(xhr, status) {
                        // Função executada sempre que a requisição é concluída (bem-sucedida ou com erro)
                        console.log('Código de Status:', xhr.status);
                    }
                });
            }

            // FUNCAO PARA CHAMAR TOKE
            function getToken() {
                // console.log({{ Auth::user()->id }});
                $.ajax({
                    url: "/api/v1/getTokenMl",
                    type: "GET",
                    data: {
                        "id": {{ Auth::user()->id }}
                    },
                    success: function(response) {
                        if (response) {
                            // SHOW ALL RESULT QUERY
                            let tokenAccess = response.token;
                            $("#token").val(tokenAccess);
                        }
                    },
                });
            }

            // FUNCAO PARA CHAMAR USER ID
            function getUserID() {
                // console.log({{ Auth::user()->id }});
                $.ajax({
                    url: "/api/v1/getUserID",
                    type: "GET",
                    data: {
                        "id": {{ Auth::user()->id }}
                    },
                    success: function(response) {
                        if (response) {
                            // SHOW ALL RESULT QUERY
                            let userid = response.user;
                            $("#user").val(userid);
                        }
                    },
                });
            }

            $("#secoundStep").click(function() {

                var ids = [];
                var base = $("#base").val();
                var title = $("#title_anuncio").val();
                var sList = "";
                $("li#ids_li").each(function(index, element) {
                    sendProductIdForVariations($(element).text(), base);
                });


            });

            $("#firstStep").click(function() {

                var temporizador = document.getElementById('temporizador');

                var ativerIntervalo = function() {
                    temporizador.innerHTML = 120;
                    var intervalo = setInterval(function() {
                        var novoValor = parseInt(temporizador.innerHTML, 10) - 1;
                        temporizador.innerHTML = novoValor;

                        if (novoValor === 0) {
                            clearInterval(intervalo);
                            setTimeout(ativerIntervalo, 3000);
                        }

                        $("li#ids_li").each(function(index, element) {

                            $.ajax({
                                url: `https://api.mercadolibre.com/items/${$(element).text()}`,
                                type: "GET",
                                headers: {
                                    "Content-Type": 'application/json',
                                    'Accept': 'application/json',
                                    "Authorization": `Bearer ${$("#token").val()}`
                                },
                                success: function(response) {
                                    if (response) {
                                        if (response.variations.length >
                                            0) {
                                            $("#VerificadoVariacao").empty()
                                                .append(
                                                    `<div class="alert alert-success" role="alert">Variação Verificada com Sucesso</div>`
                                                );
                                            $("#secoundStep").removeClass(
                                                'd-none');
                                            clearTimeout(ativerIntervalo);
                                        }
                                    }
                                },
                                error: function(xhr, status, error) {
                                    // Lógica para lidar com o erro
                                    $("#relatorio_erro").val(xhr
                                        .responseText);
                                    trataError(xhr.responseText);
                                }
                            });
                        });

                    }, 1000);

                };
                ativerIntervalo();

            });

            // FUNCAO PARA CHAMAR TOKEN
            function sendProductIdForServer(data, base, newtitle) {
                $.ajax({
                    url: "/api/v1/tradeCategoria",
                    type: "POST",
                    data: {
                        "id": data,
                        "base": base,
                        'newtitle': newtitle,
                        "user": $("#user").val()
                    },
                    success: function(response) {
                        if (response == 200 || response == "200") {
                            // REMOVE O EFEITO HIDDEN
                            $("#abrirModal").removeClass('d-none');

                            $("#resultadoServer").append(
                                "<li class='list-group-item bg-success text-white'><i class='bi bi-check-circle-fill'></i> Anúncio Finalizado com Sucesso</li>"
                            );
                        } else {
                            $("#resultadoServer").append(
                                "<li class='list-group-item bg-warning text-dark' id='apagaError'><i class='bi bi-tools'></i> Arrumando Erros de Forma Recursiva..</li>"
                            );
                        }
                    },
                });
            }

            // FUNCAO PARA CHAMAR TOKEN
            function sendProductIdForVariations(data, base) {
                $.ajax({
                    url: "/api/v1/getAttributesForVariations",
                    type: "POST",
                    data: {
                        "id": data,
                        "base": base,
                        "user": $("#user").val()
                    },
                    success: function(response) {
                        console.log(response);
                        $("#resultado_variacao").append(response);
                    },
                });
            }

            // FUNÇÃO QUE PEGA O PRODUTO DIGITADO NO INPUT
            // FUNCAO PARA CHAMAR TOKEN
            function getProductByName(name = null, user_id = null, accessToken) {
                $.ajax({
                    url: `https://api.mercadolibre.com/sites/MLB/search?seller_id=${user_id}&q=${name}`,
                    type: "GET",
                    headers: {
                        'Authorization': `Bearer ${accessToken}`,
                        "Content-Type": 'application/json',
                        'Accept': 'application/json'
                    },

                    success: function(response) {
                        if (response) {
                            $("#dataAnuncio").empty();
                            console.log(response.results);
                            if (response.results.length == 0) {
                                $("#dataAnuncio").append(`
                                  <tr>
                                    <td scope="col"></td>
                                    <td scope="col"><i class="bi bi-zoom-out"></i></td>
                                    <td scope="col">Nenhum Resultado Encontrado</td>
                                    <td scope="col"></td>
                                    <td scope="col"></td>
                                  </tr>
                                `);
                            }
                            // SHOW ALL RESULT QUERY
                            response.results.forEach(function(element) {
                                $("#dataAnuncio").append(`
                                  <tr>
                                    <td scope="col"><img src="${element.thumbnail}"></td>
                                    <td scope="col">${element.id}</td>
                                    <td scope="col">${element.title}</td>
                                    <td scope="col">${element.price}</td>
                                    <td scope="col"> <input type="checkbox" name="item" value="${element.id}"></td>
                                  </tr>
                                `);
                            });

                        }
                    },
                });
            }
            // FUNCAO PARA CHAMAR TOKEN
            function getAllProducts(name = null, inputdata = "", status = "active") {
                $.ajax({
                    url: `/api/v1/getProductsApi?user=${name}&item=${inputdata}&status=${status}`,
                    // url: `https://api.mercadolibre.com/sites/MLB/search?status=${status}&seller_id=s${name}`,
                    type: "GET",

                    success: function(response) {
                        if (response) {
                            $("#loading").fadeOut();
                            $('#dataAnuncio').empty();
                            // SHOW ALL RESULT QUERY
                            response.results.forEach(function(element) {
                                console.log(element);
                                $("#dataAnuncio").append(`
                                  <tr>
                                    <td scope="col"><img src="${element.thumbnail}"></td>
                                    <td scope="col">${element.id}</td>
                                    <td scope="col">${element.title}</td>
                                    <td scope="col">${element.price}</td>
                                    <td scope="col"><input type="checkbox" name="item" value="${element.id}"></td>
                                  </tr>
                                `);
                            });

                        }
                    },
                });
            }

            //https://api.mercadolibre.com/items/MLB3226359198
            // FUNCAO PARA CHAMAR PRODUTO
            function getProduct(product) {
                // $("#foto_anuncio").empty();
                $("#titlenovo").empty();

                $.ajax({
                    url: "https://api.mercadolibre.com/items/" + product,
                    type: "GET",
                    headers: {
                        "Authorization": `Bearer ${$("#token").val()}`
                    },
                    success: function(response) {
                        if (response) {
                            if (response.variations.length > 0) {
                                $("#variacao").prop("checked", true);
                            } else {
                                $("#variacao").prop("checked", false);
                            }
                            $("#titlenovo").val(response.title)
                                .css("background-color", "yellow")
                                .focus();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Erro na requisição:", error);
                    }
                });

            }

            function getSugestoes(title) {
                // $("#foto_anuncio").empty();
                $("#titlenovo").empty();

                $.ajax({
                    url: `https://api.mercadolibre.com/products/search?status=active&site_id=MLB&q=${title}`,
                    type: "GET",
                    success: function(response) {
                        if (response) {
                            var sugestoesdiv = $("#div-sugestoes");
                            var selectElement = $("#selectSugestoes");

                            selectElement.append($('<option>').text(title).css("background-color",
                                "#03fc45"));

                            // Adicionar barra separadora
                            selectElement.append($('<option disabled>').text("--------------"));
                            // Iterar sobre cada item em response.results
                            $.each(response.results, function(i, item) {
                                // Criar um novo elemento <option> com o valor de item.name
                                var optionElement = $('<option>').text(item.name);
                                // Adicionar o elemento <option> ao elemento <select>
                                selectElement.append(optionElement);
                            });


                        }
                    },
                });
            }

            // FUNCAO PARA CHAMAR CATEGORIAS
            function getCategory(category) {
                var domain = "";
                $.ajax({
                   url: "/meli/subcategories/" + category,
                    type: "GET",
                    success: function(response) {

                        domain = response.settings.catalog_domain;
                        if (response) {
                            // SHOW ALL RESULT QUERY
                            var index = [];
                            $.each(response.children_categories, function(i, item) {
                                index[i] =
                                    '<option class="option-size" value=' + item.id +
                                    '>  - </option><option class="option-size" value=' + item
                                    .id + '>' +
                                    item.name + '</option>';
                            });

                            var arr = jQuery.makeArray(index);
                            arr.reverse();

                            if (index.length <= 1) {
                                // Remover a classe d-none para mostrar o elemento select
                                $(".smartFiled").removeClass('d-none');
                                $.ajax({
                                    url: "/meli/subcategories/attributes/" + category,
                                    type: "GET",
                                    success: function(response) {
                                        if (response) {
                                            // COMPLETA O DOMINIO
                                            $("#domain").val(domain);
                                            const requiredItems = [];
                                            const requiredAttributeNames = [];

                                            response.forEach(item => {
                                                if (item.tags && item.tags
                                                    .required === true && !
                                                    requiredAttributeNames.includes(
                                                        item.id)) {
                                                    requiredItems.push(item);
                                                }
                                            });

                                            requiredItems.forEach(element => {
                                                // Adiciona o label
                                                var label = document.createElement(
                                                    "label");
                                                label.textContent = element.name;
                                                formContainer.appendChild(label);

                                                if (label.textContent == "Marca") {
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
                                                } else
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
            var i = 0;
            $.ajax({
               url: "/meli/categories",
                type: "GET",
                success: function(response) {
                    if (response) {
                        // SHOW ALL RESULT QUERY
                        var index = [];
                        $.each(response, function(i, item) {
                            index[i] = '<option class="option-size" value=' + '' +
                                ' > - </option><option class="option-size" value=' + item.id +
                                '>' +
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

        });
    </script>
</body>

</html>
