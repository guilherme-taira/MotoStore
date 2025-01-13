<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlingCreateUserByFornecedor extends Model
{
    use HasFactory;

    // Definir a tabela associada
    protected $table = 'bling_create_user_by_fornecedor';

    // Definir os campos que podem ser preenchidos
    protected $fillable = [
        'contato_id',
        'fornecedor_id',
        'bling_id',
    ];


    public static function ifExistFornecedor($fornecedor, $contato){
        $data = BlingCreateUserByFornecedor::where('fornecedor_id', $fornecedor)
            ->where('contato_id', $contato)
            ->first();

        return $data;
    }

    /**
     * Relacionamento com o model Contato.
     * Um registro desta tabela pertence a um único contato.
     */
    public function contato()
    {
        return $this->belongsTo(Contato::class, 'contato_id');
    }

    /**
     * Relacionamento com o model Fornecedor.
     * Um registro desta tabela pertence a um único fornecedor.
     */
    public function fornecedor()
    {
        return $this->belongsTo(User::class, 'fornecedor_id');
    }
}
