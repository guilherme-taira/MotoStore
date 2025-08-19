<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class produtos_integrados extends Model
{
    use HasFactory;

    protected $casts = [
        'dados' => 'array',
    ];

    protected $table = 'produtos_integrados';

    protected $fillable = ['priceNotFee', 'acrescimo_reais', 'acrescimo_porcentagem','desconto_reais','desconto_porcentagem','isPorcem','precofixo'];

    public static function getProdutos($user){
        $data = DB::table('products')
            ->join('produtos_integrados', 'products.id', '=', 'produtos_integrados.product_id')
            ->select(
                'produtos_integrados.id_mercadolivre',
                'produtos_integrados.name',
                'produtos_integrados.product_id',
                'products.image',
                'produtos_integrados.id',
                'produtos_integrados.created_at',
                'produtos_integrados.priceNotFee',
                'produtos_integrados.acrescimo_reais',
                'produtos_integrados.acrescimo_porcentagem',
                'produtos_integrados.desconto_reais',
                'produtos_integrados.desconto_porcentagem',
                'produtos_integrados.isPorcem',
                'produtos_integrados.precofixo',
                'produtos_integrados.active',
                'produtos_integrados.estoque_minimo',
                'products.variation_data',
                'products.isVariation'
            )
            ->where('user_id', $user)
            ->orderBy('produtos_integrados.created_at', 'desc')
            ->paginate(10);

        return $data;
    }




    public static function getProdutosByApi($user){
        $data = DB::table('products')
        ->join('produtos_integrados', 'products.id', '=', 'produtos_integrados.product_id')
        ->select('produtos_integrados.id_mercadolivre','produtos_integrados.name','produtos_integrados.product_id','products.image','produtos_integrados.id','produtos_integrados.created_at',
        'produtos_integrados.priceNotFee','produtos_integrados.acrescimo_reais','produtos_integrados.acrescimo_porcentagem','produtos_integrados.desconto_reais','produtos_integrados.desconto_porcentagem','produtos_integrados.isPorcem','produtos_integrados.precofixo','produtos_integrados.active','produtos_integrados.estoque_minimo','produtos_integrados.created_at')
        ->where('user_id', $user)
        ->orderBy('produtos_integrados.created_at','desc')
        ->paginate(10);
    return $data;
    }

    // no model ProdutosIntegrados
    public function variacoes()
    {
        return $this->hasMany(Variacao::class, 'id_mercadolivre', 'id_mercadolivre');
    }


    public static function cadastrar($name,$image,$id_prod){
        $integrado = new produtos_integrados();
        $integrado->name = $name;
        $integrado->image = $image;
        $integrado->product_id = $id_prod;
        $integrado->user_id = Auth::user()->id;
        $integrado->save();
    }

    public static function removeStockProduct($produto,$quantidade){
        $data = produtos_integrados::where('id',$produto)->first();
        if($data){
            $atualStock = Products::where('id',$data->product_id)->first();
            $novoEstoque = $atualStock->available_quantity - $quantidade;
            Products::where('id',$data->product_id)->update([
                'available_quantity' => $novoEstoque
            ]);
        }
    }

    // Mutator para acrescimo_reais
    public function setAcrescimoReaisAttribute($value)
    {
        $this->attributes['acrescimo_reais'] = $value;
        if ($value) {
            $this->attributes['acrescimo_porcentagem'] = null;
            $this->attributes['desconto_reais'] = null;
            $this->attributes['desconto_porcentagem'] = null;
            $this->attributes['precofixo'] = null; // Zera precofixo
        }
    }

    // Mutator para acrescimo_porcentagem
    public function setAcrescimoPorcentagemAttribute($value)
    {
        $this->attributes['acrescimo_porcentagem'] = $value;
        if ($value) {
            $this->attributes['acrescimo_reais'] = null;
            $this->attributes['desconto_reais'] = null;
            $this->attributes['desconto_porcentagem'] = null;
            $this->attributes['precofixo'] = null; // Zera precofixo
        }
    }

    // Mutator para desconto_reais
    public function setDescontoReaisAttribute($value)
    {
        $this->attributes['desconto_reais'] = $value;
        if ($value) {
            $this->attributes['acrescimo_reais'] = null;
            $this->attributes['acrescimo_porcentagem'] = null;
            $this->attributes['desconto_porcentagem'] = null;
            $this->attributes['precofixo'] = null; // Zera precofixo
        }
    }

    // Mutator para desconto_porcentagem
    public function setDescontoPorcentagemAttribute($value)
    {
        $this->attributes['desconto_porcentagem'] = $value;
        if ($value) {
            $this->attributes['acrescimo_reais'] = null;
            $this->attributes['acrescimo_porcentagem'] = null;
            $this->attributes['desconto_reais'] = null;
            $this->attributes['precofixo'] = null; // Zera precofixo
        }
    }

    // Mutator para precofixo
    public function setPrecofixoAttribute($value)
    {
        $this->attributes['precofixo'] = $value;
        if ($value) {
            $this->attributes['acrescimo_reais'] = null;
            $this->attributes['acrescimo_porcentagem'] = null;
            $this->attributes['desconto_reais'] = null;
            $this->attributes['desconto_porcentagem'] = null;
        }
    }

}
