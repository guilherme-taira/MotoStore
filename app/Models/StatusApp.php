<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusApp extends Model
{
    use HasFactory;

    protected $table = 'status_app';

     // Campos que podem ser preenchidos em massa
     protected $fillable = ['nome'];

     // Relacionamento: Um StatusApp pode ter muitos StatusPedido
     public function statusPedidos()
     {
         return $this->hasMany(StatusPedido::class);
     }
}
