<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variacao extends Model
{
    use HasFactory;

    protected $table = 'variacoes';

    protected $fillable = [
        'meli_variation_id',
        'sku',
        'price',
        'available_quantity',
        'attribute_combinations',
        'picture_ids',
        'id_mercadolivre'
    ];

    protected $casts = [
        'attribute_combinations' => 'array',
        'picture_ids' => 'array',
    ];

    // Relacionamento (opcional)
    public function kits()
    {
        return $this->hasMany(KitProductVariation::class, 'variacao_id');
    }
}
