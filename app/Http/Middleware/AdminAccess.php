<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role == '') {
            return $next($request);
        } else {
            if (Auth::user()) {
                return redirect()->route('home')->with('mgs_login', "Usuário:".Auth::user()->email." Não tem Permissão para Navegar nesta Página");
            } else {
                return redirect()->route('login')->with('mgs_login', 'Para Acessar essa tela precisa estar conectado!');
            }
        }
    }
}
