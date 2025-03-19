<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    public function cadastraUserApi(Request $request)
    {
        // Decodifica os dados enviados na chave "data"
        $decodedData = json_decode($request->get('data'), true);

        // Validação dos dados usando o array decodificado
        $validator = Validator::make($decodedData, [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            // Se for necessário confirmar a senha, envie também 'password_confirmation'
            // 'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Erro de validação',
                'errors'  => $validator->errors()
            ], 422);
        }

        try {
            // Criação do usuário com os dados validados
            $user = User::create([
                'name'     => $decodedData['name'],
                'email'    => $decodedData['email'],
                'password' => Hash::make($decodedData['password']),
            ]);
        } catch (\Exception $e) {
            // Caso ocorra algum erro, como duplicidade de email, retorna um erro
            return response()->json([
                'message' => 'Erro ao cadastrar usuário',
                'error'   => $e->getMessage()
            ], 500);
        }

        // Criação do token via Sanctum
        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'user'    => $user,
            'token'   => $token,
            'message' => 'Usuário cadastrado com sucesso.'
        ], 201);
    }




    public function loginApi(Request $request){

    Log::alert($request->all());
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt($credentials)) {
        $token = $request->user()->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user_id' => Auth::user()->id,
            'username' => Auth::user()->name
        ]);
    }

    return response()->json(['message' => 'Credenciais inválidas'], 401);
}

}
