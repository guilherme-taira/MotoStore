@extends('layouts.app')

@section('conteudo')
<style>
    .notification-list {
    padding: 0;
    margin: 0;
}

.notification-item {
    padding: 15px;
    border-bottom: 1px solid #ddd;
}

.notification-link {
    text-decoration: none;
    color: inherit;
}

.notification-content {
    display: flex;
    align-items: flex-start;
}

.notification-image {
    width: 80px;
    height: 80px;
    object-fit: cover;
    margin-right: 15px;
    border-radius: 5px;
}

.notification-text {
    flex-grow: 1;
}

.notification-title {
    font-weight: bold;
    font-size: 16px;
    color: #333;
}

.notification-details {
    margin: 5px 0;
    color: #555;
    font-size: 14px;
}

.notification-subtitle {
    font-size: 13px;
    color: #777;
}

.notification-subtitle.bg-dark {
    padding: 2px 5px;
    display: inline-block;
    border-radius: 3px;
    margin-top: 5px;
}

.notification-date {
    font-size: 12px;
    color: #888;
    margin-left: auto;
    text-align: right;
}

</style>
<div class="container">
    <h1 class="mb-4">Todas as Notificações</h1>

    <ul class="notification-list list-unstyled">
        @if(isset(Auth::user()->notifications))
            @foreach (Auth::user()->notifications as $notification)
                <li class="notification-item">
                    @if (isset($notification->data['type']) &&  $notification->data['type'] == "produto")
                        <a onclick="marcarComoLido('{{ $notification->id }}')" class="notification-link">
                            <div class="notification-content d-flex">
                                <img src="{!! Storage::disk('s3')->url('produtos/' . $notification->data['id'] . '/' . $notification->data['imagem']) !!}" alt="Produto" class="notification-image">
                                <div class="notification-text">
                                    <div class="d-flex justify-content-between">
                                        <span class="notification-title">Produto Atualizado <i class="bi bi-chat-left-text-fill"></i></span>
                                        <span class="notification-date">{{ $notification->created_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                    <p class="notification-details">{{ $notification->data['mensagem'] }}</p>
                                    <p class="notification-subtitle">Seu ID na Plataforma - {{ $notification->data['ml_id'] }}</p>
                                    <p class="notification-subtitle bg-dark text-white px-2">
                                        <i class="bi bi-coin"></i> Desatualizado R${{ $notification->data['oldPrice'] }} ~ Novo: R${{ $notification->data['newPrice'] }}
                                    </p>
                                </div>
                            </div>
                        </a>
                    @else
                        @if(isset($notification->data['orderid']))
                        <a href="{{ route('orders.show', ['id' => $notification->data['orderid']]) }}" class="notification-link">
                            <div class="notification-content d-flex">
                                <img src="{!! Storage::disk('s3')->url('produtos/' . $notification->data['id'] . '/' . $notification->data['image']) !!}" alt="Produto" class="notification-image">
                                <div class="notification-text">
                                    <div class="d-flex justify-content-between">
                                        <span class="notification-title">Você Vendeu! <i class="bi bi-bag-plus-fill"></i></span>
                                        <span class="notification-date">{{ $notification->created_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                    <p class="notification-details">{{ $notification->data['mensagem'] }}</p>
                                    <p class="notification-subtitle">ID da Venda na Plataforma - {{ $notification->data['ml_id'] }}</p>
                                    <p class="notification-subtitle bg-dark text-white px-2"><i class="bi bi-cart4"></i> Ver Mais</p>
                                </div>
                            </div>
                        </a>
                        @else
                        <div class="notification-content d-flex">
                            <img src="{!! Storage::disk('s3')->url('produtos/' . $notification->data['id'] . '/' . $notification->data['image']) !!}" alt="Produto" class="notification-image">
                            <div class="notification-text">
                                <div class="d-flex justify-content-between">
                                    <span class="notification-title">Você Vendeu! <i class="bi bi-bag-plus-fill"></i></span>
                                    <span class="notification-date">{{ $notification->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <p class="notification-details">{{ $notification->data['mensagem'] }}</p>
                                <p class="notification-subtitle">ID da Venda na Plataforma - {{ $notification->data['ml_id'] }}</p>
                                <p class="notification-subtitle bg-dark text-white px-2"><i class="bi bi-cart4"></i> Ver Mais</p>
                            </div>
                        </div>
                        @endif
                    @endif
                </li>
            @endforeach
        @else
            <p>Nenhuma notificação encontrada.</p>
        @endif
    </ul>
</div>
@endsection
