<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Bling\BlingContatos;
use App\Models\Contato;
use App\Models\IntegracaoBling;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ContatoController extends Controller
{
    public function index()
    {
        // Listar todos os contatos
        $contatos = Contato::with('integracaoBling')->get();
        return response()->json($contatos);
    }

    public function create()
    {
    // Obter integrações disponíveis para associar a um novo contato

        $data = IntegracaoBling::where('user_id',Auth::user()->id)->first();
        $viewData = [];
        $viewData['integracao_bling'] = $data->id;
        return view('bling.contato',[
            'viewData' => $viewData
        ]);
    }

    public function store(Request $request)
    {
        // Validar os dados
        $validated = $request->validate([
            'integracao_bling_id' => 'required',
            'nome' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'celular' => 'nullable|string|max:20|regex:/^\(?\d{2}\)?[\s-]?\d{4,5}[-\s]?\d{4}$/',
            'numeroDocumento' => 'nullable|string|max:20',
            'tipo' => 'required|string|in:F,J',
            'situacao' => 'required|string|in:A,I',
            'rg' => 'nullable|string|max:20',
            'cep' => 'required|string|max:10',
            'endereco' => 'required|string|max:255',
            'bairro' => 'required|string|max:255',
            'municipio' => 'required|string|max:255',
            'uf' => 'required|string|max:2',
            'numero' => 'required|string|max:20',
            'complemento' => 'nullable|string|max:255',
        ], [
            // Mensagens personalizadas para cada campo
            'integracao_bling_id.required' => 'O campo Integração Bling é obrigatório.',
            'integracao_bling_id.exists' => 'A Integração Bling fornecida não é válida.',
            'nome.required' => 'O campo Nome é obrigatório.',
            'nome.string' => 'O campo Nome deve ser uma string.',
            'nome.max' => 'O campo Nome não pode ter mais que 255 caracteres.',
            'email.email' => 'O campo E-mail deve conter um endereço de e-mail válido.',
            'email.max' => 'O campo E-mail não pode ter mais que 255 caracteres.',
            'celular.regex' => 'O campo Celular deve estar no formato (XX) XXXXX-XXXX.',
            'celular.max' => 'O campo Celular não pode ter mais que 20 caracteres.',
            'numeroDocumento.max' => 'O campo Número do Documento não pode ter mais que 20 caracteres.',
            'tipo.required' => 'O campo Tipo é obrigatório.',
            'tipo.in' => 'O campo Tipo deve ser F (Física) ou J (Jurídica).',
            'situacao.required' => 'O campo Situação é obrigatório.',
            'situacao.in' => 'O campo Situação deve ser A (Ativo) ou I (Inativo).',
            'rg.max' => 'O campo RG não pode ter mais que 20 caracteres.',
            'cep.required' => 'O campo CEP é obrigatório.',
            'cep.max' => 'O campo CEP não pode ter mais que 10 caracteres.',
            'endereco.required' => 'O campo Endereço é obrigatório.',
            'endereco.max' => 'O campo Endereço não pode ter mais que 255 caracteres.',
            'bairro.required' => 'O campo Bairro é obrigatório.',
            'bairro.max' => 'O campo Bairro não pode ter mais que 255 caracteres.',
            'municipio.required' => 'O campo Município é obrigatório.',
            'municipio.max' => 'O campo Município não pode ter mais que 255 caracteres.',
            'uf.required' => 'O campo UF é obrigatório.',
            'uf.max' => 'O campo UF não pode ter mais que 2 caracteres.',
            'numero.required' => 'O campo Número é obrigatório.',
            'numero.max' => 'O campo Número não pode ter mais que 20 caracteres.',
            'complemento.max' => 'O campo Complemento não pode ter mais que 255 caracteres.',
        ]);



        // Criar o contato
        $contato = Contato::create($validated);

        $token = IntegracaoBling::where('id',$validated['integracao_bling_id'])->first();
         // Dados para o Bling
         $blingData = [
            'nome' => $validated['nome'],
            'tipo' => $validated['tipo'],
            'numeroDocumento' => $validated['numeroDocumento'],
            'situacao' => $validated['situacao'],
            'celular' => $validated['celular'],
            'email' => $validated['email'],
            'rg' => $validated['rg'] ?? null,
            'endereco' => [
                'geral' => [
                    'endereco' => $validated['endereco'],
                    'cep' => $validated['cep'],
                    'bairro' => $validated['bairro'],
                    'municipio' => $validated['municipio'],
                    'uf' => $validated['uf'],
                    'numero' => $validated['numero'],
                    'complemento' => $validated['complemento'] ?? null,
                ],
            ],
        ];

        // // Enviar para o Bling
        try {
            $blingResponse = new BlingContatos($token->access_token,$contato['id']);
            $blingResponse->enviarContato($blingData);

        } catch (\Exception $e) {
            return redirect('/bling')->with('error', $e->getMessage());
        }

        return redirect('/bling')->with('success', 'Contato criado e enviado para o Bling com sucesso!');
    }

    public function edit($id)
    {
        $viewData = [];
        // Exibir um único contato
        $viewData['contato'] = Contato::with('integracaoBling')->findOrFail($id);
        return view('bling.contatoEdit',[
            'viewData' => $viewData
        ]);
    }

    public function update(Request $request, $id)
    {
        // Validar os dados
        $validated = $request->validate([
            'nome' => 'sometimes|string|max:255',
            'tipo' => 'sometimes|string|in:F,J',
            'situacao' => 'sometimes|string|in:A,I',
            'rg' => 'nullable|string|max:20',
            'cep' => 'sometimes|string|max:10',
            'endereco' => 'sometimes|string|max:255',
            'bairro' => 'sometimes|string|max:255',
            'municipio' => 'sometimes|string|max:255',
            'uf' => 'sometimes|string|max:2',
            'numero' => 'sometimes|string|max:20',
            'complemento' => 'nullable|string|max:255',
        ]);

        // Atualizar o contato
        $contato = Contato::findOrFail($id);
        $contato->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Contato atualizado com sucesso!',
            'data' => $contato,
        ]);
    }

    public function destroy($id)
    {
        // Excluir o contato
        $contato = Contato::findOrFail($id);
        $contato->delete();

        return response()->json([
            'success' => true,
            'message' => 'Contato excluído com sucesso!',
        ]);
    }
}
