<?php

namespace App\Http\Middleware;

use App\Models\Products;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class verificaCadastro
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

        if(count($request->all()) == 0){
            return $next($request);
        }

        if(auth()->check()){
            $produto = Products::where('fornecedor_id',auth()->user()->id)->where('id','=',$request->id)->first();
            if($produto){
                return $next($request);
            }
        }
        return redirect(route('allProductsByFornecedor'))->with('msg_error',"Você não tem permissão para editar esse produto!");

    }
}
