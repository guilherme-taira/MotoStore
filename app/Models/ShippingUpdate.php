<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ShippingUpdate extends Model
{
    use HasFactory;


    // Define a tabela associada ao modelo
    protected $table = 'shipping_updates';

    // Define os campos que podem ser preenchidos em massa
    protected $fillable = [
        'id_shopify',
        'rastreio',
        'url_rastreio',
        'isBrazil',
        'id_mercadoLivre',
        'id_user',
        'id_vendedor',
        'msg',
        'observacaomeli',
        'id_meli',
        'was_damaged',
        'was_delivered',
        'was_delivered_to_sender',
        'was_forwarded',
        'was_fulfilled',
        'was_misplaced',
        'was_refused',
        'was_returned',
        'was_scheduled',
        'id_rastreio'
    ];

    public static function getDataById($id){
        $data = DB::table('shipping_updates')
        ->where('id_shopify','=',$id)->first();
        return $data;
    }


    public static function getDataByIdMeli(Request $request, $id)
    {
        $query = ShippingUpdate::query();

        if ($request->filled('id')) {
            $query->where('id', $request->id);
        }
        if ($request->filled('data')) {
            $query->whereDate('data', $request->data);
        }
        if ($request->filled('id_venda')) {
            $query->where('id_mercadoLivre', $request->id_venda);
        }
        if ($request->filled('status')) {
            $query->where($request->status, 1);
        }
        if ($request->filled('rastreio')) {
            $query->where('rastreio', 'like', '%' . $request->rastreio . '%');
        }
        if ($request->filled('comprado')) {
            $query->whereDate('comprado', $request->comprado);
        }
        if ($request->filled('aliexpress_id')) {
            $query->where('aliexpress_id', 'like', '%' . $request->aliexpress_id . '%');
        }
        if ($request->filled('rastreado')) {
            $query->where('rastreado', $request->rastreado);
        }

        $paginator = $query->where('id_vendedor', '=', $id)
            ->orderBy('id', 'desc')  // Substitua 'id' pelo campo que deseja ordenar
            ->paginate(100);

        // Modifica os itens do paginador, mantendo o objeto de paginação intacto
        $paginator->getCollection()->transform(function ($item) {
            // Inicializa uma variável para armazenar o campo 'was_' encontrado
            $wasField = null;

            // Obtenha todos os atributos do item
            $attributes = $item->getAttributes();

            // Verifique se algum campo começa com 'was_' e tem o valor 1
            foreach ($attributes as $key => $value) {
                if (strpos($key, 'was_') === 0 && $value == 1) {
                    // Define o campo encontrado
                    $wasField = $key;
                    break; // Sai do loop assim que encontrar o primeiro campo com valor 1
                }
            }

            // Adicione o campo encontrado ao array de atributos
            if ($wasField) {
                $item->setAttribute('was_field', $wasField);
            }

            return $item;
        });

        return $paginator;
    }



    public static function extrairNumeros($texto) {
        // Expressão regular para encontrar todos os dígitos
        preg_match('/\d+/', $texto, $matches);
        // Retorna o primeiro conjunto de números encontrado
        return isset($matches[0]) ? $matches[0] : "";
    }


    public static function getStatus($status){
        $array = [
            'was_damaged' => "<span class='badge text-bg-danger'>Foi danificado</span>",
            'was_delivered' => "<span class='badge text-bg-success'>Foi entregue</span>",
            'was_delivered_to_sender' => "<span class='badge text-bg-warning'>Foi devolvido ao remetente</span>",
            'was_forwarded' => "<span class='badge text-bg-primary'>Foi encaminhado</span>",
            'was_fulfilled' => "<span class='badge text-bg-info'>Foi realizado</span>",
            'was_misplaced' => "<span class='badge text-bg-secondary'>Foi extraviado</span>",
            'was_refused' => "<span class='badge text-bg-dark'>Foi recusado</span>",
            'was_returned' => "<span class='badge text-bg-light'>Foi devolvido</span>",
            'was_scheduled' => "<span class='badge text-bg-muted'>Foi agendado</span>",
            '' => "<span class='badge text-bg-warning'>Aguardando..</span>"
        ];


        foreach ($array as $key => $value) {
            if($key == $status){
                return $value;
            }
        }
    }

    public static function getIntegrado($status){

        if($status != ""){
            return "<span class='badge text-bg-success'>OK</span>";
        }else{
            return "<span class='badge text-bg-danger'>N</span>";
        }
    }

    public static function ifExist($id){
        $data = DB::table('shipping_updates')
        ->where('id_mercadolivre','=',$id)->first();

        if($data){
            return false;
        }else{
            return true;
        }
    }
}
