@extends('layouts.admin')
@section('title', $viewData['title'])
@section('subtitle', $viewData['subtitle'])
@section('conteudo')
    <div class="container">

        {{-- Filter Start --}}
        <div>
            <div class="bd-example-snippet bd-code-snippet">
                <div class="bd-example">
                    <div class="accordion-item">
                        <h4 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                Filtros Avançados
                            </button>
                        </h4>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <form class="row g-3 needs-validation" novalidate>
                                    <div class="col-md-4">
                                        <label for="validationCustom01" class="form-label">Nome do Cliente</label>
                                        <input type="text" class="form-control" id="search"
                                            placeholder="digite o nome do cliente" required>
                                        <div class="valid-feedback">
                                            Looks good!
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="validationCustom02" class="form-label">Data Inicial</label>
                                        <input type="date" class="form-control" id="validationCustom02" value="Otto"
                                            required>
                                        <div class="valid-feedback">
                                            Looks good!
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="validationCustom02" class="form-label">Data Final</label>
                                        <input type="date" class="form-control" id="validationCustom02" value="Otto"
                                            required>
                                        <div class="valid-feedback">
                                            Looks good!
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <select class="form-select" id="formPayment"
                                            aria-label="Floating label select example">
                                            <option value="" selected>...</option>
                                            <option value="1">Dinheiro</option>
                                            <option value="2">Cartão de Crédito</option>
                                            <option value="3">Cartão de Débito</option>
                                            <option value="5">Pix</option>
                                            <option value="4">Marcar</option>
                                        </select>
                                        <label for="floatingSelect">Selecione o Meio de Pagamento</label>
                                    </div>

                                    <div class="col-12">
                                        <button class="btn btn-primary" type="submit">Pesquisar</button>
                                    </div>

                                    <small class="d-block text-end mt-3">
                                        <a href="{{route('generate')}}" class="btn btn-success">Exportar Relatórios</a>
                                    </small>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Filter END --}}

        {{-- : START --}}

        <div class="d-flex align-items-center p-3 my-3 text-white bg-purple rounded shadow-sm">
            <div
                class="feature-icon d-inline-flex align-items-center justify-content-center bg-primary bg-gradient text-white fs-2 p-2">
                <i class="bi bi-coin py-2"></i>
            </div>

            <div class="lh-1 p-2">
                <h1 class="h6 mb-0 text-white lh-1">Vendas</h1>
                <small>MotoStore {{ date('Y') }}</small>
            </div>
        </div>

        <div class="my-3 p-3 bg-body rounded shadow-sm" id="result">
            <h6 class="pb-2 mb-0">Vendas Recentes</h6>
            @foreach ($viewData['orders'] as $order)
                <div class="d-flex text-muted pt-3">
                    <p class="pb-3 mb-0 small lh-sm d-block">
                        {{$order->cliente}}
                        <strong class="d-block text-gray-dark"><a class="text-decoration-none"
                                href={{ route('orders.show', ['id' => $order->id]) }}>{{ $order->cliente }}</a></strong>
                        {{-- content --}}
                        ID do Pedido: {{ $order->numeropedido }}, Total R$: {{ $order->valorVenda }}, Data do Pedido:
                        {{ $order->created_at }}, Cliente : {{ $order->cliente }}
                    </p>
                </div>
                <hr>
            @endforeach

            <small class="d-block text-end mt-3">
                <a href="{{route('generate')}}" class="btn btn-primary">Exportar Relatórios</a>
            </small>
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
