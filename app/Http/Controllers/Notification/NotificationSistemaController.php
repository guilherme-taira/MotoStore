<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationSistemaController extends Controller
{
    public function index()
    {
        // Obtém todas as notificações do usuário autenticado
        $notificacoes = Auth::user()->notifications;

        // Retorna a view com as notificações
        return view('notificacoes.index', compact('notificacoes'));
    }

    public function marcarComoLidaERedirecionar($id)
    {
        $notification = Auth::user()->unreadNotifications->where('id', $id)->first();

        if ($notification) {
            $notification->markAsRead();
            $url = $notification->data['url'] ?? '/vendas'; // URL de redirecionamento padrão
            return redirect($url);
        }

        return redirect('/vendas'); // Redireciona para a página de vendas se a notificação não for encontrada
    }
}
