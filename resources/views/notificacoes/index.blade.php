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
            <li>
                <a class="dropdown-item notification-item"
                   @if(isset($notification->data['link'])) href="{{ $notification->data['link'] }}"
                   @elseif(isset($notification->data['orderid'])) href="{{ route('orders.show', ['id' => $notification->data['orderid']]) }}"
                   @else onclick="marcarComoLido('{{ $notification->id }}')"
                   @endif>
                    <div class="notification-content mt-2">
                        @if(isset($notification->data['id']) && isset($notification->data['image']))
                        <div class="notification-image-container" style="width: 150px; height: 150px; overflow: hidden; border-radius: 5px;">
                            <img src="{!! Storage::disk('s3')->url('produtos/' . $notification->data['id'] . '/' . $notification->data['image']) !!}"
                                 alt="Produto"
                                 style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        @else
                            <div class="notification-image-container" style="width: 50px; height: 50px; overflow: hidden; border-radius: 5px;">
                                <img src="https://img.icons8.com/clouds/100/000000/rocket.png"
                                    alt="Imagem Padrão"
                                    style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                        @endif


                        <div class="notification-text">
                            <span class="notification-title">
                                @if(isset($notification->data['type']) && $notification->data['type'] == "produto")
                                    Produto Atualizado <i class="bi bi-chat-left-text-fill"></i>
                                @else
                                    Notificação Geral <i class="bi bi-bell-fill"></i>
                                @endif
                            </span>

                            <span class="notification-details">
                                {{ $notification->data['mensagem'] ?? 'Detalhes não disponíveis.' }}
                            </span>

                            @if(isset($notification->data['ml_id']))
                                <span class="notification-subtitle">
                                    ID da Plataforma - {{ $notification->data['ml_id'] }}
                                </span>
                            @endif

                            @if(isset($notification->data['oldPrice']) && isset($notification->data['newPrice']))
                                <span class="notification-subtitle bg-dark text-white px-2">
                                    <i class="bi bi-coin"></i> De: R${{ $notification->data['oldPrice'] }} ~ Para: R${{ $notification->data['newPrice'] }}
                                </span>
                            @endif

                            <span class="notification-date text-muted d-block mt-1" style="font-size: 0.85em;">
                                {{ $notification->created_at->format('d/m/Y H:i') }}
                            </span>
                        </div>
                    </div>
                </a>
            </li>
            @endforeach
        @else
            <p>Nenhuma notificação encontrada.</p>
        @endif
    </ul>
</div>
@endsection
