<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <title>{{ $viewData['title'] }}</title>
</head>

<body>

    <div class="container mt-4">

        <h2>Mercado Livre Alterador de Categoria:</h2>

        <form id="formulario">
            <div class="input-group">
                <span class="input-group-text">Anúncio / Anúncio Base</span>
                <input type="text" class="form-control" id="id"
                    placeholder="Código do anúncio que sofrerá Mudança." />
                <input type="text" class="form-control" id="base" placeholder="Código do anúncio base." />
                <div class="input-group-append">
                    <button class="btn btn-primary" id="inserir" type="button">Inserir</button>
                </div>
            </div>

            <span class="badge text-bg-info text-white mt-4">
                <h5>{{ $viewData['auth'] }}</h5>
            </span>

            <ol id="titulo-anuncio" class="mt-4"></ol>

            <div class="row p-6 mt-4">
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="text" class="form-control" id="nome_produto"
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

            <div id="conteudo"></div>

            <div class="row">
                <div class="col-sm-6">
                    <h5>Sucesso:</h5>
                    <textarea class="form-control" id="relatorio_sucesso" rows="4"></textarea>
                </div>
                <div class="col-sm-6">
                    <h5>error:</h5>
                    <textarea class="form-control" id="relatorio_erro" rows="4"></textarea>
                </div>

                <div class="col-sm-6">
                    <h5>Novos Atributos</h5>
                    <textarea class="form-control" id="new_atributos" rows="4"></textarea>
                </div>
            </div>
            <hr>

            <div class="btn-group mt-4" role="group" aria-label="Botões">
                <button type="button" id="trocar" class="btn btn-secondary x-2">Trocar <i
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
                } catch (error) {
                    // Lidar com erros
                    console.log('Erro:', error);
                }
            }

            pegarToken();

            $("#inserir").click(function() {
                var item = getProduct($("#id").val());
                if (item !== "") {
                    var listItem = $(`<li id="ids_li">${$("#id").val()}</li>`);
                    $("#titulo-anuncio").append(listItem);
                    $("#id").val("");
                }
            });

            $("#pesquisar").click(function() {
                getCategoryForName($("#nome_produto").val());
            });

            $("#trocar").click(function() {

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
                    sendProductIdForServer(ids, base, access_token,atributos,tp_cadastro,title);
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
                //console.log(ConteudoCategory(categoriaFinal));
                e.preventDefault();

            });

            function pushProduct(product, accessToken, category) {

                var body = {
                    "category_id": category
                };

                $.ajax({
                    url: `https://api.mercadolibre.com/items/${product}`,
                    type: "PUT",
                    data: JSON.stringify(body),
                    headers: {
                        'Authorization': `Bearer ${accessToken}`,
                        "Content-Type": 'application/json',
                        'Accept': 'application/json'
                    },
                    success: function(response) {
                        if (response) {
                            $("#relatorio_sucesso").val("alterado com Sucesso!").css('background-color',
                                'yellow');
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

            function trataError(data) {
                $.ajax({
                    url: "https://melimaximo.com.br/api/v1/trataErroMl",
                    type: "POST",
                    data: {
                        "data": {
                            "message": "Validation error",
                            "error": "validation_error",
                            "status": 400,
                            "cause": [{
                                "department": "items",
                                "cause_id": 147,
                                "type": "error",
                                "code": "item.attributes.missing_required",
                                "references": ["item.attributes",
                                    "item.variations.attribute_combinations"
                                ],
                                "message": "The attributes [MOUNTING_TYPE, OVEN_TYPES] are required for category MLB120314 and channel marketplace. Check the attribute is present in the attributes list or in all variation's attributes_combination or attributes."
                            }]
                        }
                    },
                    success: function(response) {
                        let jsonString = JSON.stringify(response);
                        let jsonSemColchetes = jsonString.slice(1, -1);

                        if (response) {
                            $("#new_atributos").val(jsonSemColchetes);
                        }
                    }
                });
            }

            // FUNCAO PARA CHAMAR TOKE
            function getToken() {
                console.log({{ Auth::user()->id }});
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

            // FUNCAO PARA CHAMAR TOKE
            function sendProductIdForServer(data, base, access_token,atributos,tp_cadastro = "N/D",title = "N/D") {
                $.ajax({
                    url: "https://melimaximo.com.br/api/v1/getAttributesById",
                    type: "POST",
                    data: {
                        "id": data,
                        "base": base,
                        "auth": access_token,
                        "atributos": atributos,
                        "tp_cadastro": tp_cadastro,
                        "title": title
                    },
                    success: function(response) {
                        if (response) {
                            // SHOW ALL RESULT QUERY
                            $("#relatorio_sucesso").val(JSON.stringify(response));
                            console.log(response);
                        }
                    },
                });
            }

            //https://api.mercadolibre.com/items/MLB3226359198
            // FUNCAO PARA CHAMAR PRODUTO
            function getProduct(product) {
                $.ajax({
                    url: " https://api.mercadolibre.com/items/" + product,
                    type: "GET",
                    success: function(response) {
                        if (response) {
                            // SHOW ALL RESULT QUERY
                            //$("#titulo-anuncio").append(product);
                            $("#imagem-anuncio").attr('src', `${response.thumbnail}`).removeClass(
                                "d-none");
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
