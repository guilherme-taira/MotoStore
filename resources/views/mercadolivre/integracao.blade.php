@extends('layouts.app')
@section('title', $viewData['title'])
@section('conteudo')
<div class="container-fluid px-4">
    <h2 class="mt-4">Mercado Livre</h2>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item badge bg-success"> {{Auth::user()->name}}</li>
    </ol>
    <div class="row">
        <div class="alert alert-danger" role="alert">
            Atenção, cuidado ao renovar o token, verifique se você esta na conta correta dentro da sessão do seu navegador!
          </div>
        <div class="col-xl-3 col-md-6">
                @if ($viewData['integrado'])
                <div class="card bg-success text-white mb-4">
                    <div class="card-body">{{$viewData['integrado']->type}}</div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <span class="small text-white stretched-link" >Integrado em : {{$viewData['integrado']->created_at}}</span>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                    <a class="small text-white stretched-link btn btn-warning py-2" href="https://auth.mercadolivre.com.br/authorization?response_type=code&client_id=3029233524869952&redirect_uri=https://melimaximo.com.br/thankspage" >Renovar token</a>
                </div>
                @else

                <div class="card bg-warning text-white mb-4">
                    <div class="card-body text-dark">Mercado Livre <img src="https://logopng.com.br/logos/mercado-livre-87.png" width="32" height="32" class="float-end"></div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="https://auth.mercadolivre.com.br/authorization?response_type=code&client_id=3029233524869952&redirect_uri=https://melimaximo.com.br/thankspage" >Integrar</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
                @endif
        </div>
    </div>
</div>
@endsection
