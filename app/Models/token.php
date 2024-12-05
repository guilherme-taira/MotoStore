<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class token extends Model
{
    use HasFactory;

    protected $table = 'token';

    // Campos que podem ser atualizados em massa
    protected $fillable = [
        'access_token',
        'datamodify',
        'user_id_mercadolivre',
        'refresh_token',
        'type',
        'user_id',
    ];

    public static function getId($sellerId){
        $data = DB::table('token')
        ->join('users', 'users.id', '=', 'token.user_id')
        ->where('token.user_id_mercadolivre', $sellerId)->first();
        return $data->id;
    }

}
