@extends('layouts.app')
@section('title', $viewData['title'])
@section('subtitle', $viewData['subtitle'])
@section('conteudo')
    <div class="container mt-4">
        <div class="row">
            <!-- Coluna de Imagens -->
            <div class="col-md-5 d-flex">
                <div class="d-flex flex-column align-items-start me-2">
                    @if (!empty($viewData['imageJson']) && is_array(json_decode($viewData['imageJson'], true)))
                        @foreach (json_decode($viewData['imageJson']) as $foto)
                            <img src="{{ $foto->url }}" class="img-thumbnail m-1 fotoProduto" alt="...">
                        @endforeach
                    @elseif (!empty($viewData['images']) && is_array($viewData['images']))
                        @foreach ($viewData['images'] as $foto)
                            <img src="{!! Storage::disk('s3')->url('produtos/' . $viewData['product']->getId() . '/' . $foto) !!}" class="img-thumbnail m-1 fotoProduto" alt="...">
                        @endforeach
                    @else
                        <p class="text-muted">Nenhuma imagem disponível</p>
                    @endif
                </div>
                <div class="mb-3">
                    <img id="mainImage" class="img-fluid rounded shadow-lg"
                        src="{!! Storage::disk('s3')->url('produtos/' . $viewData['product']->getId() . '/' . $viewData['image']) !!}">
                </div>
            </div>

            <!-- Coluna de Informações do Produto -->
            <div class="col-md-7">
                <div class="card p-4 shadow-sm">
                    <h5 class="text-primary">{{ $viewData['product']->getName() }}</h5>
                    <p class="text-muted">SKU: {{ $viewData['product']->getId() }}</p>
                    <div class="d-flex align-items-center">
                        <span class="text-warning">★ 4.7</span>
                        <span class="text-muted ms-2"></span>
                    </div>

                    <div class="mt-3">
                        <h3 class="text-success fw-bold">R$
                            {{ number_format($viewData['product']->getPriceWithFeeMktplace(), 2, ',', '.') }}</h3>
                        <p class="text-success">Envio Imediato</p>
                    </div>

                    <p class="fw-bold">Estoque disponível ✅</p>

                    <!-- Tabela com dados adicionais do produto -->
                    <h6 class="mt-3 fw-bold">Informações do Produto:</h6>
                    <table class="table table-bordered mt-2">
                        <tbody>
                            <tr class="bg-light">
                                <td><strong>Peso</strong></td>
                                <td>{{$viewData['product']->weight ? $viewData['product']->weight : 'N/A'}}</td>
                            </tr>
                            <tr>
                                <td><strong>Altura</strong></td>
                                <td>{{$viewData['product']->height ? $viewData['product']->height . " CM": 'N/A'}}</td>
                            </tr>
                            <tr class="bg-light">
                                <td><strong>Largura</strong></td>
                                <td>{{$viewData['product']->width ? $viewData['product']->width. " CM" : 'N/A'}}</td>
                            </tr>
                            <tr>
                                <td><strong>Comprimento</strong></td>
                                <td>{{$viewData['product']->length ? $viewData['product']->length. " CM" : 'N/A'}}</td>
                            </tr>
                            <tr class="bg-light">
                                <td><strong>GTIN / EAN</strong></td>
                                <td>{{$viewData['product']->gtin ? $viewData['product']->gtin : 'N/A'}}</td>
                            </tr>
                        </tbody>
                    </table>


                    <div class="mt-4">
                        <h5 class="fw-bold">Descrição do Produto:</h5>
                        <p class="text-justify">{{ $viewData['product']->getDescription() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .img-thumbnail {
            cursor: pointer;
            border: 2px solid transparent;
            width: 230px;
            height: 90px;
        }

        .img-thumbnail:hover {
            border: 2px solid #007bff;
        }
    </style>

@endsection

<script>
    document.addEventListener("DOMContentLoaded", function() {
        let mainImage = document.getElementById("mainImage");
        let thumbnails = document.querySelectorAll(".fotoProduto");

        thumbnails.forEach(thumb => {
            thumb.addEventListener("mouseenter", function() {
                mainImage.src = this.src;
            });
        });
    });
</script>
