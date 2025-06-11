<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KitProductVariation extends Model
{
    use HasFactory;

    protected $table = 'kit_product_variations';

    protected $fillable = [
        'kit_id',
        'product_id',
        'sku',
        'custom_price',
        'quantity',
        'attribute_combinations',
        'picture_ids',
        'fornecedor_id',
    ];

    protected $casts = [
        'attribute_combinations' => 'array',
        'picture_ids' => 'array',
    ];

    // Relacionamentos, se necessário
    public function product()
    {
        return $this->belongsTo(products::class, 'product_id');
    }

    public function kit()
    {
        return $this->belongsTo(products::class, 'kit_id'); // se kits forem produtos
    }

    public function fornecedor()
    {
        return $this->belongsTo(User::class, 'fornecedor_id'); // ajuste para o model correto, se necessário
    }
}
