@extends('layouts.app')
@section('conteudo')
    <style>
        .badge-warning {
            background-color: #f0ad4e;
        }

        .badge-success {
            background-color: #5cb85c;
        }

        .badge-secondary {
            background-color: #6c757d;
        }

        .badge-info {
            background-color: #5bc0de;
        }
    </style>
    <div class="container mt-5">
        <h1 class="mb-4">Pedidos: Shopify</h1>

        <!-- Filtros -->
        <div class="mb-4">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active" href="#">Tudo</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Não processados</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Não pagos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Abertos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Arquivado</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">+</a>
                </li>
            </ul>
        </div>

        <!-- Controles de Tabela -->
        <div class="d-flex justify-content-between mb-2">
            <div class="d-flex">
                <button class="btn btn-outline-secondary me-2">Filtrar</button>
                <button class="btn btn-outline-secondary me-2">Ordenar</button>
                <button class="btn btn-outline-secondary">Exibir</button>
            </div>
            <div class="d-flex">
                <button class="btn btn-outline-secondary me-2">Exportar</button>
                <button class="btn btn-outline-secondary">Mais ações</button>
            </div>
        </div>

        <!-- Tabela -->
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th><input type="checkbox"></th>
                        <th>Pedido</th>
                        <th>Data</th>
                        <th>Cliente</th>
                        <th>Canal de vendas</th>
                        <th>Total</th>
                        <th>Status do pagamento</th>
                        <th>Status de processamento do pedido</th>
                        <th>Itens</th>
                        <th>Status da entrega</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td><input type="checkbox"></td>
                            <td>#{{ $order['id'] }}</td>
                            <td>{{ $order['date'] }}</td>
                            <td>{{ $order['client'] }}</td>
                            <td>{{ $order['channel'] }}</td>
                            <td>{{ $order['total'] }}</td>
                            <td>
                                <span
                                    class="badge {{ $order['payment_status'] == 'Pagamento pendente' ? 'badge-warning' : 'badge-success' }}">
                                    {{ $order['payment_status'] }}
                                </span>
                            </td>
                            <td>
                                <span
                                    class="badge {{ $order['order_status'] == 'Não processado' ? 'badge-warning' : ($order['order_status'] == 'Em andamento' ? 'badge-info' : 'badge-secondary') }}">
                                    {{ $order['order_status'] }}
                                </span>
                            </td>
                            <td>{{ $order['items'] }}</td>
                            <td>{{ $order['delivery_status'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
