@extends('layouts.layout')
@section('conteudo')
    <div class="row">
        @foreach ($viewData['products'] as $product)
            <div class="col-md-4 col-lg-3 mb-2">
                <div class="card" style="width: 16rem;">
                    <img src="{{Storage::disk('s3')->url('produtos/'.$product->getId().'/'.$product->getImage())}}" class="card-img-top img-card mt-2 p-4">
                    <div class="card-body text-center">
                        @if($product->getStock() == 0)
                        <h4 class="badge bg-danger">SEM ESTOQUE</h4>
                        @else
                        <a href="{{ route('products.show', ['id' => $product->getId()]) }}"
                            class="btn bg-primary text-white">{{ $product->getName() }}</a>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
