@extends('layouts.app')
@section('title', $viewData['title'])
@section('conteudo')

    <div class="container-fluid px-4">
        <h2 class="mt-4">Dashboard</h2>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item badge bg-success">Bem-vindo a Afilidrop {{ Auth::user()->name }}</li>
        </ol>
        <div class="row">
            <div class="container">
                <div class="row">
                    <div class="col-md-3">
                        <div class="card card-custom">
                            {{-- LOADING --}}
                            <div class="spinner-overlay loading-api" id="loading-api">
                                <div class="spinner-border spinner-big text-light" role="status">
                                    <span class="visually-hidden">Carregando...</span>
                                </div>
                            </div>

                            <div class="card-header bg-dark text-white">Valor total de vendas</div>
                            <div class="card-body">
                                <h5 class="card-title totalvenda">R$ 0,00</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card card-custom">

                            {{-- LOADING --}}
                            <div class="spinner-overlay loading-api" id="loading-api">
                                <div class="spinner-border spinner-big text-light" role="status">
                                    <span class="visually-hidden">Carregando...</span>
                                </div>
                            </div>

                            <div class="card-header bg-dark text-white">Valor médio por dia</div>
                            <div class="card-body">
                                <h5 class="card-title totalMedio">R$ 0,00</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card card-custom">

                            {{-- LOADING --}}
                            <div class="spinner-overlay loading-api" id="loading-api">
                                <div class="spinner-border spinner-big text-light" role="status">
                                    <span class="visually-hidden">Carregando...</span>
                                </div>
                            </div>
                            <div class="card-header bg-dark text-white">Total de vendas Mês</div>
                            <div class="card-body">
                                <h5 class="card-title QuantidadeVendas">0</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card card-custom">
                            {{-- LOADING --}}
                            <div class="spinner-overlay loading-api" id="loading-api">
                                <div class="spinner-border spinner-big text-light" role="status">
                                    <span class="visually-hidden">Carregando...</span>
                                </div>
                            </div>
                            <div class="card-header bg-dark text-white">Vendas por dia</div>
                            <div class="card-body">
                                <h5 class="card-title vendasHoje">0</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="row">
                    <div class="col-md-4">
                        <select class="form-control">
                            {{-- <option>Selecionar...</option> --}}
                            <option>Mercado Livre</option>
                            {{-- <option>Shopee</option> --}}
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="form-control">
                            <option>Todas</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="text" id="daterange" class="form-control">
                    </div>
                </div>
                <div class="mt-5 d-flex justify-content-center">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-header">
                    <i class="fas fa-table me-1"></i>
                    Últimas Vendas
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Cliente</th>
                                <th>Email</th>
                                <th>Valor</th>
                                <th>Tarifa</th>
                                <th>Data Venda</th>
                                <th>Número Pedido</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($viewData['orders'] as $order)
                            <tr>
                                <td>{{$order->cliente}}</td>
                                <td>{{$order->email}}</td>
                                <td>R$ {{$order->valorVenda}}</td>
                                <td>R$ {{$order->fee}}</td>
                                <td>{{$order->dataVenda}}</td>
                                <td>{{$order->numeropedido}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <input type="hidden" name="id_user" id="id_user" value="{{ Auth::user()->id }}">

        <link href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" rel="stylesheet">
        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- Bootstrap JS -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <!-- DateRangePicker JS -->
        <script src="https://cdn.jsdelivr.net/npm/moment/min/moment.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
        <!-- Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            var canvas = document.getElementById('salesChart');
            canvas.width = 3000;
            canvas.height = 1000;

            // var ctx = document.getElementById('salesChart').getContext('2d');
            // var salesChart = new Chart(ctx, {
            //     type: 'line',
            //     data: {
            //         labels: [],
            //         datasets: []
            //     },
            //     options: {
            //         scales: {
            //             y: {
            //                 beginAtZero: true
            //             }
            //         }
            //     }
            // });


            function getCurrentDate() {
                const today = new Date();
                const year = today.getFullYear();
                const month = String(today.getMonth() + 1).padStart(2, '0'); // Mês começa em 0
                const day = String(today.getDate()).padStart(2, '0');

                return `${year}-${month}-${day}`;
            }

            // Função para obter a data de ontem no formato YYYY-MM-DD
            function getYesterdayDate() {
                const today = new Date();
                today.setDate(today.getDate() - 7);
                const year = today.getFullYear();
                const month = String(today.getMonth() + 1).padStart(2, '0'); // Mês começa em 0
                const day = String(today.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            }


            var idUser = $("#id_user").val();

            $.ajax({
                url: 'https://afilidrop.com.br/api/v1/sales-data',
                method: 'GET',
                data: {
                    'dataInicial': getYesterdayDate(),
                    'dataFinal': getCurrentDate(),
                    'user_id': idUser
                },
                success: function(response) {
                    // Log para depuração
                    // var canvas = document.getElementById('salesChart');
                    // canvas.width = 3000;
                    // canvas.height =
                    // 1000;
                    // Simulação de dados para o gráfico
                    var ctx = document.getElementById('salesChart').getContext('2d');
                    var salesChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: response.labels,
                            datasets: response.datasets
                        },
                        options: {
                            interaction: {
                                mode: 'index',
                                intersect: false
                            },
                            plugins: {
                                tooltip: {
                                    mode: 'index',
                                    intersect: false,
                                    callbacks: {
                                        title: function(tooltipItems) {
                                            return tooltipItems[0].label;
                                        },
                                        label: function(tooltipItem) {
                                            var dataset = tooltipItem.dataset;
                                            var label = dataset.label || '';
                                            if (label) {
                                                label += ': ';
                                            }
                                            label += Math.round(tooltipItem
                                                .parsed.y * 100) / 100;
                                            return label;
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                    // });
                },
                //     error: function(error) {
                //         console.log(error);
                //     }
            });


            // Inicializando o daterangepicker
            $('#daterange').daterangepicker({
                opens: 'left',
                locale: {
                    format: 'YYYY-MM-DD'
                },
                autoUpdateInput: true, // Atualiza o input automaticamente
                startDate: moment().startOf('month'),
                endDate: moment().endOf('month')

            }, function(start, end, label) {

                //APAGAR A FOTO ROTA POST /
                var idUser = $("#id_user").val();
                $.ajax({
                    url: 'https://afilidrop.com.br/api/v1/sales-data',
                    method: 'GET',
                    data: {
                        'dataInicial': start.format('YYYY-MM-DD'),
                        'dataFinal': end.format('YYYY-MM-DD'),
                        'user_id': idUser
                    },
                    success: function(response) {
                        // Log para depuração
                        console.log(response);
                        $(function() {
                            // Inicializando o DateRangePicker

                            // Simulação de dados para o gráfico
                            var ctx = document.getElementById('salesChart').getContext('2d');
                            var salesChart = new Chart(ctx, {
                                type: 'line',
                                data: {
                                    labels: response.labels,
                                    datasets: response.datasets,
                                },
                                options: {
                                    scales: {
                                        y: {
                                            beginAtZero: true
                                        }
                                    }
                                }
                            });
                        });
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            });
        </script>

        <script>
            $(document).ready(function() {
                //APAGAR A FOTO ROTA POST /
                var idUser = $("#id_user").val();
                $.ajax({
                    type: "POST",
                    url: "https://afilidrop.com.br/api/v1/dataHome",
                    data: {
                        'user': idUser
                    },
                    success: function(response) {
                        // console.log(response);
                        $(".totalvenda").text("R$: " + response.totalVendasDia);
                        $(".totalMedio").text("R$: " + response.valorMedio);
                        $(".vendasHoje").text(response.VendasPorDia);
                        $(".QuantidadeVendas").text(response.qtdVendasMes);

                        $('.loading-api').each(function() {
                            $(this).addClass('d-none');
                        });

                    },
                    error: function(xhr, status, error) {
                        // Ação a ser realizada em caso de erro

                        console.error("Erro ao deletar a foto:", error);
                    }
                    // Aqui você obtém o src da foto
                });
            });

        </script>
    @endsection
