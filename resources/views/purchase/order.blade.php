@extends('layouts.layout')
@section('title', $viewData['title'])
@section('subtitle', $viewData['subtitle'])
@section('conteudo')
    <div class="card">
        <div class="card-header">
            Venda Finalizada
        </div>
        <div class="card-body">
            <div class="alert alert-success" role="alert">
                <pre>
                    {{ print_r(session()->all()) }}
                    Parabéns pela compra, o número do seu pedido é : <b>#</b>
                </div>
            </div>
        </div>
@endsection

   {{-- AJAX JQUERY SEARCH --}}

   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
   <link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.css" rel="stylesheet" />
   <link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.theme.css" rel="stylesheet" />
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.js"></script>


   <script>
       $(document).ready(function() {

       });
   </script>
