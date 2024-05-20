@extends('layouts.app')
@section('title', $viewData['title'])
@section('subtitle', $viewData['subtitle'])
@section('conteudo')

    <div class="container-fluid px-4">
        <h2 class="mt-4">Vendas</h2>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item badge bg-success">Usuário: {{ Auth::user()->name }}</li>
        </ol>
        <div class="row">
            <div class="container">

                  <!-- Copy until here -->

                </div>
                <div class="col-md-6 col-lg-12 pb-3">


                        <!--Table-->
                        <table class="table table-hover table-forum text-center">
                            <!--Table head-->

                            <!--Table body-->
                            <tbody>
                                <div class="input-group mb-4">
                                    <input type="text" class="form-control" id="advanced-search-input"
                                        placeholder="Nome do Cliente, Código do Pedido" />
                                    <button class="btn btn-primary" id="advanced-search-button" type="button">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                                <div class="accordion accordion-flush" id="accordionFlushExample">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="flush-headingOne">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#flush-collapseOne"
                                                aria-expanded="false" aria-controls="flush-collapseOne">
                                                Filtros Avançado
                                            </button>
                                        </h2>
                                        <div id="flush-collapseOne" class="accordion-collapse collapse"
                                            aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                            <div class="accordion-body">
                                                {{-- FILTROS AVANCADOS  --}}
                                                <form action="{{ route('orders.index') }}" method="get">
                                                    <div class="row">
                                                        <div class="col-md-2">
                                                            <label for="validationCustom01" class="form-label">Número do
                                                                Pedido</label>
                                                            <input type="text" name="npedido" class="form-control"
                                                                id="validationCustom01">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label for="validationCustom01" class="form-label">Nome do
                                                                Cliente</label>
                                                            <input type="text" name="nome" class="form-control"
                                                                id="validationCustom01">
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="row">
                                                        <div class="col-md-2">
                                                            <label for="validationCustom01" class="form-label">CPF</label>
                                                            <input type="text" name="cpf" class="form-control"
                                                                id="validationCustom01">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label for="validationCustom01" class="form-label">CNPJ</label>
                                                            <input type="text" name="cnpj" class="form-control"
                                                                id="validationCustom01">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label for="validationCustom01" class="form-label">Data
                                                                Incial</label>
                                                            <input type="date" name="datainicial" class="form-control"
                                                                id="validationCustom01">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label for="validationCustom01" class="form-label">Data
                                                                Final</label>
                                                            <input type="date" name="datafinal" class="form-control"
                                                                id="validationCustom01">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label for="validationCustom01" class="form-label">Status do
                                                                Pedido</label>
                                                            <select class="form-select" aria-label="Default select example">
                                                                <option selected>Selecione..</option>
                                                                <option value="1">Aguardando Envio</option>
                                                                <option value="2">Enviado</option>
                                                                <option value="3">A Enviar</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 mt-4">
                                                        <button class="btn btn-primary" type="submit">Pesquisar</button>
                                                    </div>
                                            </div>
                                        </div>
                                        </form>
                                    </div>

                                    @foreach ($viewData['pedidos'] as $order)

                                    <div class="col-md-6 col-lg-12 pb-3">
                                        <div class="card card-custom bg-white border-white border-0">
                                            <div class="card-custom-img" style="background-image: url(http://res.cloudinary.com/d3/image/upload/c_scale,q_auto:good,w_1110/trianglify-v1-cs85g_cc5d2i.jpg);"></div>

                                            <div class="card-body" style="overflow-y: auto">
                                            <h4 class="card-title">
                                                <div class="card-custom-avatar">
                                                    <img class="img-fluid border border-dark rounded-circle" src="{{$order['produtos'][0]->image}}" width="64" />
                                                    </div>

                                                {{ $order['pedido']->cliente }}</h4>
                                            <p class="card-text">Nome: {{$order['produtos'][0]->nome}} -   Quantidade: <span class="border border-dark rounded-circle px-3 py-2"> {{$order['produtos'][0]->quantidade}}</span> <hr> Número do Pedido:  {{$order['pedido']->numeropedido}} - Valor : R$ {{number_format($order['pedido']->valorVenda,2)}} </p>
                                            </div>
                                            <div class="card-footer" style="background: inherit; border-color: inherit;">
                                            <a href="#" class="btn btn-primary">Ver Mais</a>
                                            <a href="#" class="btn btn-outline-primary">Pagar</a>
                                            </div>
                                        </div>
                                    </div>

                                    @endforeach
                                </nav>
                        </table>
                    </div>
                    <div class="d-flex py-2">
                        {!! $orders->links() !!}
                    </div>
                    <!--Bottom Table UI-->
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

            $("#search").keyup(function() {

                var payment = $("#formPayment").val();
                // SELECIONAR A FORMA DE PAGAMENTO
                $("#formPayment").change(function() {
                    var payment = $("#formPayment").val();
                });

                var name = $("#search").val();
                $.ajax({
                    url: "/getOrderUser",
                    type: "GET",
                    data: {
                        name: name,
                        payment: payment,
                    },
                    success: function(response) {
                        console.log(response);
                        if (response) {
                            $('#result').removeClass('d-none');
                            // CONVERT ARRAY IN JSON FOR EACH FUNCTION
                            var json = $.parseJSON(response.dados);

                            // SHOW ALL RESULT QUERY
                            var index = [];
                            $.each(json, function(i, item) {
                                url =
                                    "<div class='d-flex text-muted pt-3'><svg class='bi bi-cash-coin  bd-placeholder-img flex-shrink-0 me-2 rounded' width='32' height='32'xmlns='http://www.w3.org/2000/svg' role='img' aria-label='Placeholder: 32x32'preserveAspectRatio='xMidYMid slice' focusable='false'><rect width='100%' height='100%' fill='#007bff' /><text x='50%' y='50%' fill='#007bff' dy='.3em'></text> </svg><p class='pb-3 mb-0 small lh-sm d-block' id='result'><strong class='d-block text-gray-dark'><a class='text-decoration-none' href={{ route('orders.show', ['id' => ':id']) }}>" +
                                    item.name + "</a></strong>ID do Pedido: " + item
                                    .id + ", Total R$: " + item.total +
                                    ", Data do Pedido: " + item.created_at +
                                    ", Forma de Pagamento : " + item.pagamento +
                                    "</p></div><hr>";
                                url = url.replace(':id', item.id);
                                index[i] = url;
                                // index[i] = '<option value=' + item.id + '>' + item
                                //     .name + '</option>';
                            });

                            var arr = jQuery.makeArray(index);
                            arr.reverse();
                            $("#result").html(arr);

                        }
                    },
                    error: function(error) {
                        $('#result').hide();
                    }
                });
            });
        });
    </script>

@endsection
