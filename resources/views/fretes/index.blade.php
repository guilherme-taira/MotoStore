@extends('layouts.app')
@section('title', $viewData['title'])
@section('subtitle', $viewData['subtitle'])
@section('conteudo')

    <div class="container-fluid px-4">
        <div class="card mt-2">
            <div class="card-header">
                Fretes
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
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($viewData['shipping'] as $shipping)
                            <tr id="linhasProduct">
                                <td>{{ $shipping->id }}</td>
                                <td>{{ $shipping->created_at }}</td>
                                <td>{{ $shipping->id_mercadoLivre }}</td>
                                <td> - </td>
                                <td>{{$shipping->rastreio}}</td>
                                <td>{{ $shipping->updated_at }}</td>
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
