<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ifAuthMsg
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
        if(Auth::user() && Auth::user()->role == 'client'){
            return redirect()->route('home')->with('mgs_login',"Usuário".Auth::user()->name."Não tem Permissão para Navegar nesta Página");
        }else{
            return $next($request);
        }
    }
}
