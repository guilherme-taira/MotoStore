@extends('layouts.layout')
@section('conteudo')
    <div class="card">

        {{-- MESSAGE SUCCESS REMOVE ADD CART --}}
        @if (session()->get('message'))
            <div class="alert alert-success" role="alert">
                {{ session()->get('message') }}
            </div>
        @else
            <div class="card-header">
                Products in Cart
                <div class="container">
                    <div class="row">
                        <div class="col">
                            <div class="col-8">
                                <input type="text" class="form-control" id="search" placeholder="Pesquisar..">
                                <select class="form-select d-none" multiple id="result">
                                </select>
                                <p id="final"></p>
                            </div>
                        </div>
                        <div class="col">
                            <div class="container" id="ShowQuantiti"></div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        {{-- END MESSAGE SUCCESS REMOVE ADD CART --}}

        <div class="card-body">
            <table class="table table-bordered table-striped text-center">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Price</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Delete</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($viewData['products'] as $product)
                        <tr>
                            <td>{{ $product->getId() }}</td>
                            <td>{{ $product->getName() }}</td>
                            <td>${{ $product->getPrice() }}</td>
                            <td>{{ session('products')[$product->getId()] }}</td>
                            <td><a href="{{ route('cart.delete', ['id' => $product->getId()]) }}"><i
                                        class="bi bi-trash"></i></a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="row">
                <div class="text-end">
                    <a class="btn btn-outline-secondary mb-2"><b>Total:</b> ${{ $viewData['total'] }}</a>
                    @if (count($viewData['products']) > 0)
                        <a href="{{ route('cart.status') }}" class="btn bg-primary text-white mb-2">Finalizar</a>
                        <a href="{{ route('cart.delete') }}">
                            <button class="btn btn-danger mb-2">
                                Limpar Carrinho
                            </button>
                        </a>
                    @endif
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

                var name = $("#search").val();
                $.ajax({
                    url: "/getInfoProduct",
                    type: "GET",
                    data: {
                        name: name,
                    },
                    success: function(response) {
                        // console.log(response);
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
                                $('#final').text($(this).children("option:selected")
                                    .val());
                                var number = $('#final').text();
                                var url = '{{ route('product.cartshow', ':id') }}';
                                url = url.replace(':id', number);
                                $("#ShowQuantiti").load(url);

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
@endsection
