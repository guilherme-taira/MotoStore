<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\token;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
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


    public function getTokenUpMineracao(Request $request){
        Log::alert($request->all());
        // Busca o token no banco de dados pelo user_id
        $token = token::where('user_id', $request->id)->first();

        // Verifica se o token foi encontrado
        if ($token) {
            return response()->json([
                'access_token' => $token->access_token
            ]);
        } else {
            return response()->json([
                'error' => 'Token não encontrado para este usuário.'
            ], 404);
        }

    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ], [
            'name.required' => 'O nome é obrigatório.',
            'name.max' => 'O nome não pode ter mais que 255 caracteres.',

            'email.required' => 'O e-mail é obrigatório.',
            'email.email' => 'Informe um e-mail válido.',
            'email.unique' => 'Este e-mail já está em uso.',

            'password.required' => 'A senha é obrigatória.',
            'password.min' => 'A senha deve ter no mínimo 6 caracteres.',
        ]);


        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);
        return response()->json(['message' => 'Usuário criado com sucesso!', 'user' => $user], 201);
    }


    public function getDataFromApi(Request $request)
    {
        // Valida o email
        $validated = $request->validate([
            'email' => 'required|email',
        ]);

        // Verifica se o email existe na tabela 'users' e faz o join com a tabela 'token'
        $user = User::leftJoin('token_up_mineracao', 'users.id', '=', 'token_up_mineracao.user_id') // Assuming 'user_id' is the foreign key in 'token'
                    ->where('users.email', $validated['email'])
                    ->select('users.email', 'users.id','token_up_mineracao.user_id_mercadolivre as integrado','users.name as nome') // Seleciona apenas o email e access_token
                    ->first();

        // Se o email não existir ou não encontrar o token correspondente
        if (!$user) {
            return response()->json([
                'message' => 'Email não encontrado na base de dados.',
            ], 404); // Status 404 - Not Found
        }

        // Retorna o email e access_token encontrados
        return response()->json([
            'email' => $user->email,
            'user_id' => $user->id,
            'code' => 200,
            'integrado' => $user->integrado,
            'nome' => $user->nome
        ], 200);
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
