<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusPedido extends Model
{
    use HasFactory;

    // Definindo a tabela associada (opcional, pois o Laravel já infere pelo nome)
    protected $table = 'status_pedido';

    // Campos que podem ser preenchidos em massa
    protected $fillable = ['status_app_id', 'order_site_id', 'etiqueta'];

    // Relacionamento: StatusPedido pertence a um StatusApp
    public function statusApp()
    {
        return $this->belongsTo(StatusApp::class);
    }

    // Relacionamento: StatusPedido pertence a um OrderSite
    public function orderSite()
    {
        return $this->belongsTo( order_site::class);
    }
}
