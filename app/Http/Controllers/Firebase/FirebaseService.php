<?php

namespace App\Http\Controllers\Firebase;

use App\Http\Controllers\Controller;
use App\Models\FcmToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Factory;

class FirebaseService extends Controller
{

  public function saveTokenPhoneAuth(Request $request){
    Log::alert($request->all());
     // Validação dos dados enviados
     $request->validate([
        'user_id' => 'required|exists:users,id',
        'token'   => 'required|string',
    ]);

    $userId = $request->input('user_id');
    $token = $request->input('token');

    // Atualiza ou cria o registro para evitar duplicação
    $fcmToken = FcmToken::updateOrCreate(
        ['token' => $token], // condição para encontrar o token
        ['user_id' => $userId] // dados a atualizar ou inserir
    );

    return response()->json([
        'message' => 'FCM token cadastrado com sucesso.',
        'data'    => $fcmToken,
    ]);
  }
}
