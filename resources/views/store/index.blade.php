@extends('layouts.layout')
@section('conteudo')
    <div class="row">
        @foreach ($viewData['products'] as $product)
            <div class="col-md-4 col-lg-3 mb-2">
                <div class="card">
                    <img src="{{Storage::disk('s3')->url('produtos/'.$product->getId().'/'.$product->getImage())}}" class="card-img-top img-card mt-2 p-4">
                    <div class="card-body text-center">
                        <a href="{{ route('products.show', ['id' => $product->getId()]) }}"
                            class="btn bg-primary text-white">{{ $product->getName() }}</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
