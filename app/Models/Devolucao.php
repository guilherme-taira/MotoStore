<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Devolucao extends Model
{
    use HasFactory;

    protected $table = 'devolucoes'; // Nome da tabela no banco

    protected $fillable = [
        'rastreio',
        'id_venda',
        'id_user',
        'recebido_em',
        'quem_recebeu',
        'shippingId',
        'dados'
    ];

    protected $dates = [
        'recebido_em',
    ];

    /**
     * Relacionamento: Devolução pertence a uma Venda (OrderSite).
     */
    public function venda()
    {
        return $this->belongsTo(order_site::class, 'id_venda');
    }

    /**
     * Relacionamento: Devolução pertence a um Usuário (quem solicitou a devolução).
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * Relacionamento: Usuário que recebeu a devolução.
     */
    public function usuarioRecebedor()
    {
        return $this->belongsTo(User::class, 'quem_recebeu');
    }

    public static function getData($user) {
        $data = DB::table('devolucoes')
            ->join('order_site', 'devolucoes.id_venda', '=', 'order_site.numeropedido')
            ->join('pivot_site', 'order_site.id', '=', 'pivot_site.order_id')
            ->join('users', 'pivot_site.id_user', '=', 'users.id')
            ->join('product_site', 'pivot_site.product_id', '=', 'product_site.id')
            ->where('devolucoes.id_user', '=', $user)
            ->whereNull('devolucoes.data_recebimento') // CORRETO para verificar valores NULL
            ->paginate(10);

        return $data;
    }

}
