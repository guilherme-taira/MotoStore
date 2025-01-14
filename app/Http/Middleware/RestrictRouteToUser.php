<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

    class RestrictRouteToUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Substitua pelo seu ID ou e-mail de usuário
        $user = User::where('id',2)->first();
        $allowedUserId = $user->id; // Exemplo com ID
        $allowedEmail = $user->email; // Exemplo com e-mail

        // Verifica se o usuário está autenticado e se é você
        if (Auth::check() && (Auth::id() === $allowedUserId || Auth::user()->email === $allowedEmail)) {
            return $next($request);
        }

        // Bloqueia o acesso
        return redirect()->route('home')->with('error', 'Página em breve disponivel..');

    }
}
