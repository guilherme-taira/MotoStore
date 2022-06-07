@extends('layouts.layout')
@section('conteudo')
    <div class="row">
        @foreach ($viewData['products'] as $product)
            <div class="col-md-4 col-lg-3 mb-2">
                <div class="card">
                    <img src="{!!Storage::disk('s3')->url('produtos/'.$product->getId().'/'.$product->getImage())!!}" class="card-img-top img-card">
                    <div class="card-body text-center">
                        <a href="{{ route('products.show', ['id' => $product['id']]) }}"
                            class="btn bg-primary text-white">{{ $product['name'] }}</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
