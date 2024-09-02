@extends('layouts.app')
@section('title', $viewData['title'])
@section('subtitle', $viewData['subtitle'])
@section('conteudo')

    <div class="container-fluid px-4">
        <div class="card mt-2">

            <div class="card-header">
                Fretes

                <form action="" method="GET">
                    <div class="row">
                        <div class="col-md-2">
                            <input type="text" name="id" class="form-control" placeholder="ID">
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="data" class="form-control" placeholder="Data">
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="id_venda" class="form-control" placeholder="ID Venda">
                        </div>
                        <div class="col-md-2">
                            <select name="status" id="status" class="form-control">
                                <option value="">Todos</option>
                                <option value="was_damaged" {{ request()->get('status') == 'was_damaged' ? 'selected' : '' }}>Foi danificado</option>
                                <option value="was_delivered" {{ request()->get('status') == 'was_delivered' ? 'selected' : '' }}>Foi entregue</option>
                                <option value="was_delivered_to_sender" {{ request()->get('status') == 'was_delivered_to_sender' ? 'selected' : '' }}>Foi devolvido ao remetente</option>
                                <option value="was_forwarded" {{ request()->get('status') == 'was_forwarded' ? 'selected' : '' }}>Foi encaminhado</option>
                                <option value="was_fulfilled" {{ request()->get('status') == 'was_fulfilled' ? 'selected' : '' }}>Foi realizado</option>
                                <option value="was_misplaced" {{ request()->get('status') == 'was_misplaced' ? 'selected' : '' }}>Foi extraviado</option>
                                <option value="was_refused" {{ request()->get('status') == 'was_refused' ? 'selected' : '' }}>Foi recusado</option>
                                <option value="was_returned" {{ request()->get('status') == 'was_returned' ? 'selected' : '' }}>Foi devolvido</option>
                                <option value="was_scheduled" {{ request()->get('status') == 'was_scheduled' ? 'selected' : '' }}>Foi agendado</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="rastreio" class="form-control" placeholder="Rastreio">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary">Filtrar</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Data</th>
                            <th scope="col">ID Venda</th>
                            <th scope="col">Status</th>
                            <th scope="col">Rastreio</th>
                            <th scope="col">Comprado</th>
                            <th scope="col">Aliexpress ID</th>
                            <th scope="col">Rastreado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($viewData['shipping'] as $shipping)
                            <tr id="linhasProduct">
                                <td>{{ $shipping->id }}</td>
                                <td>{{ $shipping->created_at }}</td>
                                <td>{{ $shipping->id_mercadoLivre }}</td>
                                <td> {!! App\Models\ShippingUpdate::getStatus($shipping->was_field) !!} </td>
                                <td>{{ $shipping->rastreio }}</td>
                                <td>{{ $shipping->updated_at }}</td>
                                <td>{!! App\Models\ShippingUpdate::extrairNumeros($shipping->msg) !!}</td>
                                <td>{!! App\Models\ShippingUpdate::getIntegrado($shipping->id_rastreio) !!}</td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="d-flex py-2">
                    {!! $viewData['shipping']->links() !!}
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


        });
    </script>

@endsection
