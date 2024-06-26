@extends('layouts.app')
@section('conteudo')
    <div class="card mb-4">
        <div class="card-header">
            Criar Produto
        </div>

        <div class="card-body">
            @if ($errors->any())
                <ul class="alert alert-danger list-unstyled">
                    @foreach ($errors->all() as $error)
                        <li>- {{ $error }}</li>
                    @endforeach
                </ul>
            @endif


            <!--- FINAL DO MODAL ---->
            <div id="liveAlertPlaceholder"></div>


            <div class="spinner-overlay d-none" id="loading-api">
                <div class="spinner-border spinner-big text-light" role="status">
                  <span class="visually-hidden">Carregando...</span>
                </div>
              </div>

            <form method="POST" action="{{ route('products.update', ['id' => $viewData['product']->id]) }}"
                enctype="multipart/form-data">
                @method('PUT')
                @csrf

                <!--- MODAL QUE SELECIONA O MOTORISTA --->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title text-dark" id="exampleModalLabel">Tarifa Plataforma <i
                                        class="bi bi-money"></i>
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
                                <div class="col-md-12">

                                    <div class="col-md-12">
                                        <p class="col-lg-2 col-md-6 col-sm-12 col-form-label">Acréssimo </p>
                                        <div class="col">
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-md-6 col-sm-12 col-form-label">%</label>
                                                <div class="col-lg-3 col-md-6 col-sm-6">
                                                    <input id="acressimoP" class="form-control porcem">
                                                </div>
                                                <label class="col-lg-2 col-md-6 col-sm-12 col-form-label">R$</label>
                                                <div class="col-lg-3 col-md-6 col-sm-6">
                                                    <input id="acressimoR" type="text" value="{{$viewData['product']->fee}}" class="form-control porcem">
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-md-12">
                                        <div class="col">
                                            <div class="mb-3 row">
                                                <label class="col-lg-2 col-md-6 col-sm-12 col-form-label">Bruto:</label>
                                                <div class="col-lg-3 col-md-6 col-sm-12">
                                                    <input name="price" id="precoFinal" type="text"
                                                        class="form-control">
                                                </div>

                                                <label class="col-lg-2 col-md-6 col-sm-12 col-form-label">Liquído: </label>
                                                <div class="col-lg-3 col-md-6 col-sm-12">
                                                    <input name="fee" value="{{$viewData['product']->fee}}" id="precoLiquido" type="text"
                                                        class="form-control">
                                                </div>

                                                <hr class="mt-4">
                                                <label class="col-lg-4 col-md-3 col-sm-12 col-form-label">Taxa %: </label>
                                                <div class="col-lg-3 col-md-6 col-sm-12">
                                                    <input name="taxaFee" id="taxaFee" type="text" value="4.99"
                                                        class="form-control">
                                                </div>

                                                <label class="col-lg-2 col-md-6 col-sm-12 col-form-label">Final: </label>
                                                <div class="col-lg-3 col-md-6 col-sm-12">
                                                    <input name="PriceWithFee" id="PriceWithFee" value="{{$viewData['product']->valorProdFornecedor}}" type="text"
                                                        class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>




                <div class="row">
                    <div class="row imagemCs">
                        @foreach ($viewData['photos'] as $foto)
                        <div class="col row-cols-1 row-cols-md g-2">
                            <div class="col">
                                    <div class="foto-container position-relative">
                                        <img src="{{ $foto }}" alt="{{ $viewData['product']->getName() }}"
                                            class="img-fluid border border-3 border-secondary rounded imagem">
                                        <span
                                            class="icone-lixeira position-absolute top-0 end-0 m-2 p-2 bg-light rounded-circle"><i
                                                class="fas fa-trash-alt text-danger"></i></span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <hr class="mt-3">

                    <div class="col-lg-3">
                        <label>Ativo / Público</label>
                        <select name="isPublic" class="form-control" required>
                            <option value="1" selected>SIM</option>
                            <option value="0">NÂO</option>
                        </select>
                    </div>

                    <div class="col-lg-3">
                        <label>NFT</label>
                        <select name="isNft" class="form-control" required>
                            <option value="1">SIM</option>
                            <option value="0"selected>NÂO</option>
                        </select>
                    </div>

                    <div class="col-lg-2">
                        <label>Valor Termômetro <i class="bi bi-speedometer"></i></label>
                        <input type="number" name="termometro" value="{{ $viewData['product']->termometro }}"
                            id="termometro" min="0" max="150" class="form-control">
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="mb-3 row mt-2">
                            <label class="col-lg-2 col-md-6 col-sm-12 col-form-label">Nome:</label>
                            <div class="col-lg-10 col-md-6 col-sm-12">
                                <input name="name" value="{{ $viewData['product']->title }}" type="text"
                                    class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="mb-3 row mt-2">
                            <label class="col-lg-2 col-md-6 col-sm-12 col-form-label">Preço R$:</label>
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <input name="price" id="precoNormal" value="{{ $viewData['product']->price }}"
                                    type="text" class="form-control">
                            </div>

                            <label class="col-lg-2 col-md-6 col-sm-12 col-form-label">Estoque:</label>
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <input name="stock" value="{{ $viewData['product']->available_quantity }}"
                                    type="number" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3 row mt-2">
                            <label class="col-lg-2 col-md-2 col-sm-2 col-form-label">Categoria Mercado Livre:</label>
                            <div class="col-lg-3 col-md-2 col-sm-2">
                                <input name="categoria_mercadolivre" value="{{ $viewData['product']->category_id }}"
                                    type="text" class="form-control">
                            </div>

                            <label class="col-lg-2 col-md-6 col-sm-12 col-form-label">Marca :</label>
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <input name="brand" value="{{ $viewData['product']->brand }}" type="text"
                                    class="form-control">
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="mb-3 row">
                            <div class="col-lg-3">
                                <label>Preço Promocional:</label>
                                <input name="pricePromotion" value="{{ $viewData['product']->pricePromotion }}"
                                    type="text" class="form-control">
                            </div>

                            <div class="col-lg-3">
                                <label>GTIN / EAN :</label>
                                <input name="ean" value="{{ $viewData['product']->gtin }}" class="form-control">
                            </div>


                            <div class="col-lg-1">
                                <label>Largura: </label>
                                <input name="width" value="{{ $viewData['product']->width }}" class="form-control">
                            </div>


                            <div class="col-lg-1">
                                <label>Altura: </label>
                                <input name="height" value="{{ $viewData['product']->height }}" class="form-control">
                            </div>


                            <div class="col-lg-2">
                                <label>Comprimento: </label>
                                <input name="length" value="{{ $viewData['product']->length }}" class="form-control">
                            </div>

                            <div class="col-lg-3">
                                <label>Tipo de Anúncio :</label>
                                <select name="tipo_anuncio" class="form-control" aria-label=".form-select-sm example"
                                    required>

                                    @if ($viewData['product']->listing_type_id == 'gold_special')
                                        <option value="gold_special" selected>Clássico</option>
                                        <option value="gold_pro">Premium</option>
                                    @elseif($viewData['product']->listing_type_id == 'gold_pro')
                                        <option value="gold_pro" selected>Premium</option>
                                        <option value="gold_special">Clássico</option>
                                    @else
                                        <option value="gold_special">Clássico</option>
                                        <option value="gold_pro">Premium</option>
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col col-lg-4">
                                <label for="categoria">Categorias:</label>
                                <select class="form-select mt-2" name="categoria" id="categoria" required
                                    aria-label="Default select example">
                                    <option value="{{ $viewData['product']->subcategoria }}" selected>
                                        {{ $viewData['categoriaSelected']->name }}</option>

                                    @foreach ($viewData['categorias'] as $categoria)
                                        <option class="bg-warning" disabled>{{ $categoria['nome'] }}</option>
                                        @foreach ($categoria['subcategory'] as $subcategoria)
                                            <option value="{{ $subcategoria->id }}"> - {{ $subcategoria->name }}
                                            </option>
                                        @endforeach
                                    @endforeach

                                </select>
                            </div>
                            <div class="col col-lg-4">
                                <label>Fornecedor</label>
                                <select name="fornecedor" class="form-control mt-2" required>
                                    @foreach ($viewData['fornecedor'] as $fornecedor)
                                        <option class="bg-warning" value="{{ $fornecedor->id }}">{{ $fornecedor->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="col col-lg-2">
                                <label>Tarifa</label>
                                <select name="feeClass" class="form-control mt-2" id="feeClass" required>
                                    <option selected>selecione..</option>
                                    <option value="1" data-bs-toggle="modal" data-bs-target="#exampleModal">Calcular
                                </select>
                            </div>

                            <div class="col col-lg-2">
                                <div id="inputContainer"></div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col">
                                <div class="mb-3 row">
                                    <label class="col-lg-2 col-md-4 col-sm-12 col-form-label">Image:</label>
                                    <div class="col-lg-10 col-md-6 col-sm-12">
                                        <input class="form-control" type="file" name="image">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="3">{{ $viewData['product']->description }}</textarea>
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Atualizar <i class="bi bi-hdd"></i></button>
                        </div>
            </form>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

    <script>
        $(document).ready(function() {
            $(".icone-lixeira").click(function(event){

                if (confirm("Tem certeza que deseja apagar esta foto?")) {
                $(this).closest('.col').remove(); // Remove o contêiner da coluna que envolve a foto
                var urlImagem = $(this).siblings("img").attr("src");

                $("#loading-api").removeClass('d-none');

                //APAGAR A FOTO ROTA POST /
                $.ajax({
                    type: "POST",
                    url: "http://127.0.0.1:8000/api/v1/deleteFoto",
                    data: {
                        'imagem': urlImagem
                    },
                    success: function(response) {
                        // Ação a ser realizada em caso de sucesso
                        const alertPlaceholder = document.getElementById('liveAlertPlaceholder')

                        const alert = (message, type) => {
                            const wrapper = document.createElement('div')
                            wrapper.innerHTML = [
                                `<div class="alert alert-${type} alert-dismissible" role="alert">`,
                                `   <div>${message}</div>`,
                                '   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>',
                                '</div>'
                            ].join('')

                            $(event.target).fadeOut("slow", function() {
                                $(this).remove();
                            });


                            alertPlaceholder.append(wrapper)
                        }

                        alert(response.res, 'success');
                        $("#loading-api").addClass('d-none');
                    },
                    error: function(xhr, status, error) {
                        // Ação a ser realizada em caso de erro

                        console.error("Erro ao deletar a foto:", error);
                    }
                // Aqui você obtém o src da foto
                });
             }
            });




            $('#feeClass').change(function() {
                var selectedValue = $(this).val();
                // Remover input anterior, se existir
                $('#inputContainer').empty();

                // Criar novo input com a máscara apropriada
                if (selectedValue === '1') {
                    $("#precoFinal").val($("#precoNormal").val());
                    $('#exampleModal').modal('show');
                } else if (selectedValue === '2') {
                    $("#precoFinal").val($("#precoNormal").val());
                    $('#exampleModal').modal('show');

                }

            });

            // VALOR TOTAL
            var total = $('#precoNormal').val();
            var totalCalculado = total;
            var totalLiquido = 0;
            var valorProduto = 0;
            var taxa = $("#taxaFee").val();

            function calculaPorcemtagem(valor, porcem) {
                //60 x 25% = 160 (25/100) = 160 x 0,25 = 40.
                return valor * (porcem / 100);
            }

            $('#precoFinal').val(parseFloat(total).toFixed(2));


            $('#acressimoP').keyup(function() {

                total = $('#precoNormal').val();
                $('#precoFinal').val(parseFloat(total).toFixed(2));

                if ($('#acressimoP').val().length >= 1) {
                    var porcem = $('#acressimoP').val();
                    totalCalculado = parseFloat($('#precoNormal').val()) + parseFloat(calculaPorcemtagem($(
                        '#precoNormal').val(), porcem));
                    $('#precoFinal').val(parseFloat(totalCalculado).toFixed(2));

                    totalLiquido = parseFloat($('#precoFinal').val()) - parseFloat($('#precoNormal').val());
                    $("#precoLiquido").val(totalLiquido.toFixed(2));

                    $('#acressimoR').prop("disabled", true).css({
                        'background-color': '#cecece'
                    });
                    $('#descontoP').prop("disabled", true).css({
                        'background-color': '#cecece'
                    });;
                    $('#descontoR').prop("disabled", true).css({
                        'background-color': '#cecece'
                    });
                } else {
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

                total = $('#precoNormal').val();
                $('#precoFinal').val(parseFloat(total).toFixed(2));


                if ($('#acressimoR').val().length >= 1) {
                    var reais = $('#acressimoR').val();
                    totalCalculado = parseFloat(total) + parseFloat(reais);

                    $('#precoFinal').val(parseFloat(totalCalculado).toFixed(2));

                    // valor com as taxas calculo final
                    valorProduto = (parseFloat($("#precoFinal").val()) / 0.95);
                    // claculo do valor liquido
                    totalLiquido = parseFloat($('#precoFinal').val()) - parseFloat($('#precoNormal').val());
                    // preço liquido final
                    $("#precoLiquido").val(totalLiquido.toFixed(2));

                    // coloca o valor final
                    $("#PriceWithFee").val(parseFloat(valorProduto).toFixed(2));

                    $('#acressimoP').prop("disabled", true).css({
                        'background-color': '#cecece'
                    });
                    $('#descontoP').prop("disabled", true).css({
                        'background-color': '#cecece'
                    });;
                    $('#descontoR').prop("disabled", true).css({
                        'background-color': '#cecece'
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
        });
    </script>
@endsection
