<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class getUserInfoController extends Controller
{
    public function infoSearch(Request $request){
        $data = json_encode(User::where('name', 'LIKE', '%'.$request->name.'%')->get());
        if ($data) {
            return response()->json(['dados' => $data], 200);
        }
        return response()->json('Error: Não Há Registro Com esse Nome', 404);
    }
}
