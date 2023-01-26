<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class admin
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
        if (Auth::user() && Auth::user()->role == 'admin') {
            return $next($request);
        } else {
            if (Auth::user()) {
                return redirect()->route('login')->with('mgs_login', "Usuário:".Auth::user()->email." Não tem Permissão para Navegar nesta Página");
            } else {
                return redirect()->route('login')->with('mgs_login', 'Para Acessar essa tela precisa estar conectado!');
            }
        }
    }
}
