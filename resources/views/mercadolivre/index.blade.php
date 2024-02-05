<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
        <title>{{ $viewData['title'] }}</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
</head>

<body>
    <div class="container mt-4">

          <button class="btn btn-primary" type="button" id="logHistory" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
            Histórico
          </button>

          <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
            <div class="offcanvas-header">
              <h5 class="offcanvas-title" id="offcanvasExampleLabel"><div class="spinner-border text-primary" id="loadingHistorico" role="status">
                <span class="visually-hidden">Loading...</span>
              </div> Últimos Registros </h5>
              <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body" id="conteudoHistorico">
              <div>
                 {{-- IMPLEMENTAR LOG --}}
              </div>
            </div>
          </div>

        <h2>Mercado Livre Alterador de Categoria:</h2>
            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
                </div>
            </div>

        <div class="modal fade modal-xl" id="exampleModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content">
                <div class="modal-header">
                  <h1 class="modal-title fs-5" id="exampleModalToggleLabel">Pesquisar Produtos </h1>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- CONTENT MODAL START --}}

                    <div id="loading">
                        <button class="btn btn-success" type="button" disabled>
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Loading...
                          </button>
                    </div>

                    <div class="col-md-3">
                        <label for="validationCustom04" class="form-label">Status dos Anúncios</label>
                        <select class="form-select" id="statusproduto">
                          <option value="active" selected>Ativo</option>
                          <option value="paused">Inativo</option>
                        </select>
                        <div class="invalid-feedback">
                          Please select a valid state.
                        </div>
                      </div>

                        <div class="input-group mt-2">
                            <input type="text" class="form-control" id="nome_produto_by_user"
                                placeholder="Nome da categoria..." />

                            <div class="input-group-append">
                                <button class="btn btn-primary" id="pesquisar" type="button">Pesquisar</button>
                            </div>

                            <table class="table mt-4">
                                <thead>
                                  <tr>
                                    <th scope="col">FOTO</th>
                                    <th scope="col">ID</th>
                                    <th scope="col">IMAGEM</th>
                                    <th scope="col">PREÇO</th>
                                    <th scope="col">SELECIONAR</th>
                                  </tr>
                                </thead>
                                <tbody id="dataAnuncio">


                                </tbody>
                              </table>

                    {{-- CONTENT MODAL END --}}
                </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" id="inserir" type="button">Inserir</button>
                </div>
              </div>
            </div>
          </div>

          <div class="modal fade" id="exampleModalToggle2" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content">
                <div class="modal-header">
                  <h1 class="modal-title fs-5" id="exampleModalToggleLabel2">Modal 2</h1>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  Hide this modal and show the first with the button below.
                </div>
                <div class="modal-footer">
                  <button class="btn btn-primary" data-bs-target="#exampleModalToggle" data-bs-toggle="modal">Back to first</button>
                </div>
              </div>
            </div>
          </div>

        <form id="formulario">
            <div class="input-group">

                <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary" id="botao_anuncio"data-bs-toggle="modal" href="#exampleModalToggle">
                Pesquisar Anúncio <i class="bi bi-search"></i>
            </button>

                <input type="text" class="form-control" id="base" placeholder="Código do anúncio base." />

                <div class="card" id="card_produto_receive" style="width: 400px; height:100px;">
                    <div class="row">
                    <div class="col-md-4">
                        <img src="" id="foto_anuncio" class="img-fluid rounded-start py-2">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                        <h5 class="card-title" id="title_anuncio_insert"></h5>
                        </div>
                    </div>
                    </div>
                </div>
                </div>
            <span class="badge text-bg-info text-white mt-4">
                <h5>{{ $viewData['auth'] }}</h5>
            </span>


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
                    Selecione os Atributos
                </div>
                <div class="card-body">
                    <h5 class="card-title">Atributos transferido</h5>
                    <div class="container mt-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="radioName" value="descricao"
                                id="checkbox_id">
                            <label class="form-check-label" for="descricao">
                                Descrição
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="radioName" value="fotos" id="checkbox_id">
                            <label class="form-check-label" for="fotos">
                                Fotos
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="radioName" value="info" id="checkbox_id">
                            <label class="form-check-label" for="info">
                                Ficha Técnica
                            </label>
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

                            <input class="form-check-input" type="radio" name="tp_cadastro" value="variacao" id="variacao">
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

            <div id="conteudo"></div>
            <hr>

            <div class="btn-group mt-4" role="group" aria-label="Botões">
                <button type="button" id="trocar"  data-bs-toggle="modal" data-bs-target="#exampleModal" class="btn btn-secondary x-2">Trocar <i
                        class="bi bi-sunset-fill"></i></button>
                <input type="submit" class="btn btn-primary" value="Pesquisar">
            </div>
        </form>
    </div>

    {{-- AJAX JQUERY SEARCH --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.theme.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.js"></script>

    <script>
        $(document).ready(function() {


            setTimeout(function(){
                $("#apagaError").hide();
            }, 1000); // 2000 milliseconds = 2 seconds

            // Show loading indicator
            $("#loading").fadeIn();

            // PEGA O VALOR DIGITADO NO INPUT
            $("#nome_produto_by_user").on("keyup", function() {
            // Get the value of the input field
                var inputValue = $(this).val();
                $('#dataAnuncio').empty();
                $("#loading").fadeIn();
                getAllProducts($("#user").val(), inputValue,status = "active")
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
                $("#title_anuncio").css("background-color","yellow").focus();
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

                 // Find all checked checkboxes and append their values to the list
                $(":checkbox:checked").each(function() {
                    let value = $(this).val();
                    if (value !== "") {
                    var listItem = $(`<li id="ids_li">${value}</li>`);
                }

                $("#titulo-anuncio").append(listItem);
                $("#exampleModalToggle").modal("hide");
                    // $("#selectedItems").append(`<li>${value}</li>`);
                });

                // var item = getProduct($("#id").val());

            });

            $("#base").on("keyup", function() {
                // Get the value of the input field
                var inputValue = $(this).val();
                getProduct(inputValue);
            });

            $("#pesquisar").click(function() {
                getCategoryForName($("#nome_produto").val());
            });

            $("#trocar").click(function() {

                $("#resultadoServer").empty();

                var atributos = [];
                var tp_cadastro;
                var ids = [];
                var base = $("#base").val();
                var title = $("#title_anuncio").val();
                var sList = "";
                $('input[type=checkbox]').each(function () {
                    atributos.push((this.checked ?  $(this).val() : "not_checked"));
                });

                tp_cadastro = $("#formulario").find("input[name=tp_cadastro]:checked").val();

                var access_token = {{ Auth::user()->id }};
                $("li#ids_li").each(function(index, element) {
                    ids.push($(element).text());
                });
                if (base) {
                    console.log("BASE PREENCHIDA");
                    sendProductIdForServer(ids, base);
                } else {
                    console.log("BASE VAZIA");
                    $("li#ids_li").each(function(index, element) {
                        pushProduct($(element).text(), $("#token").val(), $("#id_categoria").val());
                    });

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
                var data = getAllProducts($("#user").val(), inputValue,selectedValue);
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

            function getAllHistoryByUser($user){

                $.ajax({
                    url: "https://melimaximo.com.br/api/v1/getHistory",
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
                    var data = getAllProducts($("#user").val(),"","active");
                // Hide loading indicator after 2 seconds

                }, 2000);
            });

            // FUNCAO PARA TROCAR DE CATEGORIA
            function pushProduct(product, accessToken, category) {

            var body = {
                'user': $("#user").val(),
                "id": product,
                "categoria": category
            };

            $.ajax({
                url: `https://melimaximo.com.br/api/v1/tradeCategoria`,
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
                    url: " https://api.mercadolibre.com/categories/" + category,
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
                    url: "https://melimaximo.com.br/api/v1/getTokenMl",
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
                    url: "https://melimaximo.com.br/api/v1/getUserID",
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

            // FUNCAO PARA CHAMAR TOKEN
            function sendProductIdForServer(data, base) {
                $.ajax({
                    url: "https://melimaximo.com.br/api/v1/tradeCategoria",
                    type: "POST",
                    data: {
                        "id": data,
                        "base": base,
                        "user": $("#user").val()
                    },
                    success: function(response) {
                        console.log(response);
                        if (response) {
                            // SHOW ALL RESULT QUERY
                            $("#resultadoServer").append(response);
                        }
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
                            if(response.results.length == 0){
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
             function getAllProducts(name = null, inputdata = "",status = "active") {
                $.ajax({
                    url: `https://melimaximo.com.br/api/v1/getProductsApi?user=${name}&item=${inputdata}&status=${status}`,
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
                $("#foto_anuncio").empty();
                $("#title_anuncio_insert").empty();

                $.ajax({
                    url: " https://api.mercadolibre.com/items/" + product,
                    type: "GET",
                    success: function(response) {
                        if (response) {
                            if(response.variations.length > 0){
                                $("#variacao").prop("checked", true);
                            }else{
                                $("#variacao").prop("checked", false);
                            }
                            // SHOW ALL RESULT QUERY
                            //$("#titulo-anuncio").append(product);
                            $("#foto_anuncio").attr('src', `${response.thumbnail}`);
                            $("#title_anuncio_insert").append(response.title);
                        }
                    },
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
                            $.each(response.children_categories, function(i, item) {
                                index[i] =
                                    '<option class="option-size" value=' + item.id + '>' + item
                                    .name + '</option>';
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
            }
            var i = 0;
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

        });
    </script>
</body>

</html>
