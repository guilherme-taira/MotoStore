@extends('layouts.layout')
@section('title', $viewData['title'])
@section('subtitle', $viewData['subtitle'])
@section('conteudo')
    <div class="card">
        <div class="card-body">
            <div class="card-header">
                <h5 class="card-title">Selecione o Cliente para finalizar o Pedido</h5>
                <div class="row">
                    <div class="col-6">
                        <input type="text" class="form-control" id="search" placeholder="Pesquisar..">
                        <select class="form-select d-none" multiple id="result">
                        </select>
                    </div>

                    <div class="col-2">
                        <a href="{{ route('user.create') }}" class="btn btn-success">Novo Usuário <i
                                class="bi bi-person-plus-fill"></i></a>
                    </div>
                </div>

                <form action="{{route('setUser.add')}}" method="post">
                @csrf
                <div class="container" id="ShowUser">
                    <div class="card-body">
                        <table class="table table-bordered table-striped text-center">
                            <thead>
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Nome</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="text" name="user" class="form-control" id="id"></td>
                                    <td><input type="text" id="name" class="form-control" disabled></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="d-grid gap-2 d-none" id="btnFinalizar">
                    <button class="btn btn-success" type="submit">Finalizar <i class="bi bi-cart-check"></i></button>
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


    <script>
        $(document).ready(function() {

            $("#search").keyup(function() {

                var name = $("#search").val();
                $.ajax({
                    url: "/getInfoUser",
                    type: "GET",
                    data: {
                        name: name,
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
                                index[i] = '<option value=' + item.id + '>' + item
                                    .name + '</option>';
                            });

                            var arr = jQuery.makeArray(index);
                            arr.reverse();
                            $("#result").html(arr);

                            $("select").change(function() {
                                $('#id').val($(this).children("option:selected")
                                    .val());
                                $('#name').val($(this).children("option:selected")
                                    .text());
                                var number = $('#id').val();
                                if (number > 0) {
                                    $("#btnFinalizar").removeClass('d-none');
                                    $('#name,#id').addClass('p-3 mb-2 bg-warning text-dark');
                                }
                                //  var url = '{{ route('product.cartshow', ':id') }}';
                                //  url = url.replace(':id', number);
                                //  $("#ShowQuantiti").load(url);

                            });


                        }
                    },
                    error: function(error) {
                        $('#result').html('<option> Produto Digitado Não Existe! </option>');
                    }
                });
            });
        });
    </script>
