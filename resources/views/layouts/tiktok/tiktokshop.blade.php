@extends('layouts.app')
@section('title', $viewData['title'])
@section('conteudo')
    <div class="container-fluid px-4">
        <h2 class="mt-4">TikTok Shop</h2>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item badge bg-success"> {{ Auth::user()->name }}</li>
        </ol>
        <div class="row">
            <div class="alert alert-danger" role="alert">
                Atenção, cuidado ao renovar o token, verifique se você está na conta correta dentro da sessão do seu
                navegador!
            </div>

            {{-- Ajuda explicando a ordem dos passos --}}
            <div class="mt-2 text-dark d-flex align-items-center py-4">
                <i class="fas fa-info-circle me-2 text-primary"></i>
                <span class="fw-semibold">⚠️ Primeiro integre a loja, depois clique em autorizar.</span>
            </div>

            <div class="col-xl-3 col-md-6">
                @if ($viewData['integrado'])
                    <div class="card bg-success text-white mb-4">
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <span class="small text-white stretched-link">Integrado em:
                                {{ $viewData['integrado']->created_at }}</span>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                        <a class="small text-white stretched-link btn btn-warning py-2 mb-2"
                            href="https://afilidrop.com.br/tiktok/oauth/redirect">Renovar token</a>

                        {{-- Novo botão de Autorizar Loja --}}
                        <a class="small text-white stretched-link btn btn-primary py-2"
                            href="{{ route('tiktok.autorizar.loja') }}">Autorizar Loja</a>
                    </div>
                @else
                    <div class="card text-white mb-4">
                        <div class="card-body text-dark">
                            TikTokShop
                            <img src="https://img.freepik.com/premium-vector/tiktok-shop-simple-icon_985537-63.jpg"
                                width="62" height="62" class="float-end">
                        </div>

                        {{-- Botão de Integração --}}
                        <a class="btn btn-primary py-2 mt-2" href="{{ route('tiktok.redirect') }}">
                            Integrar Loja
                        </a>

                        {{-- Botão de Autorização --}}
                        <a class="btn btn-secondary py-2 mt-2" href="{{ route('tiktok.autorizar.loja') }}">
                            Autorizar Loja
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
        });
    </script>
@endpush
