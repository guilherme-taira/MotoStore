@extends('layouts.app')

@section('conteudo')
<style>
    /* Estilos customizados para os cards de notificação */
    .notification-card {
        margin: 0 auto 20px auto; /* Centraliza horizontalmente com margem inferior */
        max-width: 600px; /* Define largura máxima menor */
        transition: box-shadow 0.3s;
    }
    .notification-card:hover {
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .notification-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
</style>

<div class="container">
    <h1 class="mb-4 text-center">Todas as Notificações</h1>
    @if(isset(Auth::user()->notifications) && count(Auth::user()->notifications) > 0)
        @foreach(Auth::user()->notifications as $notification)
            <div class="card notification-card">
                <div class="row g-0">
                    <div class="col-md-4">
                        @if(isset($notification->data['id']) && isset($notification->data['image']))
                            <img src="{!! Storage::disk('s3')->url('produtos/' . $notification->data['id'] . '/' . $notification->data['image']) !!}"
                                 class="notification-img" alt="Produto">
                        @else
                            <img src="https://img.icons8.com/clouds/100/000000/rocket.png"
                                 class="notification-img" alt="Imagem Padrão">
                        @endif
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title">
                                @if(isset($notification->data['type']) && $notification->data['type'] == "produto")
                                    Produto Atualizado <i class="bi bi-chat-left-text-fill"></i>
                                @else
                                    Notificação Geral <i class="bi bi-bell-fill"></i>
                                @endif
                            </h5>
                            <p class="card-text">
                                {{ $notification->data['mensagem'] ?? 'Detalhes não disponíveis.' }}
                            </p>
                            @if(isset($notification->data['ml_id']))
                                <p class="card-text">
                                    <small class="text-muted">ID da Plataforma - {{ $notification->data['ml_id'] }}</small>
                                </p>
                            @endif
                            @if(isset($notification->data['oldPrice']) && isset($notification->data['newPrice']))
                                <p class="card-text">
                                    <small class="text-muted">
                                        <i class="bi bi-coin"></i> De: R${{ $notification->data['oldPrice'] }} ~ Para: R${{ $notification->data['newPrice'] }}
                                    </small>
                                </p>
                            @endif
                            <p class="card-text">
                                <small class="text-muted">{{ $notification->created_at->format('d/m/Y H:i') }}</small>
                            </p>
                            {{-- @if(isset($notification->data['link']))
                                <a href="{{ $notification->data['link'] }}" class="btn btn-primary btn-sm">Ver Detalhes</a> --}}
                            {{-- @elseif(isset($notification->data['orderid'])) --}}
                            @if(isset($notification->data['orderid']))
                                <a href="{{ route('orders.show', ['id' => $notification->data['orderid']]) }}" class="btn btn-primary btn-sm">Ver Pedido</a>
                            @else
                                <button onclick="marcarComoLido('{{ $notification->id }}')" class="btn btn-secondary btn-sm">Marcar como Lido</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="alert alert-info text-center" role="alert">
            Nenhuma notificação encontrada.
        </div>
    @endif
</div>
@endsection
