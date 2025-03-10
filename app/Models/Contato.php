<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contato extends Model
{
    use HasFactory;

    protected $table = "contatos";

    protected $fillable = [
        'integracao_bling_id',
        'nome',
        'email',
        'celular',
        'numeroDocumento',
        'tipo',
        'situacao',
        'rg',
        'cep',
        'endereco',
        'bairro',
        'municipio',
        'uf',
        'numero',
        'complemento',
        'bling_id',
        'ie'
    ];

    // Relacionamento com a tabela integracao_bling
    public function integracaoBling()
    {
        return $this->belongsTo(IntegracaoBling::class, 'integracao_bling_id');
    }
}
