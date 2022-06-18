@extends('layouts.admin')
@section('title', $viewData['title'])
@section('subtitle', $viewData['subtitle'])
@section('conteudo')
    <div class="container">
            <h3>{{$viewData['subtitle']}}</h3>
    </div>
@endsection
