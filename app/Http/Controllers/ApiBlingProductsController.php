<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\images;
use App\Models\IntegracaoBling;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ApiBlingProductsController extends Controller
{
    public function index(Request $request)
    {
        // Busque a integração do usuário autenticado
            $integracao = IntegracaoBling::where('user_id', $request->user_id)->first();

        if (!$integracao || !$integracao->access_token) {
            return response()->json(['error' => 'Integração não configurada ou token inválido.'], 401);
        }

        try {
            // Fazer a requisição à API do Bling
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$integracao->access_token}",
                'Accept' => 'application/json',
            ])->get('https://www.bling.com.br/Api/v3/produtos');

            if ($response->successful()) {
                return response()->json($response->json(), 200);
            } else {
                return response()->json([
                    'error' => 'Erro ao buscar produtos no Bling.',
                    'details' => $response->body()
                ], $response->status());
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Exceção ao buscar produtos no Bling.',
                'message' => $e->getMessage()
            ], 500);
        }
    }

        public function storeBling(Request $request)
        {

            // Criar o produto
            $produto = new Products();
            $produto->price = $request->price;
            $produto->title = $request->name;
            $produto->description = $request->description;
            $produto->available_quantity = $request->stock;
            $produto->priceWithFee = $request->PriceWithFee;
            $produto->category_id = $request->id_categoria;
            $produto->brand = $request->brand;
            $produto->gtin = $request->ean;
            $produto->height = $request->height;
            $produto->width = $request->width;
            $produto->length = $request->length;
            $produto->fornecedor_id = Auth::user()->id;
            // Use a primeira imagem do array photos, se existir
            $produto->image = $request->photos[0] ?? 'default-image.png';
            $produto->priceKit = $request->priceKit;
            $produto->save();


          // Processar URLs de imagens
        if (!empty($request->photos)) {
            foreach ($request->photos as $index => $photoUrl) {
                try {
                    // Baixar a imagem do URL original
                    $response = Http::get($photoUrl);
                    if ($response->successful()) {
                        $imageContent = $response->body();

                        // Gerar nome do arquivo baseado no nome do produto
                        $filename = Str::slug($produto->title) . ($index ? "-{$index}" : '') . '.jpg';

                        // Caminho no S3
                        $s3Path = 'produtos/' . $produto->id . '/' . $filename;

                        // Salvar no S3
                        Storage::disk('s3')->put($s3Path, $imageContent);

                        // Obter a URL da AWS S3
                        $awsUrl = Storage::disk('s3')->url($s3Path);

                        // Atualizar a imagem principal se for a primeira
                        if ($index === 0) {
                            $produto->image = $filename; // Salvamos apenas o nome do arquivo no banco
                            $produto->save();
                        }

                        // Salvar no banco de dados
                        $image = new Images();
                        $image->url = $awsUrl;
                        $image->product_id = $produto->id;
                        $image->save();
                    }
                } catch (\Exception $e) {
                    Log::error("Erro ao processar a imagem: {$photoUrl}", ['exception' => $e->getMessage()]);
                }
            }
        }
            return redirect()->route('allProductsByFornecedor')->with('success', 'Produto importado com sucesso!');
    }

    public function createOrder(Request $request){

        // Validação básica
        $validatedData = $request->validate([
            'numeroLoja' => 'required|string',
            'itens' => 'required|array|min:1',
            'contato.id' => 'required|integer',
            'contato.numeroDocumento' => 'required|string',
            'contato.tipoPessoa' => 'nullable|string|in:F,J', // F para física, J para jurídica
        ]);

        // Verificar integração do usuário
        $integracao = IntegracaoBling::where('user_id', $request->user_id)->first();

        if (!$integracao) {
            return response()->json([
                'success' => false,
                'message' => 'Integração não encontrada para o usuário informado.',
            ], 404);
        }

        $url = 'https://api.bling.com.br/Api/v3/pedidos/vendas';

        // Montar o payload
        $payload = [
            "numeroLoja" => $validatedData['numeroLoja'],
            "data" => now()->format('Y-m-d'),
            "itens" => $validatedData['itens'],
            "contato" => [
                "id" => $validatedData['contato']['id'],
                "tipoPessoa" => $validatedData['contato']['tipoPessoa'] ?? 'J',
                "numeroDocumento" => $validatedData['contato']['numeroDocumento'],
            ],
        ];

        Log::alert(json_encode($payload));
         // Fazer a requisição à API do Bling
         $response = Http::withHeaders([
            'Authorization' => "Bearer {$integracao->access_token}",
            'Accept' => 'application/json',
        ])->post($url, [
            'json' => $payload,
        ]);

        // Verificar a resposta da API
        if ($response->successful()) {
            return response()->json([
                'success' => true,
                'message' => 'Pedido criado com sucesso no Bling!',
                'data' => $response->json(),
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar o pedido no Bling.',
                'error' => $response->json(),
            ], $response->status());
        }
    }

    public function getProdutosPaginados(Request $request){
        $produtos = Products::where('isKit','=','0')
        ->where('isPublic','=',1)
        ->paginate(10);
        // Adiciona o URL completo da imagem para cada produto
        foreach ($produtos as $produto) {
            $produto->imagem_url = Storage::disk('s3')->url('produtos/' . $produto->id . '/' . $produto->image);
        }
        return response()->json($produtos);
    }


}
