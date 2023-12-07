@extends('layouts.layout')
@section('title', $viewData['title'])
@section('subtitle', $viewData['subtitle'])
@section('conteudo')
    <div class="card-body" id="displayCart">
        <!-- Button trigger modal -->
        <input type="hidden" id="modalbutton" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Aviso! <i class="bi bi-exclamation-triangle"></i></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Produto Não Tem Estoque Suficiente para a quantidade colocada no carrinho!
                        <i class="bi bi-cart-x"></i>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>

        <!--- MODAL QUE SELECIONA O MOTORISTA --->
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
                                            <input class="form-check-input" type="checkbox" value=""
                                                id="flexCheckChecked" checked>
                                            <label class="form-check-label" for="flexCheckChecked">
                                                Mercado Livre
                                            </label>
                                        </div>
                                    </button>
                                </h2>

                                <div id="flush-collapseOne" class="accordion-collapse collapse"
                                    aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body">
                                        <form method="POST" action="{{ route('IntegrarProduto') }}"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="col-md-12">
                                                <!---  ID DO PRODUTO --->
                                                <input type="hidden" name="id_product" id="id_product"
                                                    value="{{ $viewData['product']->id }}">
                                                <div class="row">
                                                    <div class="col">
                                                        <div class="mb-3 row">
                                                            <label
                                                                class="col-lg-2 col-md-6 col-sm-12 col-form-label">Nome:</label>
                                                            <div class="col-lg-10 col-md-6 col-sm-12">
                                                                <input name="name" id="name"
                                                                    value="{{ $viewData['product']->title }}" type="text"
                                                                    class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <p class="col-lg-2 col-md-6 col-sm-12 col-form-label">Acréssimo </p>
                                                    <div class="col">
                                                        <div class="mb-3 row">
                                                            <label
                                                                class="col-lg-2 col-md-6 col-sm-12 col-form-label">%</label>
                                                            <div class="col-lg-3 col-md-6 col-sm-6">
                                                                <input id="acressimoP" class="form-control porcem">
                                                            </div>
                                                            <label
                                                                class="col-lg-2 col-md-6 col-sm-12 col-form-label">R$</label>
                                                            <div class="col-lg-3 col-md-6 col-sm-6">
                                                                <input id="acressimoR" type="text"
                                                                    class="form-control porcem">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <p class="col-lg-2 col-md-6 col-sm-12 col-form-label">Desconto </p>
                                                    <div class="col">
                                                        <div class="mb-3 row">
                                                            <label
                                                                class="col-lg-2 col-md-6 col-sm-12 col-form-label">%</label>
                                                            <div class="col-lg-3 col-md-6 col-sm-6">
                                                                <input id="descontoP" type="text"
                                                                    class="form-control porcem">
                                                            </div>
                                                            <label
                                                                class="col-lg-2 col-md-6 col-sm-12 col-form-label">R$</label>
                                                            <div class="col-lg-3 col-md-6 col-sm-6">
                                                                <input id="descontoR" type="text"
                                                                    class="form-control porcem">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <p class="col-lg-4 col-md-6 col-sm-12 col-form-label">Tipo de Anúncio
                                                    </p>
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
                                                                <input name="price" id="precoFinal"
                                                                    value="{{ $viewData['product']->price }}"
                                                                    type="text" class="form-control">
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

                                            <input type="hidden" class="form-control" name="id_categoria"
                                                id="id_categoria">

                                            <button type="submit" class="btn btn-success mt-4">Finalizar
                                                Integração</button>
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
                                    <div class="accordion-body">Placeholder content for this accordion, which is intended
                                        to
                                        demonstrate the <code>.accordion-flush</code> class. This is the second item's
                                        accordion
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
                                    <div class="accordion-body">Placeholder content for this accordion, which is intended
                                        to
                                        demonstrate the <code>.accordion-flush</code> class. This is the third item's
                                        accordion
                                        body. Nothing more exciting happening here in terms of content, but just filling up
                                        the
                                        space to make it look, at least at first glance, a bit more representative of how
                                        this
                                        would look in a real-world application.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--- FINAL DO MODAL ---->


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
        <form method="POST" id="formulario" action="{{ route('cart.add', ['id' => $viewData['product']->getId()]) }}">
            <div class="row mt-4">
                @csrf
                <div class="row p-2 bg-white border rounded">
                    <!--- FOTOS ADICIONAIS  --->
                    <div class="row-md">
                        @if ($viewData['imageJson'])
                            @foreach (json_decode($viewData['imageJson']) as $foto)
                                <img src="{{ $foto->url }}" class="tamanho-fotos fotoProduto" alt="...">
                            @endforeach
                        @else
                            @foreach ($viewData['images'] as $foto)
                                <img src="{!! Storage::disk('s3')->url('produtos/' . $viewData['product']->getId() . '/' . $foto) !!}" class="tamanho-fotos fotoProduto" alt="...">
                            @endforeach
                        @endif

                    </div>
                    <!--- FINAL FOTOS ADICIONAIS  --->
                    <div class="col-md-3 mt-1 receivedPhoto">
                        @if (json_decode($viewData['imageJson']))
                            <img class="img-fluid img-responsive rounded product-image tradeFoto"
                                src="{{ json_decode($viewData['imageJson'])[0]->url }}">
                        @else
                            <img class="img-fluid img-responsive rounded product-image tradeFoto"
                                src="{!! Storage::disk('s3')->url('produtos/' . $viewData['product']->getId() . '/' . $viewData['image']) !!}">
                        @endif
                    </div>
                    <div class="col-md-6">
                        <h5>{{ $viewData['product']->getName() }}</h5>
                        <!--- comissao e fixo  --->
                        <div class="row">
                            <div class="col-md-3">
                                <div>SKU:</div>
                                <input type="text" min="1" id="sku" class="form-control quantity-input"
                                    disabled value="{{ $viewData['product']->getId() }}">
                            </div>
                        </div>

                        <div class="col-md-8">
                            <h6>Métricas KM</h6>
                            <div id='myChart' class=""></div>
                            <script>
                                ZC.LICENSE = ["569d52cefae586f634c54f86dc99e6a9", "b55b025e438fa8a98e32482b5f768ff5"];
                                window.feed = function(callback) {
                                    var tick = {};
                                    tick.plot0 = Math.ceil(350 + (Math.random() * 500));
                                    callback(JSON.stringify(tick));
                                };

                                var myConfig = {
                                    type: "gauge",
                                    globals: {
                                        fontSize: 15
                                    },
                                    plotarea: {
                                        marginTop: 60
                                    },
                                    plot: {
                                        size: '100%',
                                        valueBox: {
                                            placement: 'center',
                                            text: '%v', //default
                                            fontSize: 35,
                                            rules: [{
                                                    rule: '%v >= 120',
                                                    text: 'ALTO KM'
                                                },
                                                {
                                                    rule: '%v > 60 && %v < 120',
                                                    text: 'MÉDIO KM'
                                                },
                                                {
                                                    rule: '%v <= 60',
                                                    text: 'BAIXO KM'
                                                }
                                            ]
                                        }
                                    },
                                    tooltip: {
                                        borderRadius: 5
                                    },
                                    scaleR: {
                                        aperture: 180,
                                        minValue: 10,
                                        maxValue: 150,
                                        step: 14.5,
                                        center: {
                                            visible: false
                                        },
                                        tick: {
                                            visible: false
                                        },
                                        item: {
                                            offsetR: 0,
                                            rules: [{
                                                rule: '%i == 10',
                                                offsetX: 15
                                            }]
                                        },
                                        labels: ['0', '', '30', '90', '60', '', '90', '640', '120', '750', '', '150'],
                                        ring: {
                                            size: 40,
                                            rules: [{
                                                    rule: '%v <= 20',
                                                    backgroundColor: '#E53935'
                                                },
                                                {
                                                    rule: '%v > 20 && %v < 55',
                                                    backgroundColor: '#ff8e18'
                                                },
                                                {
                                                    rule: '%v >= 55 && %v < 85',
                                                    backgroundColor: '#fffa00'
                                                },
                                                {
                                                    rule: '%v >= 85 && %v < 110',
                                                    backgroundColor: '#83eb38'
                                                },
                                                {
                                                    rule: '%v >= 110',
                                                    backgroundColor: '#00B414'
                                                }
                                            ]
                                        }
                                    },
                                    // refresh: {
                                    //     type: "feed",
                                    //     transport: "js",
                                    //     url: "feed()",
                                    //     interval: 1500,
                                    //     resetTimeout: 1000
                                    // },
                                    series: [{
                                        values: [{{ $viewData['product']->termometro }}], // starting value
                                        backgroundColor: 'black',
                                        indicator: [10, 10, 10, 10, 0.75],
                                        animation: {
                                            effect: 2,
                                            method: 7,
                                            sequence: 2,
                                            speed: 10000
                                        },
                                    }]
                                };
                                zingchart.render({
                                    id: 'myChart',
                                    data: myConfig,
                                    height: 350,
                                    width: '100%',
                                });
                            </script>

                        </div>
                    </div>
                    <div class="align-items-center align-content-center col-md-3 border-left mt-1">
                        <div class="d-flex flex-row align-items-center">
                            @if ($viewData['product']->getPricePromotion() > 0)
                                <div class="col-md-12">
                                    <p class="text-danger margin-negative"><s>De R$:
                                            {{ $viewData['product']->getPrice() }}</s></p>
                                    <h4 class="text-success">Por R$: {{ $viewData['product']->getPricePromotion() }} </h4>
                                </div>
                            @else
                                <div class="col-md-3">
                                    <p>Valor:</p>
                                </div>
                                <div class="col-md-6">
                                    <h2 class="text-success margin-negative-maior">
                                        R$: {{ number_format($viewData['product']->getPrice(), 2, ',', '.') }}</h2>
                                </div>
                            @endif
                        </div>

                        <div class="col-md-12">
                            <div>Quantidade:</div>
                            <input type="number" min="1" id="quantity" name="quantity"
                                class="form-control quantity-input" value="1">
                        </div>

                        <div class="d-flex flex-column mt-4">
                            <button class="btn btn-outline-primary bg-primary text-white btn-sm mt-2"
                                type="submit">Comprar</button>
                            <button class="btn btn-outline-primary btn-sm mt-2" type="submit">Adicionar ao
                                Carrinho</button>
                        </div>
                        <!--- Desconto e fixo  --->
                        <div class="d-flex flex-column mt-4"">

                            <label>Material de Apoio / Dúvidas</label>
                            <button class="btn btn-primary">Material de apoio <i class="bi bi-archive-fill"></i></button>

                            <!--- Botões dos marketplaces  --->
                            <div class="row">
                                <input type="hidden" id="total"
                                    value="{{ number_format($viewData['product']->price, 2, ',', '.') }}">
                                {{-- <input type="hidden" name="id_user" id="id_user" value="{{ Auth::user()->id }}"> --}}
                                <input type="hidden" name="id_produto" id="id_produto"
                                    value="{{ $viewData['product']->id }}">
                                <div class="col-md-12">
                                    <div class="div mt-2">
                                        {{-- @if ($viewData['token'])
                                            <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                                href="#exampleModalToggle"><i class='bi bi-arrow-left-right'></i> &nbsp;
                                                INTEGRAR</button>
                                        @else --}}
                                            <button class="btn btn-secondy" disabled></button>
                                        {{-- @endif --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--- Final Desconto e fixo  --->
                    </div>
                    <!--- final botões marketplaces --->
                    <div class="card py-2 mt-4">
                        <div class="card-body">
                            <div class="negrito">Descrição do Produto</div>
                            <p class="text-justify">{{ $viewData['product']->getDescription() }}</p>
                        </div>
                    </div>
                </div>
        </form>
    </div>
    </div>

@endsection


{{-- AJAX JQUERY SEARCH --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.theme.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.3/jquery.mask.min.js"></script>


<script>
    $(document).ready(function() {
        var valorProduto = 0;
        var i = 0;
        var quantity = $("#quantity").val();
        var stock = $("#stock").val();

        $("img.fotoProduto").mouseenter(function() {
            $(this).fadeOut(100);
            $(this).fadeIn(500);
            $(this).css({
                border: "2px solid red",
                width: "58px",
                height: "58px"
            });

            var images = $(this).attr('src');
            $('.product-image').attr('src', images);

        }).mouseleave(function() {
            $(this).fadeOut(100);
            $(this).fadeIn(500);
            $(this).css({
                border: "1px solid black",
                width: "58px",
                height: "58px"
            });

        });


        $("tr#linhasProduct").click(function() {
            // LIMPA O HISTORICO
            $('.adicionarHistorico').empty();

            id_produto = $(this).children(".id_product").text();
            var id_user = $('#id_user').val();
            $.ajax({
                url: "/api/v1/product/" + id_produto,
                type: "GET",
                success: function(response) {
                    if (response) {
                        valorProduto = response.price;
                        $("#total").val(response.price);
                        $("#name").val(response.title);
                        $("#precoFinal").val(response.price);
                    }
                },
                error: function(error) {
                    $('#result').html(
                        '<option> Produto Digitado Não Existe! </option>'
                    );
                }
            });

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
            console.log(total);
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

        //$('#total').val($('#valorTotalInput').val());
    });
</script>
