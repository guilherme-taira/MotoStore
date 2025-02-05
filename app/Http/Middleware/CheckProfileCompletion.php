<?php

namespace App\Http\Middleware;

use App\Models\Contato;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class CheckProfileCompletion
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
        // if (Auth::check()) {
        //     $userId = Auth::user()->id;

        //     // Cachear a consulta por 10 minutos para melhorar a performance
        //     $userHasProfile = Cache::remember("user_profile_$userId", now()->addMinutes(10), function () use ($userId) {
        //         return Contato::where('integracao_bling_id', $userId)->exists();
        //     });

        //     if (!$userHasProfile) {
        //         return redirect()->route('contatos.create')
        //             ->with('error', 'Por favor, preencha seu perfil antes de continuar.');
        //     }
        // }

        return $next($request);
    }
}
